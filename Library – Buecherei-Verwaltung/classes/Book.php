<?php

declare(strict_types=1);

class Book
{
    private string $title;
    private ?string $author;
    private string $publisher;     
    private string $id;
    private ?string $isbn = null;
    private string $status;

    private const AVAILABLE = 'available';
    private const BORROWED = 'borrowed';
    private const REPAIR = 'repair';
    private const MISSING = 'missing';
    private const SORTED_OUT = 'sortedOut';
    private const REFERENCE_ONLY = 'referenceOnly';

    private const ALLOWED_STATUSES = [
        self::AVAILABLE,
        self::BORROWED,
        self::REPAIR,
        self::MISSING,
        self::SORTED_OUT,
        self::REFERENCE_ONLY,
    ];
    
    public function __construct
    (
        string $title,
        string $publisher,
        string $id,
        ?string $isbn = null,
        ?string $author = null,
        string $status = self::AVAILABLE
    ) {
        $this->title = $title;
        $this->publisher = $publisher;
        $this->id = $id;
        $this->isbn = $isbn;
        $this->author = $author;

        if (!in_array($status, self::ALLOWED_STATUSES, true)) {
            throw new InvalidArgumentException("Ungültiger Status");
        }

        $this->status = $status;
    }

    public function repairBook(): bool
    {
        if ($this->status === self::BORROWED || $this->status === self::SORTED_OUT){
            return false;
        }
        
        $this->status = self::REPAIR;
        return true;
    }

    public function missingBook(): bool
    {
        if ($this->status === self::SORTED_OUT) {
            return false;
            
        }
        
        $this->status = self::MISSING;
        return true;
    }

    public function sortedOutBook(): bool
    {
        if ($this->status === self::BORROWED) {
            return false;
        }
        
        $this->status = self::SORTED_OUT;
        return true;
    }

    public function reactivateBook(): bool
    {
        if ($this->status !== self::SORTED_OUT) {
            return false;
        }
        
        $this->status = self::AVAILABLE;
        return true;
    }

    public function borrowBook(): bool
    {
        if ($this->status !== self::AVAILABLE) {
            return false;
        }

        $this->status = self::BORROWED;
        return true;
    }

    public function returnBook(): bool
    {
        if ($this->status !== self::BORROWED) {
            return false;
        }

        $this->status = self::AVAILABLE;
        return true;
    }

    public function markAsReferenceOnly(): bool
    {
        if ($this->status === self::BORROWED) {
            return false;
        }
    
        $this->status = self::REFERENCE_ONLY;
        return true;
    }
    
    public function __toString(): string
    {
        $isbnText = $this->isbn ?? 'keine ISBN vorhanden.';

        return "Titel: {$this->title}<br>"
            . "Autor: {$this->getAuthor()}<br>"
            . "Verlag: {$this->publisher}<br>"
            . "ID: {$this->id}<br>"
            . "ISBN: {$isbnText}<br>"
            . "Status: {$this->getStatusText()}<br>";
    }
    
    public function getStatus(): string
    {
        return $this->status;
    }
    
    public function getTitle(): string
    {
        return $this->title;
    }
    
    public function getAuthor(): string
    {
        return $this->author ?? 'unbekannter Autor';
    }

    public function getPublisher(): string
    {
        return $this->publisher;
    }
    
    public function getId(): string
    {
        return $this->id;
    }
    
    public function getIsbn(): ?string
    {
        return $this->isbn; 
    }
    
    public function getStatusText(): string
    {
        return match ($this->status) {
            self::AVAILABLE => 'Das Buch ist verfügbar.',
            self::BORROWED => 'Das Buch ist ausgeliehen.',
            self::REPAIR => 'Das Buch ist in Reparatur.',
            self::MISSING => 'Das Buch wird vermisst.',
            self::SORTED_OUT => 'Das Buch wurde aussortiert.',
            self::REFERENCE_ONLY => 'Das Buch ist nur vor Ort nutzbar.',
            default => 'unbekannt',
        };
    }
}
