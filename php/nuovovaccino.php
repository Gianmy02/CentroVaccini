<HTML>
	<HEAD>
		<TITLE>NUOVO VACCINO</TITLE>
		<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
	 	 			  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	
	 <style>
      html, body {
      min-height: 100%;
      }
      body, div, form, input, select, textarea, p { 
      padding: 0;
      margin: 0;
      outline: none;
      font-family: Roboto, Arial, sans-serif;
      font-size: 14px;
      color: #666;
      line-height: 22px;
      }
      h1 {
      margin: 15px 0;
      font-weight: 400;
      }
      .testbox {
      display: flex;
      justify-content: center;
      align-items: center;
      height: inherit;
      padding: 3px;
      }
      form {
      width: 100%;
      padding: 20px;
      background: #fff;
      box-shadow: 0 2px 5px #ccc; 
      }
      input, select, textarea {
      margin-bottom: 10px;
      border: 1px solid #ccc;
      border-radius: 3px;
      }
      input:hover, select:hover, textarea:hover {
      outline: none;
      box-shadow: 0 0 5px 0 #095484;
      }
      input {
      width: calc(100% - 10px);
      padding: 5px;
      }
      select {
      width: 100%;
      padding: 7px 0;
      background: transparent;
      }
      textarea {
      width: calc(100% - 2px);
      paddung: 5px;
      }
      .item {
      position: relative;
      margin: 10px 0;
      }
      .item:hover p, .item:hover i {
      color: #095484;
      }
      input:hover, select:hover, textarea:hover {
      box-shadow: 0 0 5px 0 #095484;
      }
      .status:hover input {
      box-shadow: none;
      }
      .status label:hover input {
      box-shadow: 0 0 5px 0 #095484;
      }
      .status-item input, .status-item span {
      width: auto;
      vertical-align: middle;
      }
      .status-item input {
      margin: 0;
      }
      .status-item span {
      margin: 0 20px 0 5px;
      }
      input[type="date"]::-webkit-inner-spin-button {
      display: none;
      }
      input[type="time"]::-webkit-inner-spin-button {
      margin: 2px 22px 0 0;
      }
      .item i, input[type="date"]::-webkit-calendar-picker-indicator {
      position: absolute;
      font-size: 20px;
      color: #a9a9a9;
      }
      .item i {
      right: 1%;
      top: 30px;
      z-index: 1;
      }
      [type="date"]::-webkit-calendar-picker-indicator {
      right: 0;
      z-index: 2;
      opacity: 0;
      cursor: pointer;
      }
      .btn-block {
      margin-top: 20px;
      text-align: center;
      }
      button {
      width: auto;
      padding: 10px;
      border: none;
      -webkit-border-radius: 5px; 
      -moz-border-radius: 5px; 
      border-radius: 5px; 
      background-color: #095484;
      font-size: 16px;
      color: #fff;
      cursor: pointer;
      }
      button:hover {
      background-color: #0666a3;
      }
      @media (min-width: 568px) {
      .name-item {
      display: flex;
      flex-wrap: wrap;
      justify-content: space-between;
      }
      .name-item input {
      width: calc(50% - 20px);
      }
      }
    </style>
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
					<li><a href="visualizzastatistiche.php"> VISUALIZZA STATISTICHE</a></li>
					<li><a href="modificaPasswordUtente.php?prov=Utente_Regione"> MODIFICA LA PASSWORD DELL'ACCOUNT</a></li>
					<li><a href="sospensionevaccino.php">SOSPENDI VACCINO</a></li>
					<li class="active"><a href="nuovovaccino.php">NUOVO VACCINO</a></li>
					<li><a href="menusceltaregione.php?MODE=logout">LOGOUT</a></li>
				  </ul>
				</div>
			  </div>
			</nav>
		<?php
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
			if(isset($_POST['MODE']))
			{
				if( $_POST['MODE']== "agg" )
				{
					$nome=$_POST['nome'];
					$dosi=$_POST['dosi'];
					$eta=$_POST['eta'];
					if(isset($_POST['fragile']))
					{
						$fragile=1;
					}
					else
					{
						$fragile=0;
					}
					if($dosi>1)
					{
						$giorni=$_POST['giorni'];
						$query="INSERT INTO Vaccino(Nome,Dosi_Nec,Eta_Consigliata,Fragile,Giorni_Attesa) VALUES ('".$nome."',".$dosi.",".$eta.",".$fragile.",".$giorni.");";
					}
					else
					{
						$query="INSERT INTO Vaccino(Nome,Dosi_Nec,Eta_Consigliata,Fragile) VALUES ('".$nome."',".$dosi.",".$eta.",".$fragile.");";
					}
					$risp=mysqli_query($conn,$query);
					if($risp)
					{
						echo('<script>alert("NUOVO VACCINO INSERITO CON SUCCESSO")</script>');
					}
					else
					{
						echo('Errore : '.mysqli_error($conn));
						exit;
					}
					
				}
			}
		?>
		<div class="testbox">
		<form method="post" action="">
		<h1>AGGIUNTA NUOVO VACCINO</h1>
			<input type="hidden" name="MODE" value="agg" />
			<p>Nome Vaccino</p>
			<div class="name">
			<input type="text" name="nome" required="" placeholder="Nome">
			</div>
			<div class="item">
			<p>Dosi Necessarie all'immunizzazione:</p>
			<input type="number" name="dosi" required="" placeholder="Dosi Necessarie" min="1">
			</div>
			<div class="item">
			<p>Eta minima consigliata:</p>
			<input type="number" name="eta" required="" placeholder="Eta minima consigliata" min="1" max="100">
			</div>
			<div class="item">
			<p>Giorni di attesa da fare dopo ogni dose di vaccino,verrà considerato solo se le dosi per l'immunizzazione saranno più di 1:</p>
			<input type="number" name="giorni" required="" placeholder="Giorni attesa" min="10" max="365">
			</div>
			 <div class="item status">
			<p>Adatto per persone fragili</p>
			 <div class="status-item">
			<input type="checkbox" name="fragile" value=0></label>
			</div>
			</div>
			<div class="btn-block">
			<button>Aggiungi</button>
</div>
</div>			
		</form>
	</BODY>
<HTML>