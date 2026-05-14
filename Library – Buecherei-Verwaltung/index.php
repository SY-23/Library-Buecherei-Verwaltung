<?php

declare(strict_types=1);

require_once 'classes/Book.php';
require_once 'classes/Shelf.php';
require_once 'classes/Library.php';

$library = new Library("Stadtbibliothek");

$book1 = new Book(
    'Der große Gatsby', 
    'Nikol',
    '000000000001', 
    '978-3-690-43061-6',
    'F. Scott Fitzgerald'
);

$book2 = new Book(
    'Moby Dick', 
    'Anaconda',
    '000000000002', 
    '978-3-86647-764-3',
    'Herman Melville'
);

$book3 = new Book(
    'Früchte des Zorns', 
    'Pan macmillan Ltd.',
    '000000000003', 
    '978-0-670-01690-7',
    'John Steinbeck'
);

$book4 = new Book(
    'Die Elemente',  
    'Europa-Lehrmittel',
    '000000000004', 
    '978-3-8085-5482-1',
    'Euklid'
);

$book5 = new Book(
    'Über die Entstehung der Arten', 
    'E. Schweizerbart’sche Verlagshandlung (E. Koch)',
    '000000000005',
    null,
    'Charles Darwin'
);

$book6 = new Book(
    'Das Kapital', 
    'Nikol',
    '000000000006',
    '978-3-86820-596-1',
    'Karl Marx'
);

$book7 = new Book(
    'Von Menschen und Mäusen', 
    'Splitter-Verlag',
    '000000000007',
    '978-3-9872104-2-6',
    'John Steinbeck'
);

$book8 = new Book(
    'Jenseits von Eden', 
    'Diana Verlag',
    '000000000008',
    null,
    'John Steinbeck'
);

$book9 = new Book(
    'Zärtlich ist die Nacht', 
    'Anaconda',
    '000000000009', 
    '978-3-7306-1373-3',
    'F. Scott Fitzgerald'
);

$book10 = new Book(
    'Alice im Wunderland',  
    'Nikol',
    '000000000010', 
    '978-3-86820-805-4',
    'Lewis Carroll'
);

$book11 = new Book(
    'Herr der Fliegen',  
    'Fischer Taschenbuch Verlag',
    '000000000011',
    '978-3-596-52214-9',
    'William Golding'
);

$book12 = new Book(
    'Pippi Langstrumpf', 
    'Verlag Friedrich Oetinger GmbH',
    '000000000012',
    '978-3-7891-1450-2',
    'Astrid Lindgren'
);

$book13 = new Book(
    'Ronja Räubertochter', 
    'Verlag Friedrich Oetinger GmbH',
    '000000000012',
    '978-3-7891-2940-7',
    'Astrid Lindgren'
);

$book15 = new Book(
    'GUINNESS WORLD RECORDS 2025',
    'Ravensburger Verlag GmbH', 
    '000000000015', 
    '978-3473480777',
    null
);

$book1->borrowBook();              // ausgeliehen
$book3->repairBook();              // Reparatur
$book4->missingBook();             // vermisst
$book6->sortedOutBook();           // aussortiert
$book15->markAsReferenceOnly();    // Präsenzbestand

$shelf1 = new Shelf(
    1, 
    'Belletristik'
);

$shelf2 = new Shelf(
    2, 
    'Sachbücher'
);

$shelf3 = new Shelf(
    3, 
    'Kinderbücher'
);

$shelf4 = new Shelf(
    1, 
    'Kochbücher'
);

$shelfAddResults = $library->addShelf(
    $shelf1, 
    $shelf2, 
    $shelf3, 
    $shelf4
);

$bookAddResultsShelf1 = $library->addBookToShelf(
    1, 
    $book1, 
    $book2, 
    $book3, 
    $book7, 
    $book8, 
    $book9
);

$bookAddResultsShelf2 = $library->addBookToShelf(
    2, 
    $book4, 
    $book5, 
    $book6,
    $book15
);

$bookAddResultsShelf3 = $library->addBookToShelf(
    3, 
    $book10, 
    $book11, 
    $book12, 
    $book13
);

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Library</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <main>
        <h1>Library – Bücherei-Verwaltung</h1> 
       
        <section>
            <h2>Buch-Status prüfen</h2>
            <div class="output">
                <?php echo $library->checkBookById('000000000001'); ?>
                <?php echo $library->checkBookByIsbn('978-3-86820-805-4'); ?>
            </div>
        </section>

        <section>
            <h2>Suche nach Autor: Lindgren</h2>
            <div class="output">
                <?php 
                  $results = $library->findBooksByAuthor('Lindgren');
                  echo $library->formatSearchResults($results);
                ?>
            </div>
        </section>

        <section>
            <h2>Suche nach Titel: Gatsby</h2>
            <div class="output">
                <?php 
                $results = $library->findBooksByTitle('Gatsby');
                echo $library->formatSearchResults($results);
                ?>
            </div>
        </section>
        
        <section>
            <h2>Inventar der Bibliothek</h2>
            <div class="output">
                <?php echo $library->showInventory(); ?>
            </div>
        </section>

        <section>
            <h2>Liste aller Regale</h2>
            <div class="output">
                <?php echo $library->displayShelves(); ?>
            </div>
        </section>

        <section>
            <h2>Bibliotheks-Statistiken</h2>
            <div class="stats">
                <?php echo $library->displayShelfCount(); ?><br>
                <?php echo $library->displayBookCount(); ?>
            </div>
        </section>
    
        <section>
            <h2>Bücher hinzufügen – Dublettenprüfung</h2>
            <div class="add-results">
                <?php foreach ($bookAddResultsShelf3 as $result): ?>
                    <div class="add-status <?php echo $result['status']; ?>">
                        <?php if ($result['status'] === 'duplicate'): ?>
                            <strong>⚠️ WARNUNG</strong><br>
                        <?php else: ?>
                            <strong>✅ OK</strong><br>
                        <?php endif; ?>

                        <?php echo htmlspecialchars($result['message']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

        <section>
            <h2>Regale hinzufügen – Dublettenprüfung</h2>
            <div class="add-results">
                <?php foreach ($shelfAddResults as $result): ?>
                    <div class="add-status <?php echo $result['status']; ?>">
                        <?php if ($result['status'] === 'duplicate'): ?>
                            <strong>⚠️ WARNUNG</strong><br>
                        <?php else: ?>
                            <strong>✅ OK</strong><br>
                        <?php endif; ?>
  
                        <?php echo htmlspecialchars($result['message']); ?>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

    </main>
</body>
</html>
