<HTML>
	<HEAD>
		<TITLE> PRENOTA IL TUO VACCINO </TITLE>
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
                  <?php
   					session_start() ;
                    $IDP= $_GET['IDP'];
                   ?>
					<li class="active"><a href="conferma.php?IDP=<?php echo $IDP; ?>">INFO PRENOTAZIONE</a></li>
					<li><a href="modificaPasswordUtente.php?prov=Utenti">CAMBIA PASSWORD</a></li>
					<?php
					  if($_SESSION['N_dosi']==0)
					  {
					?>
						<li><a href="conferma.php?MODE=ann&IDP=<?php echo $_GET['IDP']; ?>">ANNULLA PRENOTAZIONE</a></li>
					<?php
					  }
					 ?>
					<li><a href="conferma.php?MODE=logout">LOGOUT</a></li>
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
	
			/*logout dal profilo*/
	
	if ( isset( $_GET['MODE'] ) )
	{
		if ( $_GET['MODE'] == "logout" )
		{
			session_destroy() ;
		
			header( "location: ../index.php" ) ;
		}
		if ( $_GET['MODE'] == "ann" )
		{
			$message ="Sig/Sra ".$_SESSION['Cognome']." ".$_SESSION['Nome']." le notifichiamo che la sua prenotazione per avere accesso alla propria vaccinazione è stata annullata";
			$subject = 'ANNULLAMENTO VACCINAZIONE';
			$headers = "From: <campvax@altervista.org>";
			$emailsend = mail ($_SESSION['Email'], $subject, $message, $headers);
			
			$query="DELETE FROM Prenotazione WHERE ID=".$IDP.";";
			
			$risp=mysqli_query($conn,$query);
			if($risp)
			{
				echo('<script>alert("PRENOTAZIONE ANNULLATA")</script>');
			}
			else
			{
				echo('Errore1 : '.mysqli_error($conn));
				exit;
			}
			
			header( "location: prenotazione.php?ann=1" ) ;
		}
	}	
	$query="SELECT * FROM Prenotazione WHERE IDUtente=".$_SESSION['ID']." AND ID=".$IDP.";";
	$risp=mysqli_query($conn,$query);
	if($risp)
	{
		if(mysqli_num_rows($risp)>0)
		{
			$riga =  mysqli_fetch_array($risp);
			$query1= "SELECT Sedi.Nome,Vaccino.Nome AS Vac,Sedi.ID,Sedi.Indirizzo,Vaccino.ID FROM Disponibilita,Vaccino,Sedi WHERE Disponibilita.ID=".$riga['IDDisponibilita']." AND Disponibilita.IDVaccino=Vaccino.ID AND Disponibilita.IDSede=Sedi.ID;";
			$risp1=mysqli_query($conn,$query1);
			if($risp1)
			{
				$riga1 =  mysqli_fetch_array($risp1);
				$oraconv = (date('H:i',$riga['Ora_Iniezione']));
	?>

				 
				
				<h1 align="center"><?php echo("Sig/Sra ".$_SESSION['Cognome']." ".$_SESSION['Nome']."<br> Prenotazione programmata nella sede<b> ".$riga1['Nome']."</b><br> All'indirizzo <b>".$riga1['Indirizzo']."</b><br> Vaccino selezionato: <b>".$riga1['Vac']."</b><br> Il giorno <b>".$riga['Data_Iniezione']."</b> alle ore <b>".$oraconv."</b><br> CODICE PRENOTAZIONE: <b>".$riga['ID']."</b>");?></h1>


<?php	
			}
			else
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
?>
			
	</BODY>
</HTML>