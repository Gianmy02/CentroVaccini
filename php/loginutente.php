<?php
	session_start() ;
			$mysql_dbname = 'my_campvax';
			$mysql_host = 'localhost';
			$mysql_username = 'root';
			$mysql_password = '';
			$mail='';
			$pass='';
			$pass2='';
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
			/*verifica se sono state inviate le credenziali*/
	if ( isset( $_POST['MODE'] ) )
	{
		if ( $_POST['MODE'] == "reg" )
		{
			$key="Riviello";
			$nome = ucfirst($_POST['nome']) ;  

			$cognome = ucfirst($_POST['cognome']);
			
			$CF = strtoupper($_POST['CF']);  //viene convertita la stringa tutta in maiuscolo per far combaciare i codici fiscali criptati
			$query = "SELECT * FROM Utenti WHERE Email IS NULL AND Nome='".$nome."' AND Cognome='".$cognome."';";
			$risp = mysqli_query($conn, $query);
			if($risp)
			{
				
						/*verifica dell effettiva risposta*/
				if(mysqli_num_rows($risp)>0)
				{
                /*cifrare*/
					$f=true;
					while($riga=mysqli_fetch_array($risp) AND $f==true)
					{
						$ciphertext=$riga['CF'];
						$c = base64_decode($ciphertext);
						$ivlen = openssl_cipher_iv_length($cipher="AES-128-CBC");
						$iv = substr($c, 0, $ivlen);
						$hmac = substr($c, $ivlen, $sha2len=32);
						$ciphertext_raw = substr($c, $ivlen+$sha2len);
						$original_plaintext = openssl_decrypt($ciphertext_raw, $cipher, $key, $options=OPENSSL_RAW_DATA, $iv);
						$calcmac = hash_hmac('sha256', $ciphertext_raw, $key, $as_binary=true);
						if (hash_equals($hmac, $calcmac))//PHP 5.6+ timing attack safe comparison
						{
									/*ricerca del Cf siccome per ogni volta che viene cifrato vi sono risultati diversi*/
							if($original_plaintext==$CF)
							{
								$f=false;
								$_SESSION['ID']=$riga['ID'];
								$_SESSION['Nome']=$riga['Nome'];
								$_SESSION['Cognome']=$riga['Cognome'];
								$_SESSION['CF']=strtoupper($_POST['CF']);
								$_SESSION['Data_Nascita']=$riga['Data_Nascita'];
								$_SESSION['N_dosi']=$riga['N_dosi'];
							}
						}
					}
					if($f==false)
					{	
						$mail = $_POST['mail'] ;


						$pass = $_POST['pass'] ;


						$pass2 = $_POST['pass2'] ;
							if($pass==$pass2)
							{
												/*se è fragile viene settato ad 1*/
								if(isset($_POST['fragile']))
								{
									$fragile=1;
								}
								else
								{
									$fragile=0;
								}
									/*memorizzazione di tutti i dati nella sessione*/
								$IDProvincia=$_POST['IDProvincia'];
								$_SESSION['Email']=$mail;
								$_SESSION['Password']=$pass;
								$_SESSION['Fragile']=$fragile;
								$_SESSION['IDProvincia']=$_POST['IDProvincia'];
								
								$pass = crypt($pass2, '_J9..rasm');
								/*memorizzazione nel db*/
								$query = "UPDATE Utenti set Email='".$mail."',Password='".$pass."',Fragile='".$fragile."',IDProvincia='".$IDProvincia."' WHERE ID=".$_SESSION['ID'].";";
								$risp = mysqli_query($conn, $query);
								if($risp)
								{
									$message = "".$_SESSION['Cognome']." ".$_SESSION['Nome']." Ti ringraziamo per aver effettuato registrazione alla piattaforma di vaccinazioni della Regione Campania";
									$subject = 'CONFERMA REGISTRAZIONE ADESIONE ALLA PIATTAFORMA Camp-Vax';
									$headers = "From: <campvax@altervista.org>";
									$emailsend =mail ($_SESSION['Email'], $subject, $message, $headers);
									echo('<script>alert("REGISTRAZIONE ANDATA A BUON FINE, RICEVERAI ANCHE UNA EMAIL A CONFERMA DI QUESTO")</script>');
								}
								else
								{
									echo('Errore : '.mysqli_error($conn));
									exit;
								}
							
							}
							else
							{
								echo('<script>alert("Errore: Le 2 password inserite devono coincidere")</script>');
							}
						
					}
					else  //non c'è il codice fiscale dopo aver decriptato tutti quelli disponibili non registrati
					{
						echo('<script>alert("NON ESISTE NESSUNA PERSONA CON QUESTE GENERALITA OPPURE GIA E REGISTRATA A QUESTA PIATTAFORMA")</script>');
					}
				} 
				else
				{
					echo('<script>alert("NON ESISTE NESSUNA PERSONA CON QUESTE GENERALITA OPPURE GIA E REGISTRATA A QUESTA PIATTAFORMA")</script>');
				}
						
			}
			else
			{
				echo('Errore : '.mysqli_error($conn));
				exit;
			}
																	
				
		}
		if ( $_POST['MODE'] == "login" )		/*modalita per il login*/
		{
			$mail = $_POST['mail'];		 	
			$pass = crypt($_POST['pass'], '_J9..rasm');			
			$query = "SELECT * FROM Utenti WHERE Email='".$mail."' AND Password='".$pass."'";
			$risp = mysqli_query($conn, $query);
			if($risp)
			{				/*verifica del risultato della query e conseguente memorizzazione nella variabile di sessione*/
				if(mysqli_num_rows($risp)>0)	
				{	
						$riga = mysqli_fetch_array($risp);
						$_SESSION['Email']=$_POST['mail'];
						$_SESSION['Password']=$_POST['pass'];
						$_SESSION['ID']=$riga['ID'];
						$_SESSION['Nome']=$riga['Nome'];
						$_SESSION['Cognome']=$riga['Cognome'];
						$_SESSION['CF']=$riga['CF'];
						$_SESSION['Data_Nascita']=$riga['Data_Nascita'];
						$_SESSION['Fragile']=$riga['Fragile'];
						$_SESSION['N_dosi']=$riga['N_dosi'];
						$_SESSION['IDProvincia']=$riga['IDProvincia'];
						if($_SESSION['N_dosi']!=100)  //persona gia immunizzata
						{
							header('location: prenotazione.php');
						}
						else
						{
							echo('<script>alert("HAI GIA EFFETTUATO TUTTE LE DOSI DI VACCINO NECESSARIE ALL IMMUNIZZAZIONE E DI CONSEGUENZA NON PUOI PRENOTARNE ALTRI")</script>');
						}
				}
				else
				{ 
					echo('<script>alert("CREDENZIALI ERRATE")</script>');
				}
			}
			else
			{
				echo('<script>alert("CREDENZIALI ERRATE")</script>');
			}
		}
	}
