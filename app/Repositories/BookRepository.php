<?php

namespace App\Repositories;

use App\Models\Book;

class BookRepository
{
    public function all()
    {
        return Book::all(); // Lấy tất cả sách
    }

    public function find($id)
    {
        return Book::find($id); // Tìm sách theo ID
    }

    
}