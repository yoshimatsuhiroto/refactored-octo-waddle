<html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<title>misson_2-6</title>

</head>

<body>

<h1>入力フォーム</h1>
<form action="mission_2-6.php" method="post">
名前：<br />
<input type="text" name="name" size="30" value="" /><br />
コメント：<br />
<textarea name="comment" cols="30" rows="5"></textarea><br />
パスワード：<br />
<textarea name="password" cols="30" rows="1"></textarea><br />
<br />
<input type="submit" value="登録する" />

</form>

<h1>削除/変更</h1>

<form action="mission_2-6.php" method="post">

削除と編集は同時に行わないでください<br/>

<br/>

削除したい番号を入力してください<br /> 
  
<input type="text" name="delete_num" size="30" value="" /><br />

<br/>

編集したい番号を入力してください<br />

<input type="text" name="edit_num" size="30" value="" /><br />

名前：<br />

<input type="text" name="edit_name" size="30" value="" /><br />

コメント：<br />

<textarea name="edit_comment" cols="30" rows="5"></textarea><br />

<br />

パスワードを入力してください<br />

<textarea name="repassword" cols="30" rows="1"></textarea><br /><!--password2にパスワード確認時に入力したパスワードを格納-->

<br/>

<input type="submit" value="削除/編集" />

</form>

<?php

$origin = "origin.txt";//元本のファイル

if(!empty($_POST["name"]) or !empty($_POST["comment"]) or !empty($_POST["password"])){//入力フォームに何か入っていた場合

$fp = fopen($origin, 'a');//新しく書き込まれる内容が増えるごとに追記していく

$count=count(file($origin));//$originに格納されている行数をカウントする

$count=$count+1;//1つ目の登録番号を1にするため

fwrite($fp, "{".$count."}<>");//$countに追加した回数+1を格納し、格納された順に１，２、３、、と番号が付く

fwrite($fp, "{".$_POST["name"]."}<>");

fwrite($fp,"{".$_POST["comment"]."}<>");

fwrite($fp, "{".date("Y年m月d日 h時i分")."}<>");

fwrite($fp, "{".$_POST["password"]."}<>");

fwrite($fp, "\n");

fclose($fp);

echo("登録されました<br/>");

}//新規入力フォームの終わり

$edit= "edit.txt";//$editに編集用ファイルを格納

$ret_array  = file($origin);//origin.txtのファイル内を配列に入れる

$delete_num=NULL;

$edit_num=NULL;

$rowcount=0;//削除後の行数をカウントするもの

