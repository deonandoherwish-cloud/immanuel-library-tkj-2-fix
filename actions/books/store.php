
<?php
require_once __DIR__ . "/../../config/database.php";

if (isset($_POST['store']) && $_SERVER['REQUEST_METHOD'] === "POST") {
    try {
        $pdo->beginTransaction();

        $title = htmlspecialchars($_POST['title']);
        $isbn = htmlspecialchars($_POST['isbn']);
        $year = htmlspecialchars($_POST['year']);
        $stock = htmlspecialchars($_POST['stock']);
        $description = htmlspecialchars($_POST['description']);
        $category_id = htmlspecialchars($_POST['category_id']);

        
        $query = "INSERT INTO books
                  (title, isbn, year, stock, description, category_id)
                  VALUES
                  (:title, :isbn, :year, :stock, :description, :category_id)";

        $stmt = $pdo->prepare($query);
        $stmt->execute([
            'title' => $title,
            'isbn' => $isbn,
            'year' => $year,
            'stock' => $stock,
            'description' => $description,
            'category_id' => $category_id,
        ]);

        $bookid = $pdo->lastInsertId();

       
        $query = "INSERT INTO book_author (book_id, author_id)
                  VALUES (:book_id, :author_id)";
        $stmt = $pdo->prepare($query);

        $authorids = $_POST['author_ids'];

        foreach ($authorids as $authorid) {
            $stmt->execute([
                'book_id' => $bookid,
                'author_id' => htmlspecialchars($authorid),
            ]);
        }

        $pdo->commit();

        header("Location: ../../pages/books/index.php");
        exit;

    } catch (PDOException $e) {
        if ($pdo->inTransaction()) {
            $pdo->rollBack();
        }

        throw $e;
    }
}
?>
