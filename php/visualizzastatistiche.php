<HTML>
	<HEAD>
	 <TITLE> VISUALIZZA STATISTICHE </TITLE>
	 <style>
		.table2 {
		font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
		font-size: 14px;
		border-collapse: collapse;
		text-align: center;
		}
		.table2 th, .table2 td:first-child {
		background: #AFCDE7;
		color: white;
		padding: 10px 20px;
		}
		.table2 th, .table2 td {
		border-style: solid;
		border-width: 0 1px 1px 0;
		border-color: white;
		}
		.table2 td {
		background: #D8E6F3;
		}
		.table2 th:first-child, .table2 td:first-child {
		text-align: left;

	</style>
				  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	</HEAD>
<BODY>
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
					<li><a href="menusceltaregione.php">MENU</a></li>
					<li class="active"><a href="visualizzastatistiche.php"> VISUALIZZA STATISTICHE</a></li>
					<li><a href="modificaPasswordUtente.php?prov=Utente_Regione"> MODIFICA LA PASSWORD DELL'ACCOUNT</a></li>
					<li><a href="sospensionevaccino.php">SOSPENDI VACCINO</a></li>
					<li><a href="nuovovaccino.php">NUOVO VACCINO</a></li>
					<li><a href="menusceltaregione.php?MODE=logout">LOGOUT</a></li>
				  </ul>
				</div>
			  </div>
			</nav>
	<h1 align="center">VISUALIZZA STATISTICHE</h1>
	<?php
		session_start() ;
		$mysql_dbname = 'my_campvax';
		$mysql_host = 'localhost';
		$mysql_username = 'root';
		$mysql_password = '';
		$conn = mysqli_connect($mysql_host, $mysql_username, $mysql_password);
		if(!$conn) 
		{
			echo('Errore : '.mysqli_error($conn));
			exit;
		}
																	/* Seleziona DB */
		$db = mysqli_select_db($conn, $mysql_dbname);
		if(!$db) 
		{
			echo('Errore : '.mysqli_error($conn));
			exit;
		}
		$giorno=strtotime("-60 year");
		$timestamp = date('Y-m-g',$giorno);
		
	?>
	<p align="center"><img src="http://campvax.altervista.org/img/regione_campania.png"></img><p>
	<table align="center" border="1" class="table2">
	<?php
		$query = "SELECT COUNT(ID) AS Prima_dose FROM Utenti WHERE N_dosi=1;"; //persone solo alla prima dose
		$risp = mysqli_query($conn, $query);
		$riga = mysqli_fetch_array($risp);
		echo('<tr><td> CATEGORIA </td><td>  Prima Dose  </td><td> Ciclo Vaccinale Completato </td></tr>');
		$query1 = "SELECT COUNT(ID) AS Immune FROM Utenti WHERE N_dosi=100;";   //persone gia immunizzate
		$risp1 = mysqli_query($conn, $query1);
		$riga1 = mysqli_fetch_array($risp1);
	?>
	
		<tr><td> TOTALE PERSONE </td><td align="center"><?php echo $riga['Prima_dose'];?></td><td align="center"><?php echo $riga1['Immune']; ?></td></tr>
	<?php
		$query = "SELECT COUNT(ID) AS Prima_dose FROM Utenti WHERE N_dosi=1 AND Fragile=1;"; //persone solo alla prima dose
		$risp = mysqli_query($conn, $query);
		$query1 = "SELECT COUNT(ID) AS Immune FROM Utenti WHERE N_dosi=100 AND Fragile=1;";   //persone gia immunizzate
		$risp1 = mysqli_query($conn, $query1);
		$riga1 = mysqli_fetch_array($risp1);
	?>
		<tr><td> TOTALE SOLO PER PERSONE FRAGILI </td><td align="center"><?php echo $riga['Prima_dose'];?></td><td align="center"><?php echo $riga1['Immune']; ?></td></tr>
	<?php
		$query = "SELECT COUNT(ID) AS Prima_dose FROM Utenti WHERE N_dosi=1 AND Data_Nascita<=".$timestamp.";"; //persone solo alla prima dose
		$risp = mysqli_query($conn, $query);
		$riga = mysqli_fetch_array($risp);
		$query1 = "SELECT COUNT(ID) AS Immune FROM Utenti WHERE N_dosi=100 AND Data_Nascita<=".$timestamp.";";   //persone gia immunizzate
		$risp1 = mysqli_query($conn, $query1);
		$riga1 = mysqli_fetch_array($risp1);
	?>
		<tr><td> TOTALE PERSONE VACCINATE OVER 60 </td><td align="center"><?php echo $riga['Prima_dose'];?></td><td align="center"><?php echo $riga1['Immune']; ?></td></tr>
	<?php
		$query = "SELECT COUNT(ID) AS Prima_dose FROM Utenti WHERE N_dosi=1 AND IDProvincia=1;"; //persone solo alla prima dose
		$risp = mysqli_query($conn, $query);
		$riga = mysqli_fetch_array($risp);
		$query1 = "SELECT COUNT(ID) AS Immune FROM Utenti WHERE N_dosi=100 AND IDProvincia=1;";   //persone gia immunizzate
		$risp1 = mysqli_query($conn, $query1);
		$riga1 = mysqli_fetch_array($risp1);
	?>
		<tr><td> TOTALE PERSONE VACCINATE IN PROVINCIA DI NAPOLI </td><td align="center"><?php echo $riga['Prima_dose'];?></td><td align="center"><?php echo $riga1['Immune']; ?></td></tr>
	<?php
		$query = "SELECT COUNT(ID) AS Prima_dose FROM Utenti WHERE N_dosi=1 AND IDProvincia=2;"; //persone solo alla prima dose
		$risp = mysqli_query($conn, $query);
		$riga = mysqli_fetch_array($risp);
		$query1 = "SELECT COUNT(ID) AS Immune FROM Utenti WHERE N_dosi=100 AND IDProvincia=2;";   //persone gia immunizzate
		$risp1 = mysqli_query($conn, $query1);
		$riga1 = mysqli_fetch_array($risp1);
	?>
		<tr><td> TOTALE PERSONE VACCINATE IN PROVINCIA DI CASERTA </td><td align="center"><?php echo $riga['Prima_dose'];?></td><td align="center"><?php echo $riga1['Immune']; ?></td></tr>
	<?php
		$query = "SELECT COUNT(ID) AS Prima_dose FROM Utenti WHERE N_dosi=1 AND IDProvincia=3;"; //persone solo alla prima dose
		$risp = mysqli_query($conn, $query);
		$riga = mysqli_fetch_array($risp);
		$query1 = "SELECT COUNT(ID) AS Immune FROM Utenti WHERE N_dosi=100 AND IDProvincia=3;";   //persone gia immunizzate
		$risp1 = mysqli_query($conn, $query1);
		$riga1 = mysqli_fetch_array($risp1);
	?>
		<tr><td> TOTALE PERSONE VACCINATE IN PROVINCIA DI BENEVENTO </td><td align="center"><?php echo $riga['Prima_dose'];?></td><td align="center"><?php echo $riga1['Immune']; ?></td></tr>
	<?php
		$query = "SELECT COUNT(ID) AS Prima_dose FROM Utenti WHERE N_dosi=1 AND IDProvincia=4;"; //persone solo alla prima dose
		$risp = mysqli_query($conn, $query);
		$riga = mysqli_fetch_array($risp);
		$query1 = "SELECT COUNT(ID) AS Immune FROM Utenti WHERE N_dosi=100 AND IDProvincia=4;";   //persone gia immunizzate
		$risp1 = mysqli_query($conn, $query1);
		$riga1 = mysqli_fetch_array($risp1);
	?>
		<tr><td> TOTALE PERSONE VACCINATE IN PROVINCIA DI SALERNO </td><td align="center"><?php echo $riga['Prima_dose'];?></td><td align="center"><?php echo $riga1['Immune']; ?></td></tr>
	<?php
		$query = "SELECT COUNT(ID) AS Prima_dose FROM Utenti WHERE N_dosi=1 AND IDProvincia=5;"; //persone solo alla prima dose
		$risp = mysqli_query($conn, $query);
		$riga = mysqli_fetch_array($risp);
		$query1 = "SELECT COUNT(ID) AS Immune FROM Utenti WHERE N_dosi=100 AND IDProvincia=5;";   //persone gia immunizzate
		$risp1 = mysqli_query($conn, $query1);
		$riga1 = mysqli_fetch_array($risp1);
	?>
		<tr><td> TOTALE PERSONE VACCINATE IN PROVINCIA DI AVELLINO </td><td align="center"><?php echo $riga['Prima_dose'];?></td><td align="center"><?php echo $riga1['Immune']; ?></td></tr>
		
	
		</table>
		<br>

</BODY>
</HTML>
