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
		
			header( "location: ../index.php" ) ;
		}
	}	
?>
<HTML>
	<HEAD>
		<TITLE> GESTIONE VACCINI </TITLE>
		<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Roboto'>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
				 			  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	
<style>
	html,body,h1,h2,h3,h4,h5,h6 {font-family: "Roboto", sans-serif}
</style>
<style>
.table4 {
font-family: "Lucida Sans Unicode", "Lucida Grande", Sans-Serif;
text-align: left;
border-collapse: separate;
border-spacing: 5px;
background: #ECE9E0;
color: #656665;
border: 16px solid #ECE9E0;
border-radius: 20px;
}
.table4 th {
font-size: 18px;
padding: 10px;
}
.table4 td {
background: #F5D7BF;
padding: 10px;
}

input[type=number] {
  width: 260px;
  box-sizing: border-box;
  border: 2px solid #ccc;
  border-radius: 4px;
  font-size: 16px;
  background-color: white;
  background-position: 10px 10px; 
  background-repeat: no-repeat;
  padding: 12px 20px 12px 40px;
  transition: width 0.4s ease-in-out;
}

input[type=number]:focus {
  background-color: #fffff0;
}
</style>

	</HEAD>
	<BODY class="w3-light-grey">
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
					<li class="active"><a href="gestionevaccini.php">MENU</a></li>
					<li><a href="modificaPasswordUtente.php?prov=Utente_Sede"> MODIFICA LA PASSWORD DELL'ACCOUNT</a></li>
					<li><a href="aggiuntadosi.php">AGGIUNGI NUOVE DOSI</a></li>
					<li><a href="gestionevaccini.php?MODE=logout">LOGOUT</a></li>
				  <?php
				if(isset($_GET['MODE']) && $_GET['MODE']=="cerca")
				{ 
					?>
					<li><a href="gestionevaccini.php">TORNA ALL'ELENCO DI TUTTE LE PERSONE IN LISTA</a></li>
			<?php } ?>
				  </ul>
				</div>
			  </div>
			</nav>
	<div class="w3-content w3-margin-top" style="max-width:1400px;">
		<div class="w3-row-padding">
			<div class="w3-third">
		<?php
					/*conta live dei vaccini rimanenti della giornata*/
			$oggi= date('Y-n-d');
			$query = "SELECT COUNT(Utenti.ID) AS Somma FROM Utenti,Prenotazione,Disponibilita,Vaccino,Utente_Sede WHERE Prenotazione.Data_Iniezione='".$oggi."' AND Utente_Sede.ID=".$_SESSION['ID']." AND Utente_Sede.IDSede=".$_SESSION['IDSede']." AND Utenti.ID=Prenotazione.IDUtente AND Utente_Sede.IDSede=Disponibilita.IDSede AND Disponibilita.ID=Prenotazione.IDDisponibilita AND Disponibilita.IDVaccino=Vaccino.ID ORDER BY Vaccino.Nome;";
			$risp = mysqli_query($conn, $query);
			if($risp)
			{
				$querysede="SELECT Sedi.Nome,Provincia.Nome AS Prov FROM Sedi,Provincia WHERE Sedi.ID=".$_SESSION['IDSede']." AND Sedi.IDProvincia=Provincia.ID;";
				$rispsede=mysqli_query($conn,$querysede);
				$rigasede=mysqli_fetch_array($rispsede);
				$riga = mysqli_fetch_array($risp);


		?>
		<div class="w3-white w3-text-grey w3-card-4">
			<div class="w3-display-container">
			<img src="http://campvax.altervista.org/img/logo.jpg" style="width:100%" alt="Avatar">
				<div class="w3-display-bottomleft w3-container w3-text-black">
						<h2> <?php echo "".$_SESSION['Cognome']." ".$_SESSION['Nome']."";?></h2>
		        </div>
			</div>
		
		

		        <div class="w3-container">
          <p><i class="fa fa-briefcase fa-fw w3-margin-right w3-large w3-text-teal"></i><?php echo $rigasede['Nome'];?> </p>
          <p><i class="fa fa-home fa-fw w3-margin-right w3-large w3-text-teal"></i><?php echo $rigasede['Prov'];?></p>
          <p><i class="fa fa-envelope fa-fw w3-margin-right w3-large w3-text-teal"></i><?php echo $_SESSION['Email'];?></p>

			</div>
			
			</div>
			
		</div>
		<div class="w3-twothird">
		<div class="w3-container w3-card w3-white w3-margin-bottom">
        <h2 class="w3-text-grey w3-padding-16"><i class="fa fa-suitcase fa-fw w3-margin-right w3-xxlarge w3-text-teal"></i>NUMERO DI VACCINAZIONI RIMASTE OGGI: <?php echo $riga['Somma']; ?></h2>
        <div class="w3-container">
	<?php
			}
			else
			{
				echo('Errore : '.mysqli_error($conn));
				exit;
			}
		?>
		
			<form method="GET" action="">
						
				<input type="hidden" name="MODE" value="cerca" />
				
					<b>Inserisci qui il codice di prenotazione fornito all'utente</b>
					<input type="number" name="prenotazione" required="" placeholder="Cerca Codice" min="1">
					<button class="w3-tag w3-teal w3-round">CERCA</button>	 
				</form>
		<?php
		
				if ( isset( $_GET['MODE'] ) )
				{

							/*conferma della vaccinazione*/
					if ( $_GET['MODE'] == "conf" )
					{
						$ID=$_GET['ID'];
						$IDV=$_GET['IDV'];
						$query="SELECT Dosi_Nec,Giorni_Attesa FROM Vaccino WHERE ID=".$IDV.";";
						$risp = mysqli_query($conn, $query);
						if($risp)
						{
							$query1="SELECT * FROM Utenti WHERE ID=".$ID.";";
							$risp1 = mysqli_query($conn, $query1);
							if($risp1)
							{
								$riga = mysqli_fetch_array($risp);
								$riga1 = mysqli_fetch_array($risp1);
								$doseagg=$riga1['N_dosi']+1;
								$query2= "UPDATE Utenti SET N_dosi=".$doseagg." WHERE ID=".$ID.";";
								$risp2 = mysqli_query($conn, $query2);
								if($risp2)
								{
									$query3= "SELECT ID,N_dosi FROM Disponibilita WHERE IDVaccino=".$IDV." AND IDSede=".$_SESSION['IDSede'].";";
											
									$risp3 = mysqli_query($conn, $query3);
									if($risp3)
									{
										$riga3 = mysqli_fetch_array($risp3);
										$dosedim = $riga3['N_dosi'];
										$dosedim=$dosedim-1;
										$query4= "UPDATE Disponibilita SET N_dosi=".$dosedim." WHERE ID=".$riga3['ID'].";";
										$risp4 = mysqli_query($conn, $query4);
										$query6= "SELECT * FROM Prenotazione WHERE IDUtente=".$ID." AND IDDisponibilita=".$riga3['ID']."; ";
										$risp6 = mysqli_query($conn, $query6);	
										if($risp6)
										{
												$riga6 = mysqli_fetch_array($risp6);
												$query5= "DELETE FROM Prenotazione WHERE IDUtente=".$ID." AND IDDisponibilita=".$riga3['ID'].";";
												$risp5 = mysqli_query($conn, $query5);
												if($riga['Dosi_Nec'] == $doseagg)
												{
													$query7="UPDATE Utenti SET N_dosi=100 WHERE ID=".$ID.";";
													$risp7 = mysqli_query($conn, $query7);
													$message ="Sig/Sra ".$riga1['Cognome']." ".$riga1['Nome']." le notifichiamo che ha completato il ciclo vaccinale oggi ".$timestamp." alle ore ".$oraconv." con il Vaccino selezionato: ".$riga10['Nome']."";
													$subject = 'CONFERMA FINE CICLO VACCINALE COMPLETATO';
													$headers = "From: <campvax@altervista.org>";
													$emailsend = mail ($riga1['Email'], $subject, $message, $headers);													
												}
												else
												{
													if($riga['Dosi_Nec']>$riga1['N_dosi'])
													{
														$giorno = strtotime("+".$riga['Giorni_Attesa']." day");
														$timestamp = date('Y-m-d', $giorno);
														$query7="INSERT INTO Prenotazione (Data_Iniezione,Ora_Iniezione,IDUtente,IDDisponibilita) VALUES ('".$timestamp."','".$riga6['Ora_Iniezione']."',".$ID.",".$riga3['ID'].")";
														$risp7 = mysqli_query($conn, $query7);	
														$query8 = "SELECT * FROM Prenotazione WHERE IDUtente=".$ID.";";
														$risp8 = mysqli_query($conn,$query8);
														if($risp8)
														{
															if(mysqli_num_rows($risp8)>0)
															{
																$riga8 =  mysqli_fetch_array($risp8);
																$query9="SELECT Nome,Indirizzo FROM Sedi WHERE ID=".$_SESSION['IDSede'].";";
																$risp9 = mysqli_query($conn,$query9);
																$riga9 =  mysqli_fetch_array($risp9);
																$query10 ="SELECT Nome FROM Vaccino WHERE ID=".$IDV.";";
																$risp10 = mysqli_query($conn,$query10);
																$riga10 =  mysqli_fetch_array($risp10);
																$oraconv = date('H:i',$riga6['Ora_Iniezione']);  //conversione in forma normale dell orario
																$message ="Sig/Sra ".$riga1['Cognome']." ".$riga1['Nome']."Abbiamo notato che lei ha effettuato la prima dose di vaccino, le programmiamo la seconda Prenotazione, programmata nella sede: ".$riga9['Nome']." All'indirizzo: ".$riga9['Indirizzo']." Il giorno ".$timestamp." alle ore ".$oraconv." Vaccino selezionato: ".$riga10['Nome']." CODICE PRENOTAZIONE DA MOSTRARE: ".$riga8['ID']."";
																$subject = 'CONFERMA PRENOTAZIONE RICHIAMO VACCINO';
																$headers = "From: <campvax@altervista.org>";
																$emailsend = mail ($riga1['Email'], $subject, $message, $headers);
																
																
															}
														}
														else
														{
															echo('Errore : '.mysqli_error($conn));
															exit;
														}
														
													}
												}
												header('location: gestionevaccini.php');
										}
										else
										{
											echo('Errore : '.mysqli_error($conn));
											exit;
										}
									}
									else
									{
										echo('Errore : '.mysqli_error($conn));
										exit;
									}
								}
								else
								{
									echo('Errore : '.mysqli_error($conn));
									exit;
								}
							}
							else
							{
								echo('Errore : '.mysqli_error($conn));
								exit;
							}
						}
						else
						{
							echo('Errore : '.mysqli_error($conn));
							exit;
						}
					}
					
					if ( $_GET['MODE'] == "cerca" )
					{
									/*ricerca dell utente che si è vaccinato piu rapida in base al codice di vaccinazione ricevuto*/
						$IDP='';
						$IDP=$_GET['prenotazione'];
						$query = "SELECT Vaccino.ID,Utenti.ID AS IDU,Utenti.Cognome,Utenti.Nome,Vaccino.Nome AS Vaccino FROM Utenti,Prenotazione,Disponibilita,Vaccino,Utente_Sede WHERE Prenotazione.Data_Iniezione='".$oggi."' AND Utente_Sede.ID=".$_SESSION['ID']." AND Utente_Sede.IDSede=".$_SESSION['IDSede']." AND Utenti.ID=Prenotazione.IDUtente AND Utente_Sede.IDSede=Disponibilita.IDSede AND Disponibilita.ID=Prenotazione.IDDisponibilita AND Disponibilita.IDVaccino=Vaccino.ID AND Prenotazione.ID=".$IDP." ORDER BY Vaccino.Nome;";
						
					}
				}
				else
				{
					$query = "SELECT Vaccino.ID,Utenti.ID AS IDU,Utenti.Cognome,Utenti.Nome,Vaccino.Nome AS Vaccino FROM Utenti,Prenotazione,Disponibilita,Vaccino,Utente_Sede WHERE Prenotazione.Data_Iniezione='".$oggi."' AND Utente_Sede.ID=".$_SESSION['ID']." AND Utente_Sede.IDSede=".$_SESSION['IDSede']." AND Utenti.ID=Prenotazione.IDUtente AND Utente_Sede.IDSede=Disponibilita.IDSede AND Disponibilita.ID=Prenotazione.IDDisponibilita AND Disponibilita.IDVaccino=Vaccino.ID ORDER BY Vaccino.Nome;";
				}
				
							/*si vanno a trovare tutti le vaccinazioni da fare oggi nella sede in cui lavora l'utente*/
						
						$risp = mysqli_query($conn, $query);
						if($risp)
						{				/*verifica del risultato della query e conseguente memorizzazione nella variabile di sessione*/
							
							if(mysqli_num_rows($risp)>0)	
							{
			?>
								<table class="table4">
								<tr><td><b>Cognome<b></td><td><b>Nome<b></td><td><b>Vaccino<b></td><td><b>CONFERMA</b></td></tr>
			<?php
								while($riga = mysqli_fetch_array($risp))
								{		
							/* Visualizza i dati */
			?>

											
									<tr><td> <?php echo $riga['Cognome']; ?></td><td> <?php echo $riga['Nome']; ?></td><td> <?php echo $riga['Vaccino']; ?></td><td><a href="gestionevaccini.php?MODE=conf&IDV=<?php echo $riga['ID'];?>&ID=<?php echo $riga['IDU']; ?>">Conferma Vaccinazione</a></td></tr>
			<?php
								}
			?>
								</table>
			<?php
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