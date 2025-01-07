<html>

	<?php
	
		session_start() ;
			
	 
										/*variabili per la connessione al DB*/
		$mysql_dbname = 'my_campvax';
		$mysql_host = 'localhost';
		$mysql_username = 'root';
		$mysql_password = '';														
																		
																		/* Connessione al DB */
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
		$primo=0;
		if(isset($_GET['primo'])) 
		{ 
			$primo=1;
			echo('<script>alert("ATTENZIONE PRIMO ACCESSO EFFETTUATO, CAMBIA LA PASSWORD")</script>'); 
		}
		
		if(isset($_GET['prov'])) { $prov = $_GET['prov']; }
		
		
		if(isset($_POST['MODE']))
		{
			if($_POST['MODE'] == 'modifica_password')
			{	
				if(isset($_POST['pass_att'])) { $pass_att = $_POST['pass_att']; }
				
				if(isset($_POST['nuova_pass'])) { $nuova_pass = $_POST['nuova_pass']; }
				
				if(isset($_POST['nuova_pass2'])) { $nuova_pass2 = $_POST['nuova_pass2']; }
				
				$pass_att=crypt($pass_att, '_J9..rasm');			
				
				$query = "SELECT Password AS password_utente FROM ".$prov." WHERE ID = ".$_SESSION['ID']."";
				
				$risp = mysqli_query($conn, $query);
				
				if($risp)
				{
						$riga = mysqli_fetch_array($risp);
						
						if($pass_att == $riga['password_utente'])
						{
							if(strlen($nuova_pass) >=6)
							{
								if($nuova_pass == $nuova_pass2)
								{
									$nuova_pass=crypt($nuova_pass2, '_J9..rasm');	
									
									$query_pass = "UPDATE ".$prov." SET password = '".$nuova_pass."' WHERE ID = ".$_SESSION['ID'].";";
				
									$result_pass = mysqli_query($conn, $query_pass);
											
									if($result_pass)
									{
										echo('<script>alert("Password modificata con successo dovrai rieffettuare il login con la nuova passowrd")</script>');
										session_destroy() ;
			
										header( "location: ../index.php?mod=1" ) ;
									}
									else
									{
										echo('Errore : '.mysqli_error($conn));
										exit;
									}
								}
								else
								{
									echo('<script>alert(" Le due password inserite non corrispondono")</script>');
								}
							}
							else
							{
								echo('<script>alert("La nuova password deve contenere almeno 6 caratteri")</script>');
							}
						}
						else
						{
							echo('<script>alert("Password attuale errata")</script>');
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
	
	<head>
	
		<title>
			
			Modifica password
			
		</title>
	<style>	
.align {
  align-items: center;
  display: flex;
  flex-direction: column;
  justify-content: center;
}

/* helpers/icon.css */

.icon {
  font-size: 2rem;
}

/* layout/base.css */

html {
  height: 100%;
}

body {
  color: #888;
  font-family: sans-serif;
  line-height: 1.5;
  margin: 0;
  min-height: 100%;
}

/* modules/headline.css */

h3 {
  font-size: 1.5rem;
  margin-top: 1.5em;
  color: #111;
  margin-bottom: 1.5em;
}

/* modules/paragraph.css */

p {
  margin-bottom: 1.5em;
  margin-top: 1.5em;
}

/* modules/form.css */

input {
  font: inherit;
  outline: 0;
}

.form__field {
  position: relative;
}

.form__field .icon {
  position: absolute;
  right: 1em;
  top: 50%;
  transform: translateY(-50%)
}

.form__input {
  border-radius: 0.25em;
  border-style: solid;
  border-width: 2px;
  font-size: 1.5rem;
  padding: 0.5em 4em 0.5em 2em;
}

.form__input:valid {
  border-color: forestgreen;
}

.form__input:valid + .icon::after {
  content: '😄';
}

.form__input:invalid {
  border-color: firebrick;
}

.form__input:invalid + .icon::after {
  content: '😖';
}

</style>	
	</head>
	
	<body class="align">
		
	
		<div>
			
			<h2> Modifica password </h2>
			<p>Confermata la nuova Password dovrai rieffettuare il login!</p>
			
			<form method="post" action="modificaPasswordUtente.php?prov=<?php echo $prov; ?>">
			
				<input type="hidden" name="MODE" value="modifica_password"> 
			<div class="form__field">	
				
				
				<input type="password" class="form__input" name="pass_att" placeholder="Password attuale" pattern="<?php echo $_SESSION['Password']; ?>" required> 
				<span class="icon"></span>
			</div>
				<br><br>
			<div class="form__field">	
				 
				
				<input type="password" class="form__input" name="nuova_pass" placeholder="Nuova password" pattern=".{6,}" required> 
				<span class="icon"></span>
			</div>
				<br><br>
			<div class="form__field">	
				
				
				<input type="password" class="form__input" name="nuova_pass2" placeholder="Ripeti nuova password" pattern=".{6,}" required> 
				<span class="icon"></span>
			</div>
				<br><br>
				
				<button style="width:100px; height:30px; background-color: red; color: white;"> Aggiorna </button>
				
			</form>
			
			<?php 
				if($prov=='Utente_Regione' AND $primo!=1)
				{
		
			?>
			<a href="menusceltaregione.php" style="color: red; ">RITORNA AL MENU</a>
			
			<?php
				}
				if($prov=='Utente_Sede' AND $primo!=1)
				{
						
					
				
			?>
			<a href="gestionevaccini.php" style="color: red;">RITORNA ALLA GESTIONE DEI VACCINI</a>
			
			<?php
				}
				
				if($prov=='Utenti' AND $primo!=1)
				{
						
			
			?>
			<a href="prenotazione.php" style="color: red; ">RITORNA ALLA MIA AREA PERSONALE</a>
			
			<?php
				}
			?>
		<div>
		
	</body>
	

</html>