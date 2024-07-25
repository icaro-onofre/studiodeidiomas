<?php

//===================================================================================================================================
// TELA: inc/connect.php
// DESCRIÇÃO: Funções para conexão ao banco de dados
// AUTOR: Bruno Souza
//===================================================================================================================================

	session_start();
	$connect_previamente_carregado = true;
	
	//-----------------------------------------------------------------------------------------------
	
	if($_ENV["USER"] == "brunosouza"){
		$dbhost = "localhost";						// <-- mysql server host
		$dbuser = "root";							// <-- mysql db user
		$dbpassword = "root";						// <-- mysql db password
		$dbname = "studio";							// <-- mysql db name
	} else {
		$dbhost = "cpmy0016.servidorwebfacil.com";	// <-- mysql server host
		$dbuser = 'studio_studio';					// <-- mysql db user
		$dbpassword = '1hv8yMLIGa';					// <-- mysql db password
		$dbname = "studio_db";						// <-- mysql db pname
	}

	//-----------------------------------------------------------------------------------------------
	
	function dbConnect(){
	  global $dbhost, $dbname, $dbuser, $dbpassword;
	  $db = mysql_connect($dbhost, $dbuser, $dbpassword) or die("<b>Erro no banco de dados MySQL:</b> Não foi possível a conexão ao banco de dados \"".$dbhost."\"<br/><b>Descrição do erro: ".mysql_error()."</b>");
	  mysql_select_db($dbname) or die("<b>Erro no banco de dados MySQL:</b> Não foi possível selecionar o database \"".$dbname."\"<br/><b>Descrição do erro: ".mysql_error()."</b>");
	  return($db);
	}
	
	//-----------------------------------------------------------------------------------------------
	
	function dbQuery($q) {
	  $r = mysql_query($q) or die("<b>Erro no banco de dados MySQL:</b> Falha na Query: ".str_replace("'NFenaRede'", "#chave_encript#", $q)."<br/><b>Descrição do erro: ".mysql_error()."</b>");
	  return($r);
	}
	
	//-----------------------------------------------------------------------------------------------
	
	function dbFetch($r) {
		return mysql_fetch_assoc($r);
	}

	//-----------------------------------------------------------------------------------------------
	
	function dbLinhas($r) {
		return mysql_num_rows($r);
	}

	//-----------------------------------------------------------------------------------------------
	
	function dbResult($r, $i, $c) {
		return mysql_result($r, $i, $c);
	}

	//-----------------------------------------------------------------------------------------------
	
	function dbDesconnect() {
	  mysql_close();
	}

	//-----------------------------------------------------------------------------------------------
	
	function dbEncrypt($str) {
		return "aes_encrypt('".addslashes($str)."', 'NFenaRede')";
	}
	
	//-----------------------------------------------------------------------------------------------
	
	function dbDecrypt($str) {
		return "aes_decrypt($str, 'NFenaRede') AS $str";
	}
	
	//-----------------------------------------------------------------------------------------------
	
	function dbInsertId() {
		return mysql_insert_id();
	}
	
	//-----------------------------------------------------------------------------------------------
	
	function dbString($str) {
		return "'".mysql_real_escape_string($str)."'";
	}
	
	//-----------------------------------------------------------------------------------------------
	
	function dbError() {
		return mysql_errno();
	}
	
	//-----------------------------------------------------------------------------------------------
	
	function date_str2sql($s){
		$dia = substr($s, 0, 2);
		$mes = substr($s, 3, 2);
		$ano = substr($s, 6, 4);
		return "$ano-$mes-$dia";
	}
	
	//-----------------------------------------------------------------------------------------------
	
	function date_sql2str($s){
		$ano = substr($s, 0, 4);
		$mes = substr($s, 5, 2);
		$dia = substr($s, 8, 2);
		return "$dia/$mes/$ano";
	}
	
	//-----------------------------------------------------------------------------------------------
	
	function double_sql2str($d){
		return str_replace(".", ",", $d);
	}
	
	//-----------------------------------------------------------------------------------------------
	
	function double_str2sql($d){
		return str_replace(",", ".", $d);
	}
	
	//-----------------------------------------------------------------------------------------------
	
	function tiracento($texto){
		$trocarIsso = array('à','á','â','ã','ä','å','ç','è','é','ê','ë','ì','í','î','ï','ñ','ò','ó','ô','õ','ö','ù','ü','ú','ÿ','À','Á','Â','Ã','Ä','Å','Ç','È','É','Ê','Ë','Ì','Í','Î','Ï','Ñ','Ò','Ó','Ô','Õ','Ö','Ù','Ü','Ú','Ÿ','"',);
		$porIsso =    array('a','a','a','a','a','a','c','e','e','e','e','i','i','i','i','n','o','o','o','o','o','u','u','u','y','A','A','A','A','A','A','C','E','E','E','E','I','I','I','I','N','O','O','O','O','O','U','U','U','Y','',);
		$titletext = str_replace($trocarIsso, $porIsso, $texto);
		return $titletext;
	}
	
	//-----------------------------------------------------------------------------------------------
	
	function validaCNPJ($cnpj){
		$cnpj = preg_replace ("@[./-]@", "", $cnpj);
		if (strlen ($cnpj) <> 14 or !is_numeric ($cnpj)){
			return false;
		}
		$j = 5;
		$k = 6;
		$soma1 = "";
		$soma2 = "";
		
		for ($i = 0; $i < 13; $i++){
			$j = $j == 1 ? 9 : $j;
			$k = $k == 1 ? 9 : $k;
			$soma2 += ($cnpj{$i} * $k);
			
			if ($i < 12){
				$soma1 += ($cnpj{$i} * $j);
			}
			$k--;
			$j--;
		}
		
		$digito1 = $soma1 % 11 < 2 ? 0 : 11 - $soma1 % 11;
		$digito2 = $soma2 % 11 < 2 ? 0 : 11 - $soma2 % 11;
		return (($cnpj{12} == $digito1) and ($cnpj{13} == $digito2));
	}
	
	//-----------------------------------------------------------------------------------------------
	
	function mascara($valor, $mascara){
		$mascarado = '';
		$k = 0;
		
		for($i = 0; $i<=strlen($mascara)-1; $i++){
			if($mascara[$i] == '#'){
				if(isset($valor[$k])){
					$mascarado .= $valor[$k++];
				}
			} else {
				if(isset($mascara[$i])){
					$mascarado .= $mascara[$i];
				}
			}
		}
		return $mascarado;
	}
	
	//-----------------------------------------------------------------------------------------------
	
	function cria_zip($arquivos = array(), $destino = '', $overwrite = false){
		
		// Se o zip já existir, testa se é para sobrescrever
		if(file_exists($destino) && !$overwrite){
			return false;
		}
		
		$arquivos_validos = array();
		
		// Testa se os arquivos existem
		if(is_array($arquivos)){
			foreach($arquivos as $arquivo){
				$arquivos_nome = explode("|", $arquivo);
				
				if(file_exists($arquivos_nome[0])){
					$arquivos_validos[] = $arquivo;
				}
			}
		}
		
		// Se houverem arquivos válidos, cria o zip
		if(count($arquivos_validos)){
			
			// Cria o arquivo
			$arquivo_zip = new ZipArchive();
			if($arquivo_zip->open($destino,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
				return false;
			}
			
			// Insere os arquivos no zip
			foreach($arquivos_validos as $arquivo){
				$arquivos_nome = explode("|", $arquivo);
				$arquivo_zip->addFile($arquivos_nome[0],$arquivos_nome[1]);
			}
			
			// Fecha o arquivo zip
			$arquivo_zip->close();
			
			// Retorna se o arquivo foi criado corretamente
			return file_exists($destino);
		} else {
			return false;
		}
	}
	
	//-----------------------------------------------------------------------------------------------
	
	function geraSenha($tamanho = 8, $maiusculas = true, $numeros = true, $simbolos = false){
		$lmin = 'abcdefghijklmnopqrstuvwxyz';
		$lmai = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		$num = '1234567890';
		$simb = '!@#$%*-';
		$retorno = '';
		$caracteres = '';
		
		$caracteres .= $lmin;
		if ($maiusculas) $caracteres .= $lmai;
		if ($numeros) $caracteres .= $num;
		if ($simbolos) $caracteres .= $simb;
		
		$len = strlen($caracteres);
		
		for ($n = 1; $n <= $tamanho; $n++) {
			$rand = mt_rand(1, $len);
			$retorno .= $caracteres[$rand-1];
		}
		
		return $retorno;
	}
	
	//-----------------------------------------------------------------------------------------------
	
	dbConnect();
	register_shutdown_function("dbDesconnect");
?>