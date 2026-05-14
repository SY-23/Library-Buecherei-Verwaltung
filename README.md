# Library Bücherei-Verwaltung

A Simple Library Management System in PHP.

## Structure

library/
│
├── index.php
├── README.md
│
├── classes/
│   ├── Book.php
│   ├── Shelf.php
│   └── Library.php
│
└── assets/
└── style.css

## What this project aims to achieve

This project aims to provide a simple library management application that organizes books across multiple shelves and tracks their location and status. 
It is mainly intended as a work sample for applications within the IT sector.

## Core Functionality

The system supports managing shelves and books, prevents duplicates, and enables searches by title, author, ID, or ISBN. It tracks book statuses—including available, borrowed, in repair, missing, sorted out, or reference-only—while providing a full inventory overview and statistics on total shelves and books.

## Classes

The program utilizes the three classes Book, Shelf, and Library.

-> Book stores bibliographic information and manages its own status.

-> Shelf stores books locally and provides search/display operations.

-> Library controls which shelves and books are added to the complete inventory and prevents duplicate shelf labels and duplicate book IDs.

### Library.php

The Library class represents the entire library and manages all shelves in the system. Its main responsibility is to register shelves, add books to existing shelves, and notify the user when duplicate book IDs or shelf labels already exist. It also provides an overview of the library’s contents and statistics, such as the complete inventory, the total number of books, the total number of shelves, and the specific book counts for each shelf, as well as individual searches for ISBNs, IDs, titles, and authors in the collection.

Properties:

- name: The name of the library.

- shelfList: An array containing all shelves in the library.

Methods:

-> __construct(string $name): Creates a new library with the given name.

-> addBookToShelf(int $label, Book ...$books): Adds one or more books to the shelf while preventing duplicate IDs.

-> addShelf(Shelf ...$shelves): Adds one or more shelves while preventing duplicates.

->  containsBookById(string $id): Checks whether a book with a given ID already exists in the library.

->  checkBookByIsbn(string $isbn): Searches for a book by ISBN and returns its shelf location and status.

->  checkBookById(string $id): Searches for a book by ID and returns its shelf location and status.

->  findBooksByAuthor(string $author): Finds all books written by a specific author across all shelves.

->  findBooksByTitle(string $title): Finds all books whose title matches a given search term.

->  formatSearchResults(array $results): Formats search results into a readable text output.

->  showInventory(): Displays the complete inventory of the library, including all shelves and books.

-> displayShelves(): Displays all shelves in the library, including the specific book count for each shelf.

->  getShelfCount(): Returns the total number of shelves.

->  getBookCount(): Returns the total number of books across all shelves.

->  displayShelfCount(): Returns a text representation of the shelf count.

->  displayBookCount(): Returns a text representation of the book count.

->  containsShelf(int $label): Checks whether a shelf with a given label already exists.

### Shelf.php

The Shelf class represents a single shelf within the library. It stores a shelf label, a category, and a local collection of books. The class allows books to be added to the shelf and provides search functions for finding books by title, ID, or ISBN. It also supports displaying the shelf contents in a readable format, including the shelf information and all books stored on it.

Properties:

- label: The numeric identifier of the shelf.

- category: The category or subject area of the shelf.

- books: An array containing all books stored on the shelf.

Methods:

-> __construct(int $label, string $category): Creates a shelf with a numeric label and a category name.

->  getBooks(): Returns all books stored on the shelf.

->  findBooksByTitle(string $title): Finds books on the shelf by title.

->  display(): Returns a text representation of the shelf and its books.

->  getLabel(): Returns the shelf label.

->  findBookById(string $id): Finds a book on the shelf by its ID.

->  findBookByIsbn(string $isbn): Finds a book on the shelf by its ISBN.

## Book.php

The Book class stores bibliographic data for a book, including title, author, publisher, ID, and optional ISBN, and manages the book’s current status. It provides methods to change the status, for example by borrowing, returning, repairing, marking as missing, sorting out, or reactivating. The system also supports reference-only books. These books remain part of the visible inventory and can be found through search, but they cannot be borrowed. This reflects common library workflows for dictionaries, encyclopedias, yearbooks, and other on-site reference material.
The class also supplies getters for its data and a formatted string representation for display purposes.

Properties:

- title: The book title.

- author: The author of the book.

- publisher: The publisher of the book.

- id: The unique internal book ID.

- isbn: The optional ISBN number.

- status: The current status of the book.

Methods:

->  __construct
      (
      string $title, 
      string $publisher, 
      string $id, 
      ?string $isbn = null, 
      ?string $author = null, 
      string $status = self::AVAILABLE
      ): Creates a new book with the given data and status.

->  repairBook(): Sets the status to repair, if allowed.

->  missingBook(): Marks the book as missing, if allowed.

