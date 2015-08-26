  <meta http-equiv="Content-Type" content="text/php; charset=UTF-8" />

<?php
$post = isset($_POST['edit'])? htmlspecialchars($_POST['edit']) : null;
if(!$post){
  $Temp = '<form action="kadai_ouyou.php" method="post">
  名前 :<br />
  <input type="text" name="name" size="30" value=""/><br />
  コメント :<br />
  <input type="text" name="comment" size="30" value="" /><br />
  削除対象番号 :<br />
  <input type="text" name="delete" size="10" value="" /><br />
  編集対象番号 :<br />
  <input type="text" name="edit" size="10" value="" /><br />
  パスワード:<br />
  <input type="password" name="password" size="10" maxlength="7" value="" />
  <input type="submit" value="送信">
  </form>
  ';
  print ($Temp);
}
//ファイル処理
$file_name = '/Users/seikenkiyosu/Dropbox/intern_folder/public_html/homework_project/kadai_keijiban_list.txt';
if( !file_exists($file_name) ){     //ファイル作成
    if(touch( $file_name )){
        chmod($file_name, 0777);
    } 
}

//編集の内容を受け取るための処理
$post=isset($_POST['edit_num'])? $_POST['edit_num'] : null;
if($post){
    $list = file($file_name);
    foreach ($list as $value) {
      $item = explode("<>", $value);
      if($item[0]==intval(htmlspecialchars($_POST['edit_num']) )){
        $item[1]=htmlspecialchars($_POST['name_edit']);
        $item[2]=htmlspecialchars($_POST['comment_edit']);
      }
      // else{echo "else";}
      foreach ($item as $key_item => $value_item) {$current .= $value_item;  if($key_item!=3)$current .= "<>";}
    }
    file_put_contents($file_name, $current);
    //リストの表示に関する処理
    $list = file($file_name);
    foreach ($list as $key => $value) {
      $item = explode("<>", $value);
      foreach ($item as $key_item => $value_item) {
      echo "$value_item ";
      }
    echo "<br />";
    }
}

//何も入力されていないときの処理
$post=isset($_POST['name'])? 1 : null;
if(!htmlspecialchars($_POST['name'])&&!htmlspecialchars($_POST['delete'])&&!htmlspecialchars($_POST['edit'])){
    if(!htmlspecialchars($_POST['edit_num'])){
        $list = file($file_name);
        foreach ($list as $key => $value) {
            $item = explode("<>", $value);
            foreach ($item as $key_item => $value_item) {
                echo "$value_item ";
            }
        echo "<br />";
        }
    }
    exit(1);
}

  //デリートのみ入力
if(!htmlspecialchars($_POST['name'])&&!htmlspecialchars($_POST['comment'])&&htmlspecialchars($_POST['delete'])&&!htmlspecialchars($_POST['edit'])){
  $list = file($file_name);
  foreach ($list as $key => $value) {
   $item = explode("<>", $value);
      if($item[0]!=htmlspecialchars($_POST['delete'])){
        $current .= $value;
        // $current .= "+\n";
      }
  }
  file_put_contents($file_name, $current);
}
//エディットのみ
else if(!htmlspecialchars($_POST['name'])&&!htmlspecialchars($_POST['comment'])&&!htmlspecialchars($_POST['delete'])&&htmlspecialchars($_POST['edit'])){
  $list = file($file_name);
  foreach ($list as $key => $value) {
      $item = explode("<>", $value);
      if($item[0]==htmlspecialchars($_POST['edit'])){
        $Temp = '
                     <form action="kadai_ouyou.php" method="post">
                        編集を行ってください<br />
                        名前 :<br />
                        <input type="text" name="name_edit" size="30" value='.$item[1].' /><br />
                        コメント :<br />
                        <input type="text" name="comment_edit" size="30" value='.$item[2].' /><br />
                        <input type="submit" value="送信">
                        <input type="hidden" name="edit_num" value='.$item[0].'/>
                     </form>
              ';
        print ($Temp);
      }
    }
}
//その他(名前とコメントの処理)
else{
  $list = file($file_name);
  $list = file($file_name);
  $item = explode( "<>", $list[count($list)-1] );
  $current = file_get_contents($file_name);
  $current .= $item[0]+1;
  $current .= "<>";
  $current .= htmlspecialchars($_POST['name']);
  $current .= "<>";
  $current .= htmlspecialchars($_POST['comment']);
  $current .= "<>";
  $current .= date("Y/M/d(D) H:i:s");
  $current .= "\n";
  file_put_contents($file_name, $current);
}

//リストの表示に関する処理
$list = file($file_name);
foreach ($list as $key => $value) {
  $item = explode("<>", $value);
  foreach ($item as $key_item => $value_item) {
    echo "$value_item ";
  }
  echo "<br />";
}
unset($Temp);

?>