<?php
/* 
【機能】
書籍テーブルより書籍情報を取得し、画面に表示する。
商品をチェックし、ボタンを押すことで入荷、出荷が行える。
ログアウトボタン押下時に、セッション情報を削除しログイン画面に遷移する。

【エラー一覧（エラー表示：発生条件）】
入荷する商品が選択されていません：商品が一つも選択されていない状態で入荷ボタンを押す
出荷する商品が選択されていません：商品が一つも選択されていない状態で出荷ボタンを押す
*/

//①セッションを開始する
session_start();
session_regenerate_id();

//②SESSIONの「login」フラグがfalseか判定する。「login」フラグがfalseの場合はif文の中に入る。
if (($_SESSION['login'])!= true)
{
	//③SESSIONの「error2」に「ログインしてください」と設定する。
	$_SESSION['error2']="ログインしてください";
	//④ログイン画面へ遷移する。
	header('Location:login.php');
	exit();
}

//⑤データベースへ接続し、接続情報を変数に保存する
$con  = new mysqli('localhost', 'yse', '2021', 'yse');

//⑥データベースで使用する文字コードを「UTF8」にする
$mysqli->set_charset("utf-8");

//⑦書籍テーブルから書籍情報を取得するSQLを実行する。また実行結果を変数に保存する
$sql = "SELECT * FROM books";
$bookdate = $mysqli->query($sql);

//$phpsbooks="SELECT * FROM books WHERE deleteflg=0";
//$row = $pdo->query($phpsbooks)->fetch(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title>書籍一覧</title>
	<link rel="stylesheet" href="css/ichiran.css" type="text/css" />
</head>
<body>
	<div id="header">
		<h1>書籍一覧</h1>
	</div>
	<form action="zaiko_ichiran.php" method="post" id="myform" name="myform">
		<div id="pagebody">
			<!-- エラーメッセージ表示 -->
			<div id="error">
				<?php
				/*
				 * ⑧SESSIONの「success」にメッセージが設定されているかを判定する。
				 * 設定されていた場合はif文の中に入る。
				 */ 
				if(isset($_SESSION['success']))
					{
					//⑨SESSIONの「success」の中身を表示する。
					$success = $_SESSION['success'];
					echo $success;
					}
				?>
			</div>
			
			<!-- 左メニュー -->
			<div id="left">
				<p id="ninsyou_ippan">
					<?php
						echo @$_SESSION["account_name"];
					?><br>
					<button type="button" id="logout" onclick="location.href='logout.php'">ログアウト</button>
				</p>
				<button type="submit" id="btn1" formmethod="POST" name="decision" value="3" formaction="nyuka.php">入荷</button>

				<button type="submit" id="btn1" formmethod="POST" name="decision" value="4" formaction="syukka.php">出荷</button>
			</div>
			<!-- 中央表示 -->
			<div id="center">

				<!-- 書籍一覧の表示 -->
				<table>
					<thead>
						<tr>
							<th id="check"></th>
							<th id="id">ID</th>
							<th id="book_name">書籍名</th>
							<th id="author">著者名</th>
							<th id="salesDate">発売日</th>
							<th id="itemPrice">金額</th>
							<th id="stock">在庫数</th>
						</tr>
					</thead>
					<tbody>
						<?php
						//⑩SQLの実行結果の変数から1レコードのデータを取り出す。レコードがない場合はループを終了する。
						while($row = $query->fetch(PDO::FETCH_ASSOC))
						{
							//⑪extract変数を使用し、1レコードのデータを渡す。
							extract($row);
							echo "<tr id='book'>";
							echo "<td id='check'><input type='checkbox' name='books[]'value=".$id."></td>";//12
							echo "<td id='id'>$id</td>";//13
							echo "<td id='title'>$title</td>";//14
							echo "<td id='author'>$author</td>";//15
							echo "<td id='date'>$salesDate</td>";//16
							echo "<td id='price'>$price</td>";//17
							echo "<td id='stock'>$stock</td>";//18

							echo "</tr>";
						}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</form>
	<div id="footer">
		<footer>株式会社アクロイト</footer>
	</div>
</body>
</html>
