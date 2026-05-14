<?php

declare(strict_types=1);

class Library 
{
    private string $name;
    private array $shelfList = [];
    
    public function __construct(string $name) 
    {
        $this->name = $name;
    }
    
    /**
     * @param int $label, Book ...$books
     * @return array<int, array{status: string, message: string}>
     */
    public function addBookToShelf(int $label, Book ...$books): array 
    {
        $results = []; 
        if (!isset($this->shelfList[$label])) {
            return ['error' => "Regal $label existiert nicht."];
        }

        $targetShelf = $this->shelfList[$label]; 
        foreach ($books as $book) {
            if ($this->containsBookById($book->getId())) {
                $results[] = [
                    'status' => 'duplicate',
                    'message' => "Buch ID {$book->getId()} ist bereits vorhanden."
                ];
            } else {
                
                $targetShelf->addBook($book); 
                $results[] = [
                    'status' => 'added',
                    'message' => "Buch '{$book->getTitle()}' in Regal {$label} gelegt."
                ];
            }
        }
        
        return $results;
    }

    /**
     * @param Shelf ...$shelves
     * @return array<int, array{shelf: Shelf, status: string, message: string}>
     */
    public function addShelf(Shelf ...$shelves): array
    {
        $results = []; 
        foreach ($shelves as $shelf) {
            $label = $shelf->getLabel();
            if (!$this->containsShelf($label)) {
                $this->shelfList[$label] = $shelf;
                $results[] = [
                    'shelf' => $shelf,
                    'status' => 'added',
                    'message' => "Regal mit der Bezeichnung '{$label}' wurde hinzugefügt."
                ];
            } else {
                $results[] = [
                    'shelf' => $shelf,
                    'status' => 'duplicate',
                    'message' => "Ein Regal mit der Bezeichnung '{$label}' ist bereits vorhanden."
                ];
            }
        }

        return $results;
    }

    public function containsBookById(string $id): bool 
    {
        foreach ($this->shelfList as $shelf) {
            foreach ($shelf->getBooks() as $book) {
                if ($book->getId() === $id) {
                    return true;
                }
            }
        }
        
        return false; 
    }
    
    public function checkBookByIsbn(string $isbn): string 
    {
        foreach ($this->shelfList as $shelf) {
            $book = $shelf->findBookByIsbn($isbn);
            if ($book !== null) {
                return '<br>Das Buch ' . $book->getTitle() .
                       '<br>von ' . $book->getAuthor() .
                        '<br>Verlag: ' . $book->getPublisher() .
                       '<br>mit der ISBN ' . $isbn .
                       '<br>befindet sich in Regal ' . $shelf->getLabel() .
                       '<br>Status: ' . $book->getStatusText() . '<br>';
            }
        }

        return '<br>Kein Buch mit der ISBN ' . $isbn .
               ' vorhanden <br>im Sortiment der ' . $this->name . '.<br>';
    }

    public function checkBookById(string $id): string 
    {
        foreach ($this->shelfList as $shelf) {
            $book = $shelf->findBookById($id);
            if ($book !== null) {
                return '<br>Das Buch ' . $book->getTitle() .
                       '<br>von ' . $book->getAuthor() .
                        '<br>Verlag: ' . $book->getPublisher() .
                       '<br>mit der ID ' . $id .
                       '<br>befindet sich in Regal ' . $shelf->getLabel() .
                       '<br>Status: ' . $book->getStatusText() . '<br>';
            }
        }

        return '<br>Das Buch mit der ID ' . $id . 
               ' befindet sich <br>nicht im Sortiment der ' . $this->name . '.<br>';
    }

    /**
     * Finds all books by a given author (case-insensitive) across all shelves.
     *
     * @param string $author The author name (or part of it) to search for
     * @return array<int, array{book: object, shelf: object}> An array of associative arrays, each containing a book and its shelf
     */ 
    public function findBooksByAuthor(string $author): array 
    { 
        $results = [];
        foreach ($this->shelfList as $shelf) {
            foreach ($shelf->getBooks() as $book) {
                $authorName = $book->getAuthor();
                if (stripos($authorName, $author) !== false) {
                    $results[] = ['book' => $book, 'shelf' => $shelf];
                }
            }
        }
        
        return $results;
    }
    
    /**
     * @param array<int, array{book: Book, shelf: Shelf}> $results
     * @return string
     */
    public function formatSearchResults(array $results): string 
    {
        if (empty($results)) {
            return 'Keine Bücher gefunden.';
        }
        $output = '';
        foreach ($results as $result) {
            $book = $result['book'];
            $shelf = $result['shelf'];
            $statusText = $book->getStatusText();
            $output .= "<br>" . 'Titel: ' . $book->getTitle() . "<br>";
            $output .= 'Autor: ' . $book->getAuthor() . "<br>";
            $output .= 'Verlag: ' . $book->getPublisher() . "<br>";
            $output .= 'ID: ' . $book->getId() . "<br>";
            $output .= 'Regal: ' . $shelf->getLabel() . "<br>";
            $output .= 'Status: ' . $statusText . "<br>";
            $output .= '-------------------------' . "<br>";
        }
        
        return $output;
    }

    public function showInventory(): string
    {
        $output = "Inventar der {$this->name}<br>";
        $output .= "========================<br>";
        if (empty($this->shelfList)) {
            return $output . "Keine Regale vorhanden.<br>";
        }
        foreach ($this->shelfList as $shelf) {
            $output .= $shelf->display();
            $output .= "<br>------------------------<br>";
        }
        
        return $output;
    }

    public function displayShelves(): string 
    {
        $output = "";
        if (empty($this->shelfList)) {
            return $output . "Keine Regale in dieser Bibliothek vorhanden.";
        }
        foreach ($this->shelfList as $shelf) {
            $output .= "<br>" . $shelf; 
            $output .= "Anzahl Bücher: " . count($shelf->getBooks()) . "<br>";
        }

        return $output;
    }

    public function getShelfCount(): int
    {
        return count($this->shelfList);
    }
    
    public function getBookCount(): int
    {
        $count = 0;
        foreach ($this->shelfList as $shelf) {
            $count += count($shelf->getBooks());
        }
        
        return $count;
    }

    public function displayShelfCount(): string
    {
        $output = "Anzahl Regale: " . $this->getShelfCount();
        return $output;
    }

    public function displayBookCount(): string
    {
        $output = "Anzahl Bücher: " . $this->getBookCount();
        return $output;
    }

    public function containsShelf(int $label): bool
    {
        return isset($this->shelfList[$label]);
    }
    
    /**
     * @return array<int, array{book: Book, shelf: Shelf}>
     */
    public function findBooksByTitle(string $title): array
    {
        $results = [];
        foreach ($this->shelfList as $shelf) {
            foreach ($shelf->findBooksByTitle($title) as $book) {
                $results[] = ['book' => $book, 'shelf' => $shelf];
            }
        }
        
        return $results;
    }
   
}

