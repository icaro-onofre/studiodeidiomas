<?php
	
	if(!$connect_previamente_carregado){
		include("inc/connect.php");
	}
	
	if(!$_SESSION["uid"]){
		header("Location: login.php");
	}
	
	$aid = $_GET["aid"];
	$uid = $_SESSION["uid"];
	
	if($uid){
		$rows = dbQuery("SELECT * FROM arquivos WHERE aid = ".dbString($aid).";");
		$linhas = dbLinhas($rows);
		
		if($linhas){
			$r = dbFetch($rows);
			$nome = $r["arquivo"];
			$arquivo = "aid/".$nome;
			
			if(is_file($arquivo)){
				$fp = fopen($arquivo, "rb");
				$fn = $nome;
				header("Content-Type:application/octet-stream");
				header("Content-Disposition:attachement; filename=$fn");
				header("Content-Transfer-Encoding:binary");
				
				// Descarrega o arquivo no buffer para download
				fpassthru($fp);
			} else {
				header("Location: erro.php");
			}
		} else {
			header("Location: erro.php");
		}
	} else {
		header("Location: erro.php");
	}
	
?>
