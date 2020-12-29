<!DOCTYPE html>

<html lang="ja">

<head>

    <meta charset="UTF-8">

    <title>mission_5-1</title>
</head>

<body>
    <?php
        $dsn = '********';
        $user = '****';
        $password = '*******';
        $pdo = new PDO($dsn, $user, $password, **************);
        $ename = "";
        $comment = "";
        if(!empty($_POST['edit'])){
            if(!empty($_POST["pass"])){
                $id = $_POST['edit_num'];
                $pass = $_POST["pass"];
                if(checkpass($pdo, $id, $pass) == TRUE){
                    $sql = 'SELECT * FROM db51 WHERE id=:id ';
                    $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
                    $stmt->execute();                             // ←SQLを実行する。
                    $results = $stmt->fetchAll(); 
                    foreach ($results as $row){
                        $ename = $row['name'];
                        $comment = $row['comment'];
                    }   
                }
            }
        }
    ?>
    <form action="" method="post">
        <input type="text" name="name" placeholder="name" value="<?=$ename?>"><br>
        <input type="text" name="comment" placeholder="comment" value="<?=$comment?>"><br>
        <input type="text" name="pass" placeholder="password"><br>
        <input type="text" name="delete_num" placeholder="削除番号"><br>
        <input type="text" name="edit_num" placeholder="編集番号"><br>
        <input type="hidden" name="output_enum" value="<?php if( !empty($_POST['edit_num']) ){echo $_POST['edit_num'];}?>"><br>
        <input type="submit" name="submit">
        <input type="submit" name="delete" value="削除">
        <input type="submit" name="edit" value="編集">
    </form>
    <?php

        $dsn = '*********';
        $user = '********';
        $password = '****';
        $pdo = new PDO($dsn, $user, $password, ************);

        $sql = "CREATE TABLE IF NOT EXISTS db51"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"
        . "comment TEXT,"
        . "dt char(32),"
        . "pass char(32)"
        .");";
        $stmt = $pdo->query($sql);

        function dbecho($pdo){
            $sql = 'SELECT * FROM db51 ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
            foreach ($results as $row){
                //$rowの中にはテーブルのカラム名が入る
                echo $row['id'].' ';
                echo $row['name'].' ';
                echo $row['comment'].' ';
                echo $row['dt'].'<br>';
                echo "<hr>";
            }
        }

        function checkpass($pdo, $id, $pass){
            $sql = 'SELECT * FROM db51 WHERE id=:id ';
            $stmt = $pdo->prepare($sql);                  // ←差し替えるパラメータを含めて記述したSQLを準備し、
            $stmt->bindParam(':id', $id, PDO::PARAM_INT); // ←その差し替えるパラメータの値を指定してから、
            $stmt->execute();                             // ←SQLを実行する。
            $results = $stmt->fetchAll(); 
            foreach ($results as $row){
                $dbpass = $row['pass'];
                if($pass == $dbpass){
                    return TRUE;
                }
            }
            return FALSE;
        }

        if(!empty($_POST['submit'])){
            if(!empty($_POST["comment"]) && !empty($_POST["name"])){
                if(empty($_POST['output_enum'])){
                    if(!empty($_POST['pass'])){
                        $sql = $pdo -> prepare("INSERT INTO db51 (name, comment, dt, pass) VALUES (:name, :comment, :dt, :pass)");
                        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
                        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
                        $sql -> bindParam(':dt', $dt, PDO::PARAM_STR);
                        $sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
                        $name = $_POST["name"];
                        $comment = $_POST["comment"]; 
                        $dt = date("Y/n/j G:i:s");
                        $pass = $_POST["pass"];
                        $sql -> execute();
                    }
                }else{
                    $id = $_POST['output_enum']; //変更する投稿番号
                    $name = $_POST['name'];
                    $comment = $_POST['comment']; 
                    $sql = 'UPDATE db51 SET name=:name,comment=:comment WHERE id=:id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindParam(':name', $name, PDO::PARAM_STR);
                    $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
                    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                    $stmt->execute();
                }
                dbecho($pdo);
            }
        }else if(!empty($_POST['delete'])){
            if(!empty($_POST['delete_num'])){
                if(!empty($_POST["pass"])){
                    $id = $_POST['delete_num'];
                    $pass = $_POST["pass"];
                    if(checkpass($pdo, $id, $pass) == TRUE){
                        $sql = 'delete from db51 where id=:id';
                        $stmt = $pdo->prepare($sql);
                        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
                        $stmt->execute();
                        dbecho($pdo);
                    }
                }
            }
        }
    ?>
    
</body>
</html>