->  sortedOutBook(): Marks the book as sorted out, if allowed.

->  reactivateBook(): Sets a sorted-out book back to available.

->  borrowBook(): Marks the book as borrowed, if it is available.

->  returnBook(): Marks the book as available again, if it is borrowed.

->  markAsReferenceOnly(): Marks a non-borrowed book as reference-only.

->   __toString(): Returns a readable text representation of the book.

->  getStatus(): Returns the internal status value.

->  getTitle(): Returns the title.

->  getAuthor(): Returns the author.

-> getPublisher(): Returns the publisher.

->  getId(): Returns the book ID.

->  getIsbn(): Returns the ISBN, if available.

->  getStatusText(): Returns a human-readable description of the current status.

## Key Design Decisions

A key design principle in this application is the clear separation of responsibilities. The Book class only stores and manages its own data, the Shelf class is responsible for organizing and managing books, and the Library class handles the collection of shelves. This structure keeps the code easy to understand, maintain, and extend.

### One Library Instead of Multiple Libraries

I deliberately chose to implement a single Library instance rather than introducing an additional Registry class for managing multiple libraries. I considered this extra layer of abstraction, but decided against it because the expected benefits did not justify the additional technical and conceptual complexity for this version of the project. That said, such a class could be a sensible extension in a future release.

### No Singleton

I considered using a Singleton pattern for the Library class, but decided against it because it would have made the class unnecessarily complex and harder to maintain. In addition, it would have made it more difficult to extend the application later to support multiple branches.

### Internal ID in Addition to Optional ISBN 

A crucial modeling decision was to introduce an internal ID property for books alongside the optional ISBN. Originally, books were planned to only have core data like title, author, ISBN, and status. However, I added the internal ID — which uniquely identifies a specific physical copy in the library's inventory — and made the ISBN optional, which instead identifies a book edition.

Key reasons for this approach:

- Not every book necessarily has an ISBN.

- Older books or special collections may lack ISBNs entirely.

- A library can hold multiple copies of the same ISBN.

- Different copies of the same title require distinct internal IDs.

- The internal ID serves as the unique inventory number.

- The ISBN is treated as optional bibliographic supplementary information.

Duplicate checking is performed library-wide by internal ID, not ISBN.

### "Sorted Out" Status Instead of Deletion 

Another status property reflecting real library operations is sortedOut. This status indicates that a book has been removed from the lendable inventory but remains preserved as an archival record.

Key reasons for this approach:

- Customers may inquire about books that were previously available.

- Staff can explain that a book was once held but has since been sorted out.

- Sorted-out books can potentially be reactivated later.

- Borrowing histories remain complete and intact.

- Statistics are not distorted by data loss.

- Current inventory is clearly distinguished from historical records.

Common reasons for sorting out books:

- Lack of demand

- Irreparably damaged

- Content outdated

- Legally or politically problematic

- Inventory cleanup

### Status Transition Logic

The six status values (available, borrowed, repair, missing, sortedOut, reference-only) follow a strict business logic reflecting real library operations:

- Only available books can be borrowed.

- Only borrowed books can be returned.

- sortedOut books are not lendable.

- Books with missing, repair, or sortedOut status remain visible in search results.

This ensures realistic workflows while maintaining data integrity and transparency across the entire inventory.


### No database in version one

This initial version of the program is a Proof of Concept rather than a fully-fledged library application. Currently, it operates without a database, $_POST requests, or interactive forms, relying exclusively on static test data hardcoded into the system . Its primary purpose is to serve as a technical demonstration of Object-Oriented Programming (OOP) principles in PHP.

## Current Environment

Replit is used as the primary development and presentation platform for this project.

## Future Roadmap

While the current version focuses on core logic, the "Library" system is designed to be extensible. Future iterations are planned to include:

-> Enriched Content: Integration of detailed metadata such as publication year, and brief synopses.

->  Advanced Circulation Logic: Extended circulation workflows such as loan periods, due dates, and a comprehensive fee system (annual memberships, late fees, and discounts).

->  Catalog Expansion: Supporting diverse media types beyond books, such as DVDs, CD-ROMs, and digital editions.

->  Full CRUD Implementation: A robust system for staff to Create, Read, Update, and Delete records, with limited access for patrons.

->  Security & User Management: Secure login systems, user profiles, and role-based access control.

->  Scalability & Architecture: * Support for multiple library branches via a central LibraryRegistry.  

      Transitioning to a modern tech stack (e.g., Laravel, React, Composer).

      Integration of external APIs to automatically fetch media information.

## Run locally

Start PHP's built-in development server from the project root:

```bash
php -S 0.0.0.0:5000 -t .
```

Then open `http://localhost:5000` in your browser.

## Requirements

- PHP 8+
- No Composer, no JavaScript, no database, no framework.
