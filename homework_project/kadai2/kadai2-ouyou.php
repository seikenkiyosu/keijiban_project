<html>
<head>
<title>kadai2-ouyou</title>
</head>
<body>



<?php
// メイン処理
if ($_SERVER ["REQUEST_METHOD"] != "POST") { // 何も入力されていなかったら
	PrintForm ();
}

if ($_SERVER ["REQUEST_METHOD"] == "POST") {
	if (isset ( $_POST ['standard_form'] )) { // 標準フォームを入力したあとの処理
		if ($_POST ['edit_num'] == null) { // 編集対象番号が入力されていない場合標準フォーム出力
			PrintForm ();
		} else { // 編集対象番号が入力されている場合編集フォーム出力
			PrintFormEdit ();
		}
		
		if ($_POST ["delete_num"] != null) { // 削除対象番号が入力されている場合
			DeleteData ();
		} else if ($_POST ["edit_num"] == null) { // 編集対象番号に入力がなければ
			if ($_POST ["password"] != null) { // パスワードが入力されていない場合
				WriteData ();
			} else { // パスワードが入力されていない場合
				print "パスワードが入力されていません。";
			}
		}
	} else if (isset ( $_POST ["edit_form"] )) { // 編集フォームを入力した後の処理
		PrintForm ();
		EditData ();
	}
}
ReadData ();

// データベースに掲示板のデータを書き込む関数
function WriteData() {

	$link = mysql_connect ( 'localhost', 'homework', '2357seiken' );
	if (! $link) {
		die ( '接続失敗です。' . mysql_error() );
	}
	$db_selected = mysql_select_db ( 'keijiban', $link );
	if (! $db_selected) {
		die ( 'データベース選択失敗です。' . mysql_error() );
	}

	$max_num_query = mysql_query("SELECT MAX(num) as max FROM toukou");
	if(!$max_num_query){
		die('SELECTクエリーが失敗しました。'.mysql_error());
	}
	$temp = mysql_fetch_assoc($max_num_query);
	$max_num = $temp['max'];
	if(!$max_num){
		$max_num = 0;
	}
	
	$num = $max_num+1;
	$name = $_POST ['name'];
	$comment = $_POST ['comment'];
	$time = date ( "Y/M/d(D) H:i:s" );
	$pass = $_POST ['password'];
	
	$insert = mysql_query("INSERT INTO toukou(num,name,comment,time,password) VALUES($num,'$name','$comment','$time','$pass')");
	if (!$insert) {
		die('INSERTクエリーが失敗しました。'.mysql_error());
	}
	$close_flag = mysql_close($link);
	
	if (!$close_flag){
		print('<p>切断に失敗しました。</p>');
	}
}

// データベースを読み込むための関数
function ReadData() {
	$link = mysql_connect ( 'localhost', 'homework', '2357seiken' );
	if (! $link) {
		die ( '接続失敗です。' . mysql_error() );
	}
	// print('<p>接続に成功しました。</p>');
	$db_selected = mysql_select_db ( 'keijiban', $link );
	if (! $db_selected) {
		die ( 'データベース選択失敗です。' . mysql_error() );
	}
	// print('<p>keijibanデータベースを選択しました。</p>');
	
	$result = mysql_query("SELECT * FROM toukou");
	if(!$result){
		die('SELECTクエリーが失敗しました。'.mysql_error());
	}
	
	while ($toukou = mysql_fetch_array($result)) {
		echo "<hr>";
		echo '<p>'.$toukou['num'].'</p>'.
			'<p>'.$toukou['name'].'</p>'.
			'<p>'.$toukou['comment'].'</p>'.
			'<p>'.$toukou['time'].'</p>'."\n";
	}
	
	$close_flag = mysql_close($link);
	if (!$close_flag){
		print('<p>切断に失敗しました。</p>');
	}
}

