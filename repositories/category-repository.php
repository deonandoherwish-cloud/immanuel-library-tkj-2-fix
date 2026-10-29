<?php
require_once __DIR__ . "/../config/database.php";

function getCategories()
{
  global $pdo;

  $query = "select categories.*,
            (select count(*) from books
            where books.category_id = categories.id)
            as total_books
            from categories";

  $stmt = $pdo->prepare($query);
  $stmt->execute();

  $categories = $stmt->fetchAll();

  return $categories;
}

function getCategory()
{
  $category = [
    "id" => 1,
    "name" => "Fiksi",
    "description" => "Novel dan cerita rekaan",
    "total_books" => 0,
  ];

  return $category;
}

function isCategoryExists($categoryId)
{
  global $pdo;

  $query = "select count(id) from categories where id = :category_id";

  $stmt = $pdo->prepare($query);
  $stmt->execute([
    'category_id' => $categoryId
  ]);

  $isCategoryExists = $stmt->fetchColumn() > 0;

  return $isCategoryExists;
}