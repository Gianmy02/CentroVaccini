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
	if(isset($_GET['ann'])) { echo('<script>alert("PRENOTAZIONE ANNULLATA CON SUCCESSO")</script>'); }
						/*logout dal profilo*/
	if ( isset( $_GET['MODE'] ) )
	{
		if ( $_GET['MODE'] == "logout" )
		{
			session_destroy() ;
		
			header( "location: ../index.php" ) ;
		}
		
		if ( $_GET['MODE'] == "ins" )
		{
			$intervallo=300; //intervallo di 5 minuti in secondi
			$i=2;   //counter per i giorni
			$tempo = 25200; //secondi per le ore 7 + 1 che aggiunge di standard la funzione date per il fuso orario
			$sede= $_GET['sedi'];
			$vac= $_GET['vaccino'];
			$ID=''; //id disponibilita
								/*conta del personale che vaccina in una sede*/
			$querycont="SELECT COUNT(ID) AS Personale FROM Utente_Sede WHERE Utente_Sede.IDSede=".$sede.";";
			$rispcont = mysqli_query($conn, $querycont);
			$rigacont=mysqli_fetch_array($rispcont);
			/*si prende l'id della disponibilita per poi andare a diminuire le dosi*/
			$query = "SELECT ID FROM Disponibilita WHERE IDVaccino=".$vac." AND IDSede=".$sede.";";
			$risp = mysqli_query($conn, $query);
			if($risp)
			{
				$riga = mysqli_fetch_array($risp);
				$ID = $riga['ID'];
				$r=true;
				while($r == true)
				{
					$giorno = strtotime("+".$i." day");
					$timestamp = date('Y-m-d', $giorno);
					$query1 = "SELECT COUNT(Prenotazione.ID) AS ID FROM Prenotazione,Sedi,Disponibilita WHERE Data_Iniezione='".$timestamp."' AND Prenotazione.IDDisponibilita=Disponibilita.ID AND Sedi.ID=".$sede." AND Disponibilita.IDSede=Sedi.ID;";
					$risp1 = mysqli_query($conn,$query1);
					if($risp1)
					{
						$riga1 = mysqli_fetch_array($risp1);
						if($riga1['ID'] == 168*$rigacont['Personale']) //massimo vaccinazioni dalle 08:00 alle 22:00 facendone una ogni 5 minuti
						{
							/*va al giorno dopo*/
							$i++;
						}
						else
						{
							if($riga1['ID'] == 0)  
							{
								/*INSERIMENTO ALL INIZIO OVVERO ALLE 8*/
								$query2 = "INSERT INTO Prenotazione (Data_Iniezione,Ora_Iniezione,IDUtente,IDDisponibilita) VALUES ('".$timestamp."',".$tempo.",".$_SESSION['ID'].",".$ID.");";	
								$risp2 = mysqli_query($conn,$query2);
								if(!$risp2)
								{
									echo('Errore : '.mysqli_error($conn));
									exit;
								}
							}
							else
							{
								/*selezione dell ultimo del giorno e inserimento dopo l ultimo*/
								$query3 = "SELECT MAX(Prenotazione.ID) AS ID FROM Prenotazione,Sedi,Disponibilita WHERE Data_Iniezione='".$timestamp."'AND Prenotazione.IDDisponibilita=Disponibilita.ID AND Sedi.ID=".$sede." AND Disponibilita.IDSede=Sedi.ID;";//query per selezionare l'ultimo orario
								$risp3 = mysqli_query($conn,$query3);
								if($risp3)
								{
									$riga3 = mysqli_fetch_array($risp3);
									$query4 = "SELECT Data_Iniezione,Ora_Iniezione FROM Prenotazione WHERE ID=".$riga3['ID'].";";
									$risp4 = mysqli_query($conn,$query4);
									if($risp4)
									{
										$riga4 = mysqli_fetch_array($risp4);
													/*le persone sono vaccinate in contemporanea in base al numero di utenti che inocula vaccini in una sede*/
										if(($riga1['ID'] % $rigacont['Personale']) ==0)
										{
											$tempo = $riga4['Ora_Iniezione']+$intervallo;
										}
										else
										{
											$tempo = $riga4['Ora_Iniezione'];
										}
										$query2 = "INSERT INTO Prenotazione (Data_Iniezione,Ora_Iniezione,IDUtente,IDDisponibilita) VALUES ('".$timestamp."',".$tempo.",".$_SESSION['ID'].",".$ID.");";
										$risp2 = mysqli_query($conn,$query2);
										if(!$risp2)
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
							$query4 = "SELECT * FROM Prenotazione WHERE IDUtente=".$_SESSION['ID'].";";
							$risp4 = mysqli_query($conn,$query4);
							if($risp4)
							{
								if(mysqli_num_rows($risp4)>0)
								{
									$riga4 =  mysqli_fetch_array($risp4);
									$query5="SELECT Nome,Indirizzo FROM Sedi WHERE ID=".$sede.";";
									$risp5 = mysqli_query($conn,$query5);
									$riga5 =  mysqli_fetch_array($risp5);
									$query6="SELECT Nome FROM Vaccino WHERE ID=".$vac.";";
									$risp6=mysqli_query($conn,$query6);
									$riga6 =  mysqli_fetch_array($risp6);
									$oraconv = date('H:i',$riga4['Ora_Iniezione']);  //conversione in forma normale dell orario
									$message ="Sig/Sra ".$_SESSION['Cognome']." ".$_SESSION['Nome']." Prenotazione programmata nella sede: ".$riga5['Nome']." All'indirizzo: ".$riga5['Indirizzo']." Il giorno ".$riga4['Data_Iniezione']." alle ore ".$oraconv." Vaccino selezionato: ".$riga6['Nome']." CODICE PRENOTAZIONE DA MOSTRARE: ".$riga4['ID']."";
									$subject = 'CONFERMA PRENOTAZIONE VACCINO';
									$headers = "From: <campvax@altervista.org>";
									$emailsend = mail ($_SESSION['Email'], $subject, $message, $headers);
									header( "location: conferma.php?IDP=".$riga4['ID']."" );
								}
							}
							else
							{
								echo('Errore : '.mysqli_error($conn));
								exit;
							}
							$r=false;
						}
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
		} 
	}	

?>
	<?php
		/*utente gia prenotato*/
		$query="SELECT * FROM Prenotazione WHERE IDUtente=".$_SESSION['ID'].";";
		$risp=mysqli_query($conn,$query);
		if($risp)
		{
			if(mysqli_num_rows($risp)>0)
			{
				$riga =  mysqli_fetch_array($risp);
				header( "location: conferma.php?IDP=".$riga['ID']."" );
			}
		}
		else
		{
			echo('Errore : '.mysqli_error($conn));
			exit;
		}
	?>
	<HTML>
	<HEAD>
		<TITLE> PRENOTA IL TUO VACCINO </TITLE>
		<style>
body {
  margin: 20px;
  padding: 10px;
  background-color: #004882;
}

.box {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.box select {
  background-color: #0563af;
  color: white;
  padding: 12px;
  width: 500px;
  border: none;
  font-size: 20px;
  box-shadow: 0 5px 25px rgba(0, 0, 0, 0.2);
  -webkit-appearance: button;
  appearance: button;
  outline: none;
}


.box:hover::before {
  color: rgba(255, 255, 255, 0.6);
  background-color: rgba(255, 255, 255, 0.2);
}

.box select option {
  padding: 30px;
}
		</style>
	</HEAD>
	<BODY>
		<h1 style="color: white;">SIG/SRA <?php echo strtoupper($_SESSION['Cognome']); ?> <?php echo strtoupper($_SESSION['Nome']);?>  PRENOTA IL TUO VACCINO</h1>
		<p style="color: white;"><b>Scegli la sede in base alle tue preferenze ma potrai selezionare un vaccino in base alla tua fragilità, la tua età , le tue eventuali fragilità e la disponibilità della sede prescelta.</b><br><b style="color:yellow;">ATTENTO:</b> Se sei qui perchè ti hanno sospeso il vaccino non prenotarne un altro che non sia quello di cui hai gia avuto una dose, nel caso non lo trovassi aspetta che venga riabilitato, oppure a tua discrezione scegli un altro vaccino ma non è per nulla consigliato</p>		
		<?php
			/*calcolo eta attuale dell utente*/
			$dob = strtotime(str_replace("/","-",$_SESSION['Data_Nascita']));       
			$tdate = time();

			$eta = 0;
			while( $tdate > $dob = strtotime('+1 year', $dob))
			{
				++$eta;
			}
			
		?>
		
		<?php
				$sedeopzioniselect="<option>------</option>";
				$vaccinoopzioniselect="<option>------</option>";
				$ID="";//id della sede selezionata
				$vaccino="";//nome del vaccino selezionato
				
				$s1="SELECT ID,Nome FROM Sedi WHERE IDProvincia=".$_SESSION['IDProvincia']." order by nome";
				$q1=mysqli_query($conn,$s1);
				
				while($r1=mysqli_fetch_array($q1))
				{
					if(isset($_GET['sedi']) && $_GET['sedi']==$r1[0])
					{
						$sedeopzioniselect.="<option value='$r1[0]' selected='selected'>$r1[1]</option>";
					}
					else
					{
						$sedeopzioniselect.="<option value='$r1[0]'>$r1[1]</option>";
					}
				}
				// Se è stata selezionata la sede filtra i vaccini disponibili
				if(isset($_GET['sedi']) && $_GET['sedi']!="" && $_GET['sedi']!="------")
				 {
					
					$ID=$_GET['sedi'];
					
					// recupero il nome dei vaccini disponibili alla provincia selezionata per costruire il contenuto della select vaccini
					$s2="SELECT Vaccino.ID,Vaccino.Nome FROM Sedi,Vaccino,Disponibilita WHERE Sedi.ID=".$ID." AND Vaccino.ID=Disponibilita.IDVaccino AND Sedi.ID=Disponibilita.IDSede AND $eta>=Vaccino.Eta_Consigliata AND Disponibilita.N_dosi>(SELECT COUNT(ID)*2 FROM Prenotazione) AND Vaccino.Fragile>=".$_SESSION['Fragile']." AND Vaccino.Sospeso=0 ;";
					$q2=mysqli_query($conn,$s2);
						while($r2=mysqli_fetch_array($q2))
						{
							if(isset($_GET['vaccino']) && $_GET['vaccino']==$r2[0])
							{	
								$vaccinoopzioniselect.="<option value='$r2[0]' selected='selected'>$r2[1]</option>";
							}
							else
							{
								$vaccinoopzioniselect.="<option value='$r2[0]'>$r2[1]</option>";
							}
						}
				
				 }
				
				// Eseguito solo se scelto un vaccino
				if(isset($_GET['vaccino']) && $_GET['vaccino']!="")$vaccino=$_GET['vaccino'];
		?>
			
				
				<form name="mioform" method="GET" action="">
			<div class="box">	
				<label for="standard-select" style="color: white;"><b>Sede</b></label><br/>
				<select  name="sedi" onchange="javascript: document.mioform.submit();" id="standard-select">
				 <?php echo $sedeopzioniselect;?>
				 </select>
			 <span class="focus"></span>
			
				 <br/><br/>
				 
				 
				 <label for="standard-select" style="color: white;"><b>Vaccini Disponibili</b></label><br/>
				 <select name="vaccino" onchange="javascript: document.mioform.submit();">
				 <?php echo $vaccinoopzioniselect;?>
				 </select>
			 
				 <br/><br/>
				 <?php
					if(isset($_GET['vaccino']) && $_GET['vaccino']!="" && $_GET['vaccino']!="------")
					{
						if(isset($_GET['sedi']) && $_GET['sedi']!="" && $_GET['sedi']!="------")
						{
							echo('<a href="prenotazione.php?sedi='.$ID.'&vaccino='.$vaccino.'&MODE=ins"><input type="button" value="Prenota Adesso" style="width:120px; height:30px; background-color: red; color: white;"/></a>');
						}
					}
				 ?>
		 
				<input type="button" name="reset" style="width:100px; height:30px; background-color: black; color: white;" value="Reset" onClick="resetform()">
			
				<script>
				function resetform() 
				{
					document.mioform.sedi.value="";
					document.mioform.vaccino.value="";
					document.mioform.submit();
				}
				</script>
				<br/><br/>
				<a href="prenotazione.php?MODE=logout" style="color : red;">LOGOUT</a>
			</div>
			
		
	</BODY>
	
	</HTML>
	