<html>
<head><title>kadai2-4</title></head>
<body>

<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
 名前<br>
<input type="text" name="name"><br>
 コメント<br>
<input type="text" name="contents"><br>
<!-- <textarea name="contents" rows="8" cols="40"> -->
<!-- </textarea><br> -->
 削除対象番号<br>
<input type="text" name="delete_num" size="10"><br>
<input type="submit" name="submit" value="投稿する">
</form>

<?php 
//メイン処理
if($_SERVER["REQUEST_METHOD"] == "POST"){	//何の処理もしてないときのための処理
	if($_POST["delete_num"]!=null){	//削除対象番号が入力されている場合
		DeleteData();
	}
	else {
		WriteData();
	}
}
ReadData();

//ファイルに掲示板のデータを書き込む関数
function WriteData(){
	$keijiban_file = 'keijiban.txt';
	if( !file_exists($keijiban_file) ){     //ファイル作成
		if(touch( $keijiban_file )){
			chmod($keijiban_file, 0777);
		}
	}
	$fp = fopen($keijiban_file, 'ab');
	$list = file($keijiban_file);
	
	$num = count($list)+1;
	$name = $_POST['name'];
	$contents = $_POST['contents'];
	$time = date("Y/M/d(D) H:i:s");
	
	$data = $num."<>".$name."<>".$contents."<>".$time."\n";

    if ($fp){
        if (flock($fp, LOCK_EX)){
            if (fwrite($fp,  $data) === FALSE){
                print('ファイル書き込みに失敗しました');
            }
            flock($fp, LOCK_UN);
        }
        else{
            print('ファイルロックに失敗しました');
        }
    }
    fclose($fp);
    
}

//ファイルを読み込むための関数
function ReadData(){
	$keijiban_file = 'keijiban.txt';
	$fp = fopen($keijiban_file, 'rb');
	$list = file($keijiban_file);
	
	foreach ($list as $key => $value) {
		$item = explode("<>", $value);
		echo "<hr>";
		foreach ($item as $key_item => $value_item) {
		print "<p>".$value_item."</p>";
	}
	echo "<br>";
	}
	fclose($fp);
}

//ファイルからデータを削除する関数
function DeleteData(){
	$keijiban_file = 'keijiban.txt';
	$fp = fopen($keijiban_file, 'rb');
	$list = file($keijiban_file);
	$data = null;
	foreach ($list as $value) {
		$item = explode("<>", $value);
		if($item[0]!=$_POST['delete_num']){
			$data .= $value;
		}
	}
	file_put_contents($keijiban_file, $data);
	fclose($fp);
}

?>
</body>
</html>