<?php
$kuziCountList = array();
$kuziTotalCount = 0;
try {
    $host = getenv('MYSQL_HOST');
    $user = getenv('MYSQL_USER');
    $password = getenv('MYSQL_PASSWORD');
    $database = getenv('MYSQL_DATABASE');
    $charset = getenv('MYSQL_CHARSET');
    $pdo = new PDO("mysql:host={$host};dbname={$database};charset={$charset}", $user, $password,
        array(PDO::ATTR_EMULATE_PREPARES => false, PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));

    $stmt = $pdo->query("SELECT * FROM kuzi_pattern");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $kuziCountList[$row["id"]] = $row["max"];
        $kuziTotalCount += $row["max"];
    }

    $stmt = $pdo->query("SELECT * FROM kuzi_history");
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $kuziCountList[$row["kuzi_pattern_id"]]--;
        if ( $kuziCountList[$row["kuzi_pattern_id"]] < 0 ) {
            throw new Exception("くじがリセットされていないのでくじが引けません");
        }
        $kuziTotalCount--;
        if ( $kuziTotalCount <= 0 ) {
            throw new Exception("くじがリセットされていないのでくじが引けません");
        }
    }

    $index = mt_rand(0, $kuziTotalCount - 1);
    $lotteryId = -1;
    foreach ( $kuziCountList as $patternId => $rest ) {
       $lotteryId = $patternId;
       $index -= $rest;
       if ( $index < 0 ) {
           break;
       }
    }
    if ( $lotteryId < 0 ) {
         throw new Exception("くじがリセットされていないのでくじが引けません");
    }

    $insert = <<<___EOS___
INSERT INTO kuzi_history (kuzi_pattern_id, create_date) 
VALUES (:lottery_id, now())
___EOS___;
    $stmt = $pdo->prepare($insert);
    $stmt->bindValue(':lottery_id', $lotteryId, PDO::PARAM_INT);
    $ret = $stmt->execute();

    // TODO: くじが空になった時の処理
    // https://github.com/arith-alexander/vpc_docker/issues/6

} catch (PDOException $e) {
    exit('データベース接続失敗。' . $e->getMessage());
}
?>

