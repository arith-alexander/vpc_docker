<?php
try {
    $host = getenv('MYSQL_HOST');
    $user = getenv('MYSQL_USER');
    $password = getenv('MYSQL_PASSWORD');
    $database = getenv('MYSQL_DATABASE');
    $charset = getenv('MYSQL_CHARSET');

    $pdo = new PDO("mysql:host={$host};dbname={$database};charset={$charset}", $user, $password,
        array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    $insert = <<<___EOS___
INSERT INTO members (name, age, created, modified, deleted, sex) 
VALUES (:name, :age, now(), now(), 0, :sex)
___EOS___;

    $stmt = $pdo->prepare($insert);

    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':age', 14, PDO::PARAM_INT);
    $stmt->bindValue(':sex', 0, PDO::PARAM_INT);
    $name = '太郎';
    $ret = $stmt->execute();

    $stmt = $pdo->query("SELECT * FROM members");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo join("|", $row) . "<br>\n";
    }


} catch (PDOException $e) {
    exit('データベース接続失敗。' . $e->getMessage());
}