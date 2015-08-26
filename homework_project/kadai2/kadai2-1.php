<html>
<head><title>kadai2-1</title></head>
<body>

<form method="POST" action="<?php print($_SERVER['PHP_SELF']) ?>">
 名前<br>
<input type="text" name="name"><br>
 コメント<br>
<textarea name="contents" rows="8" cols="40">
</textarea><br>
<input type="submit" name="submit" value="投稿する">
</form>


</body>
</html>