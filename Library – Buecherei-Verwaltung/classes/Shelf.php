<?php

declare(strict_types=1);

class Shelf 
{
    private int $label;
    private string $category;
    private array $books = [];

    public function __construct(int $label, string $category) 
    {
        $this->label = $label;
        $this->category = $category;
    }

    public function addBook(Book $book): void 
    {
        $this->books[] = $book;
    }

    public function getBooks(): array 
    {
        return $this->books;
    }

    public function __toString(): string
    {

        return "Regal: {$this->label}<br>"
            . "Kategorie: {$this->category}<br>";
            
    }
    
    /**
     * @return Book[]
     */
    public function findBooksByTitle(string $title): array 
    {
        $results = [];
        foreach ($this->books as $book) {
            if (stripos($book->getTitle(), $title) !== false) {
                $results[] = $book;
            }
        }

        return $results;
    }

    public function display(): string 
    {
        $output = "Regal {$this->label}: {$this->category}\n";
        if (empty($this->books)) {
            return $output . "Keine Bücher in diesem Regal";
        }
        foreach ($this->books as $book) {
            $output .= $book . "\n";
        }
        
        return $output;
    }

    public function getLabel(): int 
    {
        return $this->label;
    }

    public function findBookById(string $id): ?Book 
    {
        foreach ($this->books as $book) {
            if ($book->getId() === $id) {
                return $book;
            }
        }
        
        return null;
    }

    public function findBookByIsbn(string $isbn): ?Book 
    {
        foreach ($this->books as $book) {
            if ($book->getIsbn() === $isbn) {
                return $book;
            }
        }
    
        return null;
    }
        
}
    