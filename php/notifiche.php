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
	$giorno=strtotime("+1 day");
	$timestamp=date('Y-m-d',$giorno);
	$query ="SELECT Utenti.Cognome,Utenti.Email,Utenti.Nome,Prenotazione.ID,Prenotazione.Data_Iniezione,Prenotazione.Ora_Iniezione,Vaccino.Nome AS Vaccino,Sedi.Indirizzo,Sedi.Nome AS Sede FROM Utenti,Prenotazione,Disponibilita,Vaccino,Sedi WHERE Prenotazione.Data_Iniezione='".$timestamp."' AND Utenti.ID=Prenotazione.IDUtente  AND Prenotazione.IDDisponibilita=Disponibilita.ID AND Disponibilita.IDVaccino=Vaccino.ID AND Disponibilita.IDSede=Sedi.ID; ";
	$risp = mysqli_query($conn, $query);
	if($risp)
	{
		if(mysqli_num_rows($risp)>0)
		{
			while($riga = mysqli_fetch_array($risp))
			{
				$oraconv = date('H:i',$riga['Ora_Iniezione']);
				$message ="Sig/Sra ".$riga['Cognome']." ".$riga['Nome']." le ricordiamo che la vaccinazione è programmata nella sede: ".$riga['Sede']." All'indirizzo: ".$riga['Indirizzo']." Il giorno di domani: ".$riga['Data_Iniezione']." alle ore ".$oraconv." Vaccino selezionato: ".$riga['Vaccino']." CODICE PRENOTAZIONE DA MOSTRARE: ".$riga['ID']."";
				$subject = 'NOTIFICA DI RICORDO VACCINAZIONE AL GIORNO DI DOMANI';
				$headers = "From: <campvax@altervista.org>";
				$emailsend = mail ($riga['Email'], $subject, $message, $headers);
			}
		}
	}
	$giorno=strtotime("-1 day");
	$timestamp=date('Y-m-d',$giorno);
	$query ="SELECT Utenti.Cognome,Utenti.Email,Utenti.Nome,Prenotazione.ID,Prenotazione.Data_Iniezione,Prenotazione.Ora_Iniezione,Vaccino.Nome AS Vaccino,Sedi.Nome AS Sede,Sedi.Indirizzo FROM Utenti,Prenotazione,Disponibilita,Vaccino,Sedi WHERE Prenotazione.Data_Iniezione='".$timestamp."' AND Utenti.ID=Prenotazione.IDUtente  AND Prenotazione.IDDisponibilita=Disponibilita.ID AND Disponibilita.IDVaccino=Vaccino.ID AND Disponibilita.IDSede=Sedi.ID; ";
	$risp = mysqli_query($conn, $query);
	if($risp)
	{
		if(mysqli_num_rows($risp)>0)
		{
			while($riga = mysqli_fetch_array($risp))
			{
				$oraconv = date('H:i',$riga['Ora_Iniezione']);
				$message ="Sig/Sra ".$riga['Cognome']." ".$riga['Nome']." la avvisiamo che la vaccinazione che era programmata nella sede: ".$riga['Sede']." All'indirizzo: ".$riga['Indirizzo']." Il giorno di ieri: ".$riga['Data_Iniezione']." alle ore ".$oraconv." Vaccino selezionato: ".$riga['Vaccino']." con  CODICE PRENOTAZIONE: ".$riga['ID']." è stata cancellata a causa di una sua mancata presenza, la invitiamo a rifare l'accesso al suo account e riprenotare la sua vaccinazione";
				$subject = 'AVVISO DI VACCINAZIONE CANCELLATA A CAUSA DI MANCATA PRESENZA IN SEDE';
				$headers = "From: <campvax@altervista.org>";
				$emailsend = mail ($riga['Email'], $subject, $message, $headers);
			}
			$query1="DELETE FROM Prenotazione WHERE Data_Iniezione='".$timestamp."';";
			$risp1 = mysqli_query($conn, $query1);
		}
	}
?>