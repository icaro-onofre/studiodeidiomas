<?php
	
	if(!$connect_previamente_carregado){
		include("inc/connect.php");
	}
	
	if($_SESSION["uid"] != 1){
		header("Location: login.php");
	}
	
	if($_POST){
		if (!empty($_FILES['arquivo']['tmp_name']) and is_file($_FILES['arquivo']['tmp_name'])) {
			
			$desc = $_POST['descricao'];
			$nome = $_FILES['arquivo']['name'];
			$caminho_arquivo = "aid/".$nome;
			
			if(move_uploaded_file($_FILES['arquivo']['tmp_name'], $caminho_arquivo)){
				dbQuery("INSERT INTO arquivos (descricao, arquivo, status, hora) VALUES (".dbString($desc).", ".dbString($nome).", 1, NOW());");
			} else {
				header("Location: erro.php");
			}
			
		} else {
			header("Location: erro.php");
		}
	}
	
	if($_GET["a"] == "elimina"){
		$aid = $_GET["aid"];
		
		$rows = dbQuery("SELECT * FROM arquivos WHERE aid = ".dbString($aid).";");
		$linhas = dbLinhas($rows);
		
		if($linhas){
			$r = dbFetch($rows);
			$nome = $r["arquivo"];
			$arquivo = "aid/".$nome;
			
			unlink($arquivo);
			dbQuery("DELETE FROM arquivos WHERE aid = ".dbString($aid).";");
			header("Location: aid_upload.php");
			
		} else {
			header("Location: erro.php");
		}
	}
?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js ie6 oldie" lang="pt-br"> <![endif]-->
<!--[if IE 7]>    <html class="no-js ie7 oldie" lang="pt-br"> <![endif]-->
<!--[if IE 8]>    <html class="no-js ie8 oldie" lang="pt-br"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="pt-br">  <!--<![endif]-->

<head>
	<meta charset="utf-8">
	<title>Studio de Idiomas</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<meta name="description" content="Há 18 anos na cidade de Campo Limpo Paulista, a escola Studio de Idiomas oferece cursos de Inglês, Alemão e Português para estrangeiros. Os cursos são voltados para crianças a partir de 8 anos, jovens e adultos."/>
	<meta name="keywords" content="escola, idioma, alemão, inglês, português"/>
	<meta name="author" content="Jokernet"/>
	<link rel="shortcut icon" href="favicon.ico" />
	<link href="css/reset.css" rel="stylesheet" type="text/css"/>
	<link href="css/style.css" rel="stylesheet" type="text/css"/>  
	<link href="css/font-awesome.css" rel="stylesheet" type="text/css"/>
	<link rel="stylesheet" type="text/css" href="css/jquery.lightbox-0.5.css"/>  
	<link href="css/media.css" rel="stylesheet" type="text/css"/>   
	<link href='http://fonts.googleapis.com/css?family=Oswald:400,700,300' rel='stylesheet' type='text/css'>    
	<link href='http://fonts.googleapis.com/css?family=Cabin:400,500' rel='stylesheet' type='text/css'>  
	<script type="text/javascript" src="js/jquery.min.js"></script>
	<script type="text/javascript" src="js/modernizr-2.6.2.min.js"></script>
	<script src="js/jquery.easing-1.3.pack.js"></script>    
</head>
<body>
	<div id="mask"></div>
	<table class="loader">
		<tr>
			<td><img src="images/loading.gif" alt="Carregando.." /></td>
		</tr>
	</table>
	
	<header class="navigation">
		<div class="container">
			<div class="logo"><a href="index.php"><img src="images/logo.png" alt="logo" /></a></div>
			<ul class="nav">
				<li><a href="index.php" class="icon-home"><span>Home</span></a></li>
				<li><a href="logout.php" class="icon-off"><span>Sair</span></a> </li>
			</ul>
		</div>
	</header>
	
	<div id="page" class="container"> 
		
		<!--BEGIN MAIN SECTION -->
		<div class="section portfolio" id="aluno">
			<div class="container">
				<section class="main">
					
					<div class="header">
						<h2 class="c-font-80 mbot5 pbot20 ptop20 align-center btop1 bbot1">ÁREA DE UPLOAD</h2> 
					</div>
					
					<div id="admin">
						<a href="aid_usuarios.php">Admin de Usuários</a>
					</div>
					
					<div class="clear pbot30"></div>
					
					<div class="column">
						<h3>Adicionar arquivo</h3>
						<form action="aid_upload.php" method="post" id="form" enctype="multipart/form-data">
							<table border="0" cellspacing="0" cellpadding="0" width="100%">
								<tr>
									<th><label for="descricao">Descrição</label></th>
									<td><input type="text" name="descricao" value="" id="descricao" class="campo" maxlength="200"></td>
								</tr>
								<tr>
									<th><label for="arquivo">Arquivo</label></th>
									<td><input type="file" name="arquivo" value="" id="arquivo" class="campo"></td>
								</tr>
								<tr>
									<th></th>
									<td><input type="submit" value="Enviar &rarr;" class="botao" /></td>
								</tr>
							</table>
						</form>
					</div>  
					
					<div class="clear"></div>
					
					<div class="pbot30">
						<?php
							
							$rows = dbQuery("SELECT * FROM arquivos WHERE status = 1 ORDER BY descricao ASC");
							if(dbLinhas($rows)){
								while($r = dbFetch($rows)){
						?>
						
						<h3 class="mbot5 icon-arrow-right">
							<a href="download.php?aid=<?php print $r["aid"]; ?>"><?php print $r["descricao"]; ?></a>
							<div class="arq_elimina"><a href="aid_upload.php?a=elimina&aid=<?php print $r["aid"]; ?>"><img src="images/cross.png" alt="" /></a></div>
						</h3>
						<?php
								}
							} else {
						?>
						<h3 class="mbot5 icon-arrow-right">Não há arquivos no momento...</h3>
						<?php
							}
						?>
						
					</div>
					
					<div class="clear"></div>        
					
				</section>
			</div>
		</div>
		<!-- END MAIN SECTION --> 
		
		<div class="footer container">
			<div class="footer_logo block-1 column mbot0"> <img src="images/logo.png" alt="Versi"> </div>
		</div>
		<span class="clear"></span>
	</div>
	
	<script type="text/javascript" src="js/script.js"></script>
	<script type="text/javascript" src="js/jquery.lightbox-0.5.pack.js"></script>
	<script src="js/jquery.tweet.js" charset="utf-8"></script>
	<script src="js/raphael.js" type="text/javascript"></script>
	
</body>
</html>