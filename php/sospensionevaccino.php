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
						/*logout dal profilo*/
	if ( isset( $_GET['MODE'] ) )
	{
		if ( $_GET['MODE'] == "logout" )
		{
			session_destroy() ;
		
			header( "location: ../index.php.php" ) ;
		}
		if ( $_GET['MODE'] == "sosp" )
		{
			$IDV=$_GET['IDV'];
			$sosp=$_GET['sosp'];
			if($sosp==0)
			{
				$query="UPDATE Vaccino SET Sospeso=1 WHERE ID=".$IDV.";";
			}
			else
			{
				$query="UPDATE Vaccino SET Sospeso=0 WHERE ID=".$IDV.";";
			}
			$risp=mysqli_query($conn,$query);
			if($risp)
			{
				
				$subject = 'AVVISO: VACCINAZIONE ANNULLATA PER PROBLEMI AL VACCINO';
				$headers = "From: <campvax@altervista.org>";
				echo('<script>alert("STATO DEL VACCINO CAMBIATO, SONO STATE ELIMINATE TUTTE LE PRENOTAZIONI RELATIVE A QUESTO VACCINO")</script>');
				$query = "SELECT Prenotazione.ID AS IDP,Vaccino.Nome AS Vaccino,Utenti.ID,Utenti.Nome,Utenti.Cognome,Sedi.Nome AS Sede,Prenotazione.Data_Iniezione,Prenotazione.Ora_Iniezione,Utenti.Email FROM Vaccino,Prenotazione,Utenti,Disponibilita,Sedi WHERE Vaccino.ID=".$IDV." AND Prenotazione.IDUtente=Utenti.ID AND Prenotazione.IDDisponibilita=Disponibilita.ID AND Disponibilita.IDVaccino=Vaccino.ID AND Sedi.ID=Disponibilita.IDSede;";
				$risp=mysqli_query($conn,$query);
				while($riga = mysqli_fetch_array($risp))
				{
					$oraconv = date('H:i',$riga['Ora_Iniezione']);
					$message ="Sig/Sra ".$riga['Cognome']." ".$riga['Nome']." La prenotazione programmata nella sede: ".$riga['Sede']." Il giorno ".$riga['Data_Iniezione']." alle ore ".$oraconv." è stata annullata a causa della sospensione per problemi al Vaccino da lei selezionato: ".$riga['Vaccino']." ci scusiamo per il disagio, la reinvitiamo a prenotare un altro vaccino dal nostro sito oppure aspettare che il vaccino sospeso venga autorizzato nuovamente dalla regione";
					$emailsend = mail ($riga['Email'], $subject, $message, $headers);
					$query1= "DELETE FROM Prenotazione WHERE ID=".$riga['IDP'].";";
					$risp1=mysqli_query($conn,$query1);
					if(!$risp1)
					{
						echo('Errore : '.mysqli_error($conn));
						exit;
					}
				}
			}
			else
			{
				echo('Errore : '.mysqli_error($conn));
				exit;
			}
		}
	}	
?>
<HTML>
	<HEAD>
		<TITLE> SOSPENSIONE VACCINO </TITLE>
			 			  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
		<style>
		.table_blur {
		  background: #f5ffff;
		  border-collapse: collapse;
		  text-align: left;
		}
		.table_blur th {
		  border-top: 1px solid #777777;	
		  border-bottom: 1px solid #777777; 
		  box-shadow: inset 0 1px 0 #999999, inset 0 -1px 0 #999999;
		  background: linear-gradient(#9595b6, #5a567f);
		  color: white;
		  padding: 10px 15px;
		  position: relative;
		}
		.table_blur th:after {
		  content: "";
		  display: block;
		  position: absolute;
		  left: 0;
		  top: 25%;
		  height: 25%;
		  width: 100%;
		  background: linear-gradient(rgba(255, 255, 255, 0), rgba(255,255,255,.08));
		}
		.table_blur tr:nth-child(odd) {
		  background: #ebf3f9;
		}
		.table_blur th:first-child {
		  border-left: 1px solid #777777;	
		  border-bottom:  1px solid #777777;
		  box-shadow: inset 1px 1px 0 #999999, inset 0 -1px 0 #999999;
		}
		.table_blur th:last-child {
		  border-right: 1px solid #777777;
		  border-bottom:  1px solid #777777;
		  box-shadow: inset -1px 1px 0 #999999, inset 0 -1px 0 #999999;
		}
		.table_blur td {
		  border: 1px solid #e3eef7;
		  padding: 10px 15px;
		  position: relative;
		  transition: all 0.5s ease;
		}
		.table_blur tbody:hover td {
		  color: transparent;
		  text-shadow: 0 0 3px #a09f9d;
		}
		.table_blur tbody:hover tr:hover td {
		  color: #444444;
		  text-shadow: none;
		}
		</style>

	</HEAD>
	<BODY bgcolor="#9c9c9c">
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
					<li class="active"><a href="sospensionevaccino.php">SOSPENDI VACCINO</a></li>
					<li><a href="nuovovaccino.php">NUOVO VACCINO</a></li>
					<li><a href="menusceltaregione.php?MODE=logout">LOGOUT</a></li>
				  </ul>
				</div>
			  </div>
			</nav>
		<h1 align="center">SOSPENSIONE VACCINO</h1>
		<h2 align="center">TABELLA DI GESTIONE DEI VACCINI</h2>
		<table border="1" class="table_blur" align="center">
		<tr><td align="center"> NOME VACCINO </td><td align="center">STATO</td><td align="center"> AZIONE </td></tr>
		<?php
			$query="SELECT ID,Nome,Sospeso FROM Vaccino";
			$risp = mysqli_query($conn, $query);
			if($risp)
			{
				while( $riga = mysqli_fetch_array($risp) )
				{
					
				
		?>
				<tr><td align="center"><?php echo $riga['Nome'];?></td><td align="center"><?php if($riga['Sospeso']==1){echo 'SOSPESO';}else{ echo 'IN UTILIZZO';} ?></td><td><a href="sospensionevaccino.php?MODE=sosp&IDV=<?php echo $riga['ID']?>&sosp=<?php echo $riga['Sospeso']?>"><?php if($riga['Sospeso']==1){echo 'ATTIVA';}else{ echo 'SOSPENDI';}?></a></td></tr>
		<?php
				
				}
			}
			else
			{
				echo('Errore : '.mysqli_error($conn));
				exit;
			}
		?>
		</table>
	</BODY>
</HTML>