if(!empty($_POST["delete_num"]) or !empty($_POST["edit_num"]) or !empty($_POST["edit_name"]) or !empty($_POST["edit_comment"]) or !empty($_POST["repassword"])){//削除編集フォームに何か書き込まれた場合
  
echo("delete_num,edit_numの中身確認<br/>");

echo("delete_num=".$_POST["delete_num"]);

echo("<br/>");

echo("edit_num=".$_POST["edit_num"]);

echo("<br/>");

if(!(empty($_POST["delete_num"]))){//delete_numに数字が入っていれば$delete_numに格納 

/*	echo("delete_numに格納<br/>");*/

	$delete_num=$_POST["delete_num"];//$delete_numの中に削除したい番号を入れる

}

else if(!(empty($_POST["edit_num"]))){//edit_numに数字が入っていれば$edit_numに格納

/*	echo("edit_numに格納<br/>");*/

	$edit_num=$_POST["edit_num"];//$edit_numの中に編集したい番号を入れる

}
/*$pieces1に1次元配列でorigin.txtを格納、$piecesに2次元配列でorigin.txtを格納*/
for($i=0;$i<count($ret_array);++$i){

	$pieces1=explode("<>",$ret_array[$i]);

		for($j=0;$j<5;++$j){
	
			$pieces[$i][$j]=$pieces1[$j];//$piecesは2次元配列

		}

}

$repassword=$_POST["repassword"];//パスワードの確認

echo("入力されたパスワード:".$repassword."<br/>");

/*
echo("delete_num:".$delete_num);

echo("<br/>");

echo("edit_num:".$edit_num);//$edit_num,$delete_numの中身を表示

echo("<br/>");
*/

if(!(empty($delete_num))){}

else if(!(empty($edit_num))){}

else{echo("削除番号または編集番号を選択してください<br/>");}

//削除の場合

if(!(empty($delete_num))){//削除の番号だけ指定されたとき

	echo("削除をします<br/>");

	$delete_num=$delete_num-1;//登録番号と配列上の番号を合わせる

	echo("{".$repassword."}  ".$pieces[$delete_num][4]."<br/>");

	if("{".$repassword."}"== $pieces[$delete_num][4]){//$pieces[$delete_num][4]には指定した番号のパスワードが入っている

	$delete_num=$delete_num+1;//登録番号と配列上の番号を合わせる

		for($i=0;$i<count($ret_array);++$i){//元々の行数から１引いた回数繰り返したら終了

			if($pieces[$i][0]!="{".$delete_num."}"){

				$storage[$i]=$ret_array[$i];//storageは保存用の配列

			}

		}

		$rowcount=count($ret_array)-1;//保存する行数をカウント	

/*登録番号を繰り上げる */
		for($i=0;$i<count($ret_array)-1;++$i){
			
			if(empty($storage[$i])){
				
				echo($i."番号の繰り上げ<br/>");

				for($j=$i;$j<count($ret_array)-1;++$j){
					$m=$j+1;//$mは繰り上げのための変数
					$storage[$j]=$storage[$m];//空になっている部分は繰り上げ
					$i=$j;
				}

			}

		}

		$pieces = array();//$piecesを空にする

//削除後登録番号を更新する 

		for($i=0;$i<$rowcount;++$i){

			$r=$i+1;//$rは登録番号になる登録registrationのr

			$pieces=explode("<>",$storage[$i]);

			$storage1[$i][0]=("{".$r."}");//保存用配列の登録番号の部分には配列で何番目の行なのかを$i+1にするべきか

			for($j=1;$j<5;++$j){//登録番号以外を入れたいのでjは１から

				$storage1[$i][$j]=($pieces[$j]);//保存用配列の名前などの部分にはそれまでの情報を

			}

			$storage[$i]=$storage1[$i][0]."<>".$storage1[$i][1]."<>".$storage1[$i][2]."<>".$storage1[$i][3]."<>".$storage1[$i][4]."<>";//元の形式にもどす

		}

		$fp=fopen($origin,'w');//origin.txtを書き込み用で開く

		for($i=0;$i<$rowcount;++$i){

			fwrite($fp,$storage[$i]);//$storageに保存した内容をテキストファイルに保存

			fwrite($fp,"\n");

		}

		fclose($fp);
		
	}
	
	else{

		echo("パスワードが正しくありません<br/>");

	}
}

//編集の場合

else if(!(empty($edit_num))){//削除の番号だけ指定されたとき

	echo("編集をします<br/>");

	$edit_num=$edit_num-1;//登録用番号と配列番号を合わせる

	if("{".$repassword."}" == $pieces[$edit_num][4]){//$pieces2[$num2][1]には指定した番号のパスワードが入っている

		$edit_num=$edit_num+1;//登録用番号と配列番号を合わせる

		echo($_POST["edit_num"].":"."編集対象番号<br/>");

		if($edit_num==flase){echo("編集無し<br/>");}

		else{echo($edit_num."を編集<br/>");}

		$fp=fopen($edit,'w');//編集用ファイルを作り格納

		fwrite($fp,"{".$edit_num."}<>");

		fwrite($fp,"{".$_POST["edit_name"]."}<>");

		fwrite($fp,"{".$_POST["edit_comment"]."}<>");

		fwrite($fp,"{".date("Y年m月d日 h時i分")."}<>");

		fwrite($fp,"{".$_POST["repassword"]."}<>");

		fwrite($fp,"\n");

		fclose($fp);

		$edit_array = file($edit);//edit_arrayに編集用ファイルである$editを格納する

		for($i=0;$i<count($ret_array);++$i){

			$pieces=explode("<>",$ret_array[$i]);
	
			if($pieces[0]!="{".$edit_num."}"){//登録番号と編集対象番号が一致したときは表示しない

				$storage[$i]=$ret_array[$i];//storageは保存用の配列
	
			}

			else if($pieces[0]=="{".$edit_num."}"){//登録番号と編集番号が一致するかどうか

				$storage[$i]=$edit_array[0];//編集の対象となっているi番目に保存しておいた編集後内容を格納する
			}
 	
			else{

			}
	
		}

		$fp=fopen($origin,'w');//origin.txtを書き込み用で開く

		for($g=0;$g<count($storage);++$g){

			fwrite($fp,$storage[$g]);//$storageに保存した内容をテキストファイルに保存

		}

		fclose($fp);

	}

	else{

		echo("パスワードが正しくありません<br/>");

	}

}

else if(empty($edit_num) and empty($delete_num)){//最初の状態

}

else{

echo("削除か編集どちらか一方にしてください<br/>");

}

$storage = array();//$storageの中身を空にする

$pieces = array();//$piecesの中身を空にする

}

echo("現在の名簿の中身<br/>");

$ret_array  = file($origin);//origin.txtのファイル内を配列に入れる

/*ファイルの中身を表示*/
for($i=0;$i<count($ret_array);++$i){

	$pieces1=explode("<>",$ret_array[$i]);

		for($j=0;$j<5;++$j){
	
			$pieces[$i][$j]=$pieces1[$j];//$piecesは2次元配列

			echo($pieces[$i][$j]);//削除/編集前の中身を表示

		}

		echo("<br/>");	

}

?>

</body>

</html>
 