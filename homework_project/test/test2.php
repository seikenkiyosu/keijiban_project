<html>
<head><title>PHP TEST</title></head>
<body>

<p>掲示板</p>

<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
<input type="text" name="personal_name"><br><br>
<textarea name="contents" rows="8" cols="40">
</textarea><br><br>
<input type="submit" name="btn1" value="投稿する">
</form>

<?php

$personal_name = isset($_POST['personal_name'])? $_POST['personal_name'] : null;
$contents = isset($_POST['contents'])? $_POST['contents'] : null;
$contents = nl2br($contents);

$data = "<hr>\r\n";
$data = $data."<p>投稿者:".$personal_name."</p>\r\n";
$data = $data."<p>内容:</p>\r\n";
$data = $data."<p>".$contents."</p>\r\n";

$keijban_file = 'keijiban.txt';

$fp = fopen($keijban_file, 'ab');

if ($fp){
    if (flock($fp, LOCK_EX)){
        if (fwrite($fp,  $data) === FALSE){
            print('ファイル書き込みに失敗しました');
        }

        flock($fp, LOCK_UN);
    }else{
        print('ファイルロックに失敗しました');
    }
}

fclose($fp);

?>
</body>
</html>