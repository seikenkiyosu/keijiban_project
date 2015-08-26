<?php

$link = mysql_connect('localhost' ,'keijiban', '2357seiken');
if (!$link) {
    die('接続失敗です。'.mysql_error());
}

print('<p>接続に成功しました。</p>');

// MySQLに対する処理
$query = 'create table thread(
		num int,
		name varchar[20],
		comment varchar[100],
		password varchar[10];
		)';
mysql_query($query);
$query = 'show table';
mysql_query($query);

$close_flag = mysql_close($link);

if ($close_flag){
    print('<p>切断に成功しました。</p>');
}

?>