// データベースからデータを削除する関数
function DeleteData() {
	
	$link = mysql_connect ( 'localhost', 'homework', '2357seiken' );
	if (! $link) {
		die ( '接続失敗です。' . mysql_error() );
	}
	// print('<p>接続に成功しました。</p>');
	$db_selected = mysql_select_db ( 'keijiban', $link );
	if (! $db_selected) {
		die ( 'データベース選択失敗です。' . mysql_error() );
	}
	//パスワード認証のための処理
	$sql = sprintf("SELECT * FROM toukou WHERE num=%d", $_POST['delete_num']);
	$result = mysql_query($sql);
	if(!$result){
		die('SELECTクエリーが失敗しました。'.mysql_error());
	}
	$del = mysql_fetch_assoc($result);
	$password = $del['password'];
		
	if($_POST['password']==$password){
		// print('<p>keijibanデータベースを選択しました。</p>');
		$sql = sprintf("DELETE FROM toukou WHERE num=%d", $_POST['delete_num']);
		$result = mysql_query($sql);
		if(!$result){
			die('DELETEクエリーが失敗しました。'.mysql_error());
		}
	}
	else echo "パスワードが正しくありません。<br>";
	
	$close_flag = mysql_close($link);
	if (!$close_flag){
		print('<p>切断に失敗しました。</p>');
	}
}

// ファイルからデータを編集する関数
function EditData() {
	$link = mysql_connect ( 'localhost', 'homework', '2357seiken' );
	if (! $link) {
		die ( '接続失敗です。' . mysql_error() );
	}
	// print('<p>接続に成功しました。</p>');
	$db_selected = mysql_select_db ( 'keijiban', $link );
	if (! $db_selected) {
		die ( 'データベース選択失敗です。' . mysql_error() );
	}
	$sql = sprintf("UPDATE toukou SET name='%s',comment='%s' WHERE num=%d", $_POST['name_edit'], $_POST['comment_edit'], $_POST['edit_done']);
	$result = mysql_query($sql);
	if(!$result){
		die('EDITクエリーが失敗しました。'.mysql_error());
	}
	$close_flag = mysql_close($link);
	if (!$close_flag){
		print('<p>切断に失敗しました。</p>');
	}
}

// 標準のフォームを出力する関数
function PrintForm() {
	print '<form action="kadai2-ouyou.php" method="post">
				  名前 :<br />
				  <input type="text" name="name" size="30" value=""/><br />
				  コメント :<br />
				  <input type="text" name="comment" size="30" value="" /><br />
				  削除対象番号 :<br />
				  <input type="text" name="delete_num" size="10" value="" /><br />
				  編集対象番号 :<br />
				  <input type="text" name="edit_num" size="10" value="" /><br />
			  	  パスワード:<br />
 				  <input type="password" name="password" size="10" maxlength="7" value="" />
				  <input type="submit" value="送信">
				  <input type="hidden" name="standard_form">
			  </form>
			  ';
}

// 編集のために使うフォームを出力する関数
function PrintFormEdit() {
	$link = mysql_connect ( 'localhost', 'homework', '2357seiken' );
	if (! $link) {
		die ( '接続失敗です。' . mysql_error() );
	}
	// print('<p>接続に成功しました。</p>');
	$db_selected = mysql_select_db ( 'keijiban', $link );
	if (! $db_selected) {
		die ( 'データベース選択失敗です。' . mysql_error() );
	}
	
	//パスワード認証のための処理
	$sql = sprintf("SELECT * FROM toukou WHERE num=%d", $_POST['edit_num']);
	$result = mysql_query($sql);
	if(!$result){
		die('SELECTクエリーが失敗しました。'.mysql_error());
	}
	$edit = mysql_fetch_assoc($result);
	$password = $edit['password'];
	if($_POST['password']==$password){
			print '<form action="kadai2-ouyou.php" method="post">
                        編集を行ってください<br />
                        名前 :<br />
                        <input type="text" name="name_edit" size="30" value=' . $edit['name'] . ' /><br />
                        コメント :<br />
                        <input type="text" name="comment_edit" size="30" value=' . $edit['comment'] . ' /><br />
                        <input type="submit" value="送信">
                        <input type="hidden" name="edit_done" value=' . $_POST['edit_num'] . '>
                        <input type="hidden" name="edit_form">
                   </form>';
	}
	else {
		echo "パスワードが正しくありません。<br>";
		PrintForm();
	}
	
	$close_flag = mysql_close($link);
	if (!$close_flag){
		print('<p>切断に失敗しました。</p>');
	}
}

?>
</body>
</html>
