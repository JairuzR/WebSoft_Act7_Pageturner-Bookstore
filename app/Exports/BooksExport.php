<?php

namespace App\Exports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class BooksExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = Book::with('category');
        
        if (!empty($this->filters['category_id'])) {
            $query->where('category_id', $this->filters['category_id']);
        }
        
        if (!empty($this->filters['search'])) {
            $query->where(function ($q) {
                $q->where('title', 'like', '%' . $this->filters['search'] . '%')
                  ->orWhere('author', 'like', '%' . $this->filters['search'] . '%');
            });
        }
        
        return $query;
    }

    public function headings(): array
    {
        return [
            'ID',
            'Category',
            'Title',
            'Author',
            'ISBN',
            'Price',
            'Stock',
            'Description',
            'Created At',
        ];
    }

    public function map($book): array
    {
        return [
            $book->id,
            $book->category->name ?? 'N/A',
            $book->title,
            $book->author,
            $book->isbn,
            $book->price,
            $book->stock_quantity,
            $book->description,
            $book->created_at->format('Y-m-d H:i:s'),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}