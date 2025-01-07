<HTML>
	<HEAD>
	 <TITLE> MENU SCELTA </TITLE>
	 			  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	</HEAD>
	<BODY>
	<?php
		if ( isset( $_GET['MODE'] ) )
		{
			if ( $_GET['MODE'] == "logout" )		/*modalita per il logout*/
			{
				session_destroy() ;
		
				header( "location: ../index.php" ) ;
			}
		}
	?>
		<nav class="navbar navbar-inverse">
			  <div class="container-fluid">
				<div class="navbar-header">
				  <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>                        
				  </button>
				  <a class="navbar-brand" href="#">CAMP-VAX</a>
				</div>
				<div  class="collapse navbar-collapse" id="myNavbar">
				  <ul class="nav navbar-nav">
					<li class="active"><a href="menusceltaregione.php">MENU</a></li>
					<li><a href="visualizzastatistiche.php"> VISUALIZZA STATISTICHE</a></li>
					<li><a href="modificaPasswordUtente.php?prov=Utente_Regione"> MODIFICA LA PASSWORD DELL'ACCOUNT</a></li>
					<li><a href="sospensionevaccino.php">SOSPENDI VACCINO</a></li>
					<li><a href="nuovovaccino.php">NUOVO VACCINO</a></li>
					<li><a href="menusceltaregione.php?MODE=logout">LOGOUT</a></li>
				  </ul>
				</div>
			  </div>
			</nav>
			<p align="center"><img src="http://campvax.altervista.org/img/regione_campania.png"></img></p>
				<h1 align="center">REGIONE CAMPANIA</h1>
				<h2 align="center">GESTIONE VACCINAZIONI</h2>
				<h3 align="center">BENTORNATO</h3>
	</BODY>
</HTML>