?>
	<HTML>
	<HEAD>
		<TITLE> Registrazione Utente </TITLE>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<style>
body{
	margin:0;
	color:#6a6f8c;
	background:#c8c8c8;
	font:600 16px/18px 'Open Sans',sans-serif;
}
*,:after,:before{box-sizing:border-box}
.clearfix:after,.clearfix:before{content:'';display:table}
.clearfix:after{clear:both;display:block}
a{color:inherit;text-decoration:none}

.login-wrap{
	width:100%;
	height:100%;
	margin:auto;
	max-width:525px;
	min-height:670px;
	position:relative;
	background:url(https://www.fondazioneveronesi.it/uploads/thumbs/2020/12/27/coronavirus-vaccino_thumb_720_480.jpg) no-repeat center;
	box-shadow:0 12px 15px 0 rgba(0,0,0,.24),0 17px 50px 0 rgba(0,0,0,.19);
}
.login-html{
	width:100%;
	height:100%;
	position:absolute;
	padding:90px 70px 50px 70px;
	background:rgba(40,57,101,.9);
}
.login-html .sign-in-htm,
.login-html .sign-up-htm{
	top:0;
	left:0;
	right:0;
	bottom:0;
	position:absolute;
	transform:rotateY(180deg);
	backface-visibility:hidden;
	transition:all .4s linear;
}
.login-html .sign-in,
.login-html .sign-up,
.login-form .group .check{
	display:none;
}
.login-html .tab,
.login-form .group .label,
.login-form .group .button{
	text-transform:uppercase;
}
.login-html .tab{
	font-size:22px;
	margin-right:15px;
	padding-bottom:5px;
	margin:0 15px 10px 0;
	display:inline-block;
	border-bottom:2px solid transparent;
}
.login-html .sign-in:checked + .tab,
.login-html .sign-up:checked + .tab{
	color:#fff;
	border-color:#1161ee;
}
.login-form{
	min-height:345px;
	position:relative;
	perspective:1000px;
	transform-style:preserve-3d;
}
.login-form .group{
	margin-bottom:15px;
}
.login-form .group .label,
.login-form .group .input,
.login-form .group .button{
	width:100%;
	color:#fff;
	display:block;
}
.login-form .group .input,
.login-form .group .button{
	border:none;
	padding:15px 20px;
	border-radius:25px;
	background:rgba(255,255,255,.1);
}
.login-form .group input[data-type="password"]{
	text-security:circle;
	-webkit-text-security:circle;
}
.login-form .group .label{
	color:#aaa;
	font-size:12px;
}
.login-form .group .button{
	background:#1161ee;
}
.login-form .group label .icon{
	width:15px;
	height:15px;
	border-radius:2px;
	position:relative;
	display:inline-block;
	background:rgba(255,255,255,.1);
}
.login-form .group label .icon:before,
.login-form .group label .icon:after{
	content:'';
	width:10px;
	height:2px;
	background:#fff;
	position:absolute;
	transition:all .2s ease-in-out 0s;
}
.login-form .group label .icon:before{
	left:3px;
	width:5px;
	bottom:6px;
	transform:scale(0) rotate(0);
}
.login-form .group label .icon:after{
	top:6px;
	right:0;
	transform:scale(0) rotate(0);
}
.login-form .group .check:checked + label{
	color:#fff;
}
.login-form .group .check:checked + label .icon{
	background:#1161ee;
}
.login-form .group .check:checked + label .icon:before{
	transform:scale(1) rotate(45deg);
}
.login-form .group .check:checked + label .icon:after{
	transform:scale(1) rotate(-45deg);
}
.login-html .sign-in:checked + .tab + .sign-up + .tab + .login-form .sign-in-htm{
	transform:rotate(0);
}
.login-html .sign-up:checked + .tab + .login-form .sign-up-htm{
	transform:rotate(0);
}

.hr{
	height:2px;
	margin:60px 0 50px 0;
	background:rgba(255,255,255,.2);
}
.foot-lnk{
	text-align:center;
}
</style>
	</HEAD>
	<BODY>
		
												
<div class="login-wrap">
	<div class="login-html">
		<input id="tab-1" type="radio" name="tab" class="sign-in" checked><label for="tab-1" class="tab">LOGIN</label>
		<input id="tab-2" type="radio" name="tab" class="sign-up"><label for="tab-2" class="tab">REGISTRAZIONE</label>
		<div class="login-form">
			<div class="sign-in-htm">
						
					<form method="post" action="loginutente.php">
						<div class="login-form">
						<div class="sign-in-htm">
						<div class="group">
							<input type="hidden" name="MODE" value="login" />
							
								<label class="label"><b>Email:</b></label>
								<input class="input" type="text" name="mail" required="" placeholder="Email" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,}">
								<br>
								<br>
								<label class="label"><b>Password:</b></label>
								<input class="input" type="password" name="pass" class="fadeIn third" required="" placeholder="Password">
								<br>
								<br>
						</div>
						<div class="group">
								<button class="button">Login</button>	
						</div>
						<a href="../index.php" style="color: white;">Ritorna alla Home</a><br>
						</div>
						</div>
					</div>
						
					</form>
