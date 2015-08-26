<html>
<head><title>kadai2-5</title></head>
<body>



<?php 
//メイン処理
if($_SERVER["REQUEST_METHOD"] != "POST"){	//何も入力されていなかったら
	PrintForm();
}

if($_SERVER["REQUEST_METHOD"] == "POST"){	
	if (isset($_POST['standard_form'])){	//標準フォームを入力したあとの処理
		if($_POST['edit_num']==null){		//編集対象番号が入力されていない場合標準フォーム出力
			PrintForm();
		}
		else {								//編集対象番号が入力されている場合編集フォーム出力
			PrintFormEdit();
		}
	
		if($_POST["delete_num"]!=null){		//削除対象番号が入力されている場合
			DeleteData();
		}
		else if($_POST["edit_num"]==null){	//編集対象番号に入力がなければ
			WriteData();
		}
	}
	else if(isset($_POST["edit_form"])){//編集フォームを入力した後の処理
		PrintForm();
		EditData();
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
	if( !file_exists($keijiban_file) ){     //ファイル作成
		if(touch( $keijiban_file )){
			chmod($keijiban_file, 0777);
		}
	}
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

//ファイルからデータを編集する関数
function EditData(){
	$keijiban_file = 'keijiban.txt';
	$fp = fopen($keijiban_file, 'rb');
	$list = file($keijiban_file);
	$data = null;
	
	foreach ($list as $value) {
		$item = explode("<>", $value);
		if($item[0]==$_POST['edit_done'] ){
			$item[1]=$_POST['name_edit'];
			$item[2]=$_POST['comment_edit'];
		}
		foreach ($item as $key_item => $value_item) {
			$data .= $value_item;
			if($key_item!=3) {$data .= "<>";}
		}
	}
	file_put_contents($keijiban_file, $data);
	fclose($fp);
}

//標準のフォームを出力する関数
function PrintForm(){
	print  '<form action="kadai2-5.php" method="post">
				  名前 :<br />
				  <input type="text" name="name" size="30" value=""/><br />
				  コメント :<br />
				  <input type="text" name="contents" size="30" value="" /><br />
				  削除対象番号 :<br />
				  <input type="text" name="delete_num" size="10" value="" /><br />
				  編集対象番号 :<br />
				  <input type="text" name="edit_num" size="10" value="" /><br />
				  <input type="submit" value="送信">
				  <input type="hidden" name="standard_form">
			  </form>
			  ';
}

//編集のために使うフォームを出力する関数
function PrintFormEdit(){
	$keijiban_file = 'keijiban.txt';
	$fp = fopen($keijiban_file, 'rb');
	$list = file($keijiban_file);
	
	foreach ($list as $key => $value) {
		$item = explode("<>", $value);
		if($item[0]==$_POST['edit_num']){
			print '<form action="kadai2-5.php" method="post">
                        編集を行ってください<br />
                        名前 :<br />
                        <input type="text" name="name_edit" size="30" value='.$item[1].' /><br />
                        コメント :<br />
                        <input type="text" name="comment_edit" size="30" value='.$item[2].' /><br />
                        <input type="submit" value="送信">
                        <input type="hidden" name="edit_done" value='.$item[0].'>
                        <input type="hidden" name="edit_form">
                   </form>';
		}
	}
	fclose($fp);
}

?>
</body>
</html>