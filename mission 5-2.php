
<!DOCTYPE html>
<html lang="ja">
<head>
 　<meta charset="utf-8">
</head>


	<?php

	//データベースの接続
	// ・データベース名：
	// ・ユーザー名：
	// ・パスワード：
	
	$dsn = 'データベース名';
	$user = 'ユーザー名';
	$password = 'パスワード';
	$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
	


	//4-2 データベース内にテーブルを作成する。テーブル作成の際にはcreateコマンドを使う。
	$sql = "CREATE TABLE IF NOT EXISTS tb3"
	." ("
	. "num INT AUTO_INCREMENT PRIMARY KEY,"
	. "name char(32),"
	. "comment TEXT,"
	. "date TEXT,"
	. "pass TEXT"
	.");";
	$stmt = $pdo->query($sql);
	

/*	//4-3 テーブル一覧を表示するコマンドを使って作成が出来たか確認する。
	$sql ='SHOW TABLES';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[0];
		echo '<br>';
	}
	echo "<hr>";

	//4-4 テーブルの中身を確認するコマンドを使って、意図した内容のテーブルが作成されているか確認する。
	$sql ='SHOW CREATE TABLE tb3';
	$result = $pdo -> query($sql);
	foreach ($result as $row){
		echo $row[1];
	}
	echo "<hr>";  */




	//投稿フォームにデータがあるときの処理
	if(!empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass"])){

		//編集の場合($_POST["editnum2"]が空じゃない場合)
		//UPDATEで編集…フォームから情報を受け取る！
		//4-7 
		if(!empty($_POST["editnum2"])){

		$num = $_POST["editnum2"]; //変更する投稿番号
		$name =  $_POST["name"];
		$comment =  $_POST["comment"]; 
		$sql = 'update tb3 set name=:name,comment=:comment where num=:num';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':name', $name, PDO::PARAM_STR);
		$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
		$stmt->bindParam(':num', $num, PDO::PARAM_INT);
		
		$stmt->execute();


		//新規投稿の場合
		}else{
		//0番号 num
		//1名前 name
		//2コメント comment
		//3年月日時間 date
		//4パスワード pass
	
		//INSERTで新規入力…フォームから情報を受け取る！
		//4-5
		$name = $_POST["name"];
		$comment = $_POST["comment"];
		$date =  date("Y/m/d H:i:s");
		$pass = $_POST["pass"];

		$sql = $pdo -> prepare("INSERT INTO tb3 (name, comment, date, pass) VALUES (:name, :comment, :date, :pass)");
		$sql -> bindParam(':name', $name, PDO::PARAM_STR);
		$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$sql -> bindParam(':date', $date, PDO::PARAM_STR);
		$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
	 
		$sql -> execute();
				
			}

	//削除フォームに入力がある時
	//DELETEで削除…フォームから削除番号を受け取る！
	//SELECTで編集する投稿番号の投稿からパスワードを呼び出す
	//パスワードが合っている時のみ関数に代入する
	//4-8
	}elseif(!empty($_POST["delnum"])&& !empty($_POST["delpass"])){

	$delnum = $_POST["delnum"];
	$sql = 'SELECT * FROM tb3 WHERE num=:delnum';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':delnum',$delnum, PDO::PARAM_INT);
	$stmt->execute();
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		 
		if($_POST["delpass"]==$row['pass']){
		
		$num = $_POST["delnum"];
		$sql = 'delete from tb3 where num=:num';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':num', $num, PDO::PARAM_INT);
		$stmt->execute();

		}else{ 
		echo "パスワードが違います"."<br>";	
		}
	}

		

	//編集フォームに入力がある時の処理
	}elseif(!empty($_POST["editnum"])){
	
	//SELECTで編集する投稿番号の投稿からパスワードを呼び出す
	//パスワードが合っている時のみ関数に代入する

	$editnum = $_POST["editnum"];
	$sql = 'SELECT * FROM tb3 WHERE num=:editnum';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':editnum',$editnum, PDO::PARAM_INT);
	$stmt->execute();
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		 
	if($_POST["editpass"]==$row['pass']){
			$editnum2 = $row['num'];
			$editname = $row['name'];
			$editcomment = $row['comment'];
			$editpass2 = $row['pass'];
	}else{ 		
	echo "パスワードが違います"."<br>";
	}
		}


	//フォームが全て入力されていない時の処理
	}else{
	echo "***すべて入力してね***"."<br>";
	}

	?>


	フォームに必要事項を記入してください<br>
	<form action="mission 5-1-7.php" method="post">

	 <dl>
 	<input type="hidden" name="editnum2" size=30 value="<?php 
							if(empty($editnum2)){
							echo  "";
							}else{
							echo $editnum2;
							}
							?>" />
	  ○
	  <input type="text" name="name" size=30 value="<?php 
							if(empty($editname)){
							echo  "";
							}else{
							echo $editname;
							}
							?>" placeholder="名前" /><br>
	  ○
	  <input type="text" name="comment" cols="40" rows="7" value="<?php
							if(empty($editcomment)){
							echo  "";
							}else{
							echo $editcomment;
							}
							?>" placeholder="コメント"/><br>
	  ○
	  <input type="password" name="pass" size=30 value="<?php
							if(empty($editpass2)){
							echo  "";
							}else{
							echo $editpass2;
							}
							?>" placeholder="パスワード" />
	  <input type="submit" size=30 /><br><br>

	  削除<br>
	  <input type="text" name="delnum" size=30 value="" placeholder="投稿番号" /><br>
	  <input type="password" name="delpass" size=30 value="" placeholder="投稿時のパスワード" />
	  <input type="submit" size=30 /><br><br>

	  編集<br>
	  <input type="text" name="editnum" size=30 value="" placeholder="投稿番号"/><br>
	  <input type="password" name="editpass" size=30 value="" placeholder="投稿時のパスワード" />
	  <input type="submit" size=30 /><br><br>

	
		<?php
		//SELECTでブラウザ上にテーブルの一部を表示
		//4-6 
		$sql = 'SELECT * FROM tb3';
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach ($results as $row){
			//$rowの中にはテーブルのカラム名が入る
			echo $row['num'].',';
			echo $row['name'].',';
			echo $row['comment'].',';
			echo $row['date'].'<br>';
		echo "<hr>";
		}

						?>
	 </dl>
	</form>



</html>