<div class="sign-up-htm">
						<form method="post" action="loginutente.php">		
				<div class="sign-up-htm">
				<div class="group">
						<input type="hidden" name="MODE" value="reg" />
							<label class="label"><b>Nome:</b></label>
							<input class="input" type="text" name="nome"  required="" placeholder="Nome">

				</div>
				<div class="group">
							<label class="label"><b>Cognome:</b></label>
							<input class="input" type="text" name="cognome" required="" placeholder="Cognome">

				</div>			
				<div class="group">			
							<label class="label"><b>Codice Fiscale</b></label>
							<input class="input" type="text" name="CF" required="" pattern="^[a-zA-Z]{6}[0-9]{2}[a-zA-Z][0-9]{2}[a-zA-Z][0-9]{3}[a-zA-Z]$" placeholder="Codice Fiscale">

				</div>			
				<div class="group">			
							<label class="label">Email:</label>
							<input class="input" type="text" name="mail" required="" placeholder="Email" pattern="[^@]+@[^@]+\.[a-zA-Z]{2,}">

				</div>			
				<div class="group">			
							<label class="label"><b>Password:</b></label>
							<input class="input" type="password" name="pass" required="" placeholder="Password" pattern=".{6,}">

				</div>			
				<div class="group">
							<label class="label"><b>Riscrivi Password:</b></label>
							<input class="input" type="password" name="pass2" required="" placeholder="Conferma Password" pattern=".{6,}">

				</div>			
				<div class="group">
							<label class="label"><b> Risiedo nella Provincia di:&nbsp &nbsp  </b>
							<?php
								$query= "SELECT * FROM Provincia WHERE IDRegione=1;";  /*esempio per regione campania*/
								$risp = mysqli_query($conn, $query);
								if($risp)
								{
									echo('<select name="IDProvincia" id="IDProvincia">');
									while($riga = mysqli_fetch_array($risp))
									{	
										echo('<option value="'.$riga['ID'].'">'.$riga['Nome'].'</option>');
									}
									echo('</select>');
								}
								else
								{
									echo('Errore : '.mysqli_error($conn));
									exit;
								}
							?>
							</label>
					</div>		
					<div class="group">
							<label class="label"> Dichiaro di essere una persona fragile</b>
							<input type="checkbox" name="fragile" value=1></label>
							<label class="label"><a href="fragili.html">&nbsp &nbsp Info</a></label>
					</div>
							
					
					<div class="group">
							<button class="button" >Registrati</button>
							</form>
					</div>
					 <!-- reindirizzamento alla pagina dedicata all'utente-->
			</div>	
</div>			
</div>
</div>
</div>
	</BODY>
	
</HTML>
					 
					
					 
		


<?php
  mysqli_close($conn);
?>