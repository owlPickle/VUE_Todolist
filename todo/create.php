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
    echo "DB connected failed";
    exit;
}

$sql = "INSERT INTO todolist (content) 
        VALUES (:content)";
$statement = $pdo->prepare($sql);
$statement->bindValue(':content', $_POST["content"], PDO::PARAM_STR);
$result = $statement->execute();

if ($result) {
    echo json_encode(['id' => $pdo->lastInsertId()]);
} else {
    var_dump($pdo->errorInfo());
}
