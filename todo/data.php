<?php
header('Content-Type: application/json; charset=utf-8');
include "../../db.php";

try {
    $pdo = new PDO(
        "mysql:host=$db[host];dbname=$db[dbname];charset=$db[charset];port=$db[port]",
        $db['username'],
        $db['password']
    );
} catch (PDOException $e) {
    echo 'DB connected failed';
    exit;
}

$sql = "SELECT * FROM todolist";
$statement = $pdo->prepare($sql);
$statement->execute();
$todos = $statement->fetchAll(PDO::FETCH_ASSOC);

if ($todos) {
    echo json_encode($todos);
}

