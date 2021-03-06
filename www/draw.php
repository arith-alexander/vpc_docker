<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function main()
{
    //おみくじをすでに引いた場合はメッセージを表示するだけ
    if (isset($_COOKIE["omikuji"]) && $_COOKIE["omikuji"]) {
        return;
    }

    define('TYOUKITI_PATTERN_ID', 1);
    define('DAIKITI_PATTERN_ID', 2);
    define('TYUUKITI_PATTERN_ID', 3);
    define('KITI_PATTERN_ID', 4);

    function kuziReset($pdo)
    {
        $delete = <<<___EOS___
DELETE FROM kuzi_history WHERE kuzi_pattern_id!=:pattern_id
___EOS___;
        $stmt = $pdo->prepare($delete);
        $stmt->bindValue(':pattern_id', TYOUKITI_PATTERN_ID, PDO::PARAM_INT);
        $stmt->execute();
    }


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

        // 元々のくじ数を取得
        $stmt = $pdo->query("SELECT * FROM kuzi_pattern");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $kuziCountList[$row["id"]] = $row["max"];
            $kuziTotalCount += $row["max"];
        }

        // 元々のくじ数から既に引いたくじ数を減らす
        $stmt = $pdo->query("SELECT * FROM kuzi_history");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $kuziCountList[$row["kuzi_pattern_id"]]--;
            if ($kuziCountList[$row["kuzi_pattern_id"]] < 0) {
                throw new Exception("データ不整合でくじが引けません");
            }
            $kuziTotalCount--;
            if ($kuziTotalCount <= 0) {
                throw new Exception("くじがリセットされていないのでくじが引けません");
            }
        }

        // 抽選してどのパターンIDかを求める
        $randIndex = mt_rand(0, $kuziTotalCount - 1);
        $lotteryId = -1;
        foreach ($kuziCountList as $patternId => $rest) {
            $lotteryId = $patternId;
            $randIndex -= $rest;
            if ($randIndex < 0) {
                break;
            }
        }
        if ($lotteryId < 0) {
            throw new Exception("データ不整合でくじが引けません");
        }

        // 引いたくじの履歴を残す
        $insert = <<<___EOS___
INSERT INTO kuzi_history (kuzi_pattern_id, create_date) 
VALUES (:lottery_id, now())
___EOS___;
        $stmt = $pdo->prepare($insert);
        $stmt->bindValue(':lottery_id', $lotteryId, PDO::PARAM_INT);
        $stmt->execute();
        if ($kuziTotalCount <= 1) {
            // くじが空になった時の処理
            kuziReset($pdo);
        }

    } catch (PDOException $e) {
        exit('データベース接続失敗。' . $e->getMessage());
    }

// どの画像を表示するかの場合わけ
    $lotteryImg = '';
    switch ($lotteryId) {
        case TYOUKITI_PATTERN_ID:
            $lotteryImg = '<img class="lottery-image" src="/img/tyoukiti.png" alt="超吉" />';
            break;
        case DAIKITI_PATTERN_ID:
            $lotteryImg = '<img class="lottery-image" src="/img/daikiti.png" alt="大吉" />';
            break;
        case TYUUKITI_PATTERN_ID:
            $lotteryImg = '<img class="lottery-image" src="/img/tyuukiti.png" alt="中吉" />';
            break;
        case KITI_PATTERN_ID:
            $lotteryImg = '<img class="lottery-image" src="/img/kiti.png" alt="吉" />';
            break;
    }

// テーマの一覧とその内容を取得する
    $sql = <<<___EOS___
SELECT f.id, content, t.name, f.pattern_id
FROM fortune f
LEFT JOIN theme t on f.theme_id = t.id
WHERE f.pattern_id = $lotteryId
___EOS___;
    $stmt = $pdo->query($sql);
    $themes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    setcookie("omikuji", 1);

    return array($lotteryImg, $themes);
}

list($lotteryImg, $themes) = main();
?>

<!DOCTYPE html>
<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="/css/draw.css">
        <link rel="stylesheet" type="text/css" href="/css/reset.css">
        <script>
            $(document).ready(function() {
                $('body').fadeIn(3000);
            });
        </script>
        <meta charset='utf-8'>
        <title>おみくじ結果</title>
    </head>
    <body>

        <?php
        //おみくじをすでに引いた場合はメッセージを表示するだけ
        if (isset($_COOKIE["omikuji"]) && $_COOKIE["omikuji"]) {
            echo '<div class="already-used">すでにおみくじを引いています！</div>';
        //おみくじを初めて引く場合
        } else { ?>
            <div class="lottery">
                <?php echo $lotteryImg; ?>
            </div>
            <div class="lottery-contents <?php if($themes[0]["pattern_id"] == TYOUKITI_PATTERN_ID) echo "lottery-contents-choukiti"; ?>">
                <?php foreach($themes as $theme) { ?>
                    <div class="theme-name <?php if($theme["pattern_id"] == TYOUKITI_PATTERN_ID) echo "theme-name-choukiti"; ?>"><?=$theme["name"]?></div>
                    <div class="fortune-contents"><?=$theme["content"]?></div>
                <? } ?>
            </div>
        <?php } ?>
    </body>
</html>

