<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use App\Models\Review;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user only if it doesn't exist
        if (!User::where('email', 'admin@pageturner.com')->exists()) {
            User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@pageturner.com',
                'role' => 'admin',
            ]);
        }

        // Create customer users only if we have less than 10
        if (User::where('role', 'customer')->count() < 10) {
            User::factory(10)->create(['role' => 'customer']);
        }

        // Get all users (customers only)
        $customers = User::where('role', 'customer')->get();
        
        // Create categories if none exist
        if (Category::count() == 0) {
            $categories = Category::factory(8)->create();
        } else {
            $categories = Category::all();
        }

        // Create books for each category if few books exist
        if (Book::count() < 10) {
            foreach ($categories as $category) {
                Book::factory(5)->create(['category_id' => $category->id]);
            }
        }

        // Get all books
        $books = Book::all();
        
        // Create reviews - avoid duplicates
        if (Review::count() == 0 && $customers->count() > 0 && $books->count() > 0) {
            // Track which user-book combinations we've used
            $usedCombinations = [];
            
            foreach ($books as $book) {
                // Random number of reviews per book (0-5)
                $reviewCount = fake()->numberBetween(0, min(5, $customers->count()));
                
                for ($i = 0; $i < $reviewCount; $i++) {
                    // Pick a random customer
                    $user = $customers->random();
                    $key = $user->id . '-' . $book->id;
                    
                    // Check if this user has already reviewed this book
                    if (!in_array($key, $usedCombinations)) {
                        Review::factory()->create([
                            'user_id' => $user->id,
                            'book_id' => $book->id,
                        ]);
                        $usedCombinations[] = $key;
                    }
                }
            }
        }
    }
}