<?php

namespace App\Imports;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnFailure;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Validators\Failure;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterImport;
use Maatwebsite\Excel\Events\ImportFailed;
use App\Notifications\ImportCompleted;
use App\Notifications\ImportFailed as ImportFailedNotification;

class BooksImport implements 
    ToCollection, 
    WithHeadingRow, 
    WithValidation, 
    WithChunkReading, 
    ShouldQueue,
    SkipsOnFailure,
    WithEvents
{
    use Importable, SkipsFailures;

    private $userId;
    // private $failures = [];

    public function __construct($userId = null)
    {
        $this->userId = $userId;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // Find or create category
            $category = Category::firstOrCreate(
                ['name' => $row['category']],
                ['description' => 'Imported category']
            );

            Book::updateOrCreate(
                ['isbn' => $row['isbn']],
                [
                    'category_id' => $category->id,
                    'title' => $row['title'],
                    'author' => $row['author'],
                    'price' => $row['price'],
                    'stock_quantity' => $row['stock_quantity'] ?? 0,
                    'description' => $row['description'] ?? null,
                ]
            );
        }
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|string|unique:books,isbn',
            'price' => 'required|numeric|min:0',
            'stock_quantity' => 'nullable|integer|min:0',
            'category' => 'required|string|max:255',
        ];
    }

    public function customValidationMessages()
    {
        return [
            'isbn.unique' => 'The ISBN :input already exists in the system.',
            'price.min' => 'Price must be greater than or equal to 0.',
        ];
    }

    public function chunkSize(): int
    {
        return 100;
    }

    public function registerEvents(): array
    {
        return [
            AfterImport::class => function (AfterImport $event) {
                if ($this->userId) {
                    $user = \App\Models\User::find($this->userId);
                    if ($user) {
                        $user->notify(new ImportCompleted(
                            'Books import completed successfully',
                            $this->failures
                        ));
                    }
                }
            },
            ImportFailed::class => function (ImportFailed $event) {
                if ($this->userId) {
                    $user = \App\Models\User::find($this->userId);
                    if ($user) {
                        $user->notify(new ImportFailedNotification(
                            'Books import failed: ' . $event->getException()->getMessage()
                        ));
                    }
                }
            },
        ];
    }

    public function onFailure(Failure ...$failures)
    {
        $this->failures = array_merge($this->failures, $failures);
    }
}