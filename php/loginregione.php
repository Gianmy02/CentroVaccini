<?php
	session_start() ;
	$mysql_dbname = 'my_campvax';
	$mysql_host = 'localhost';
	$mysql_username = 'root';
	$mysql_password = '';
	$nome='';
	$pass='';
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
	$msg="123456";
	$msg=crypt($msg, '_J9..rasm');			
	if ( isset( $_POST['MODE'] ) )
	{
		if ( $_POST['MODE'] == "login" )		/*modalita per il login*/
		{
			$nome = $_POST['nome'] ;
			$pass = crypt($_POST['pass'], '_J9..rasm');		 
			$query = "SELECT * FROM Utente_Regione WHERE Nome='".$nome."' AND Password='".$pass."';";
			$risp = mysqli_query($conn, $query);
			if($risp)
			{				/*verifica del risultato della query e conseguente memorizzazione nella variabile di sessione*/
				if(mysqli_num_rows($risp)>0)	
				{	
						$riga = mysqli_fetch_array($risp);
						$_SESSION['Password']=$_POST['pass'];
						$_SESSION['ID']=$riga['ID'];
						$_SESSION['Nome']=$riga['Nome'];
						$_SESSION['IDRegione']=$riga['IDRegione'];
						if($riga['Password']==$msg)
						{
								/*primo accesso*/
							header('location: modificaPasswordUtente.php?prov=Utente_Regione&primo=1');
						}
						else
						{
							header('location: menusceltaregione.php');
						}
				}
				else
				{ 
					echo('<script>alert("CREDENZIALI ERRATE")</script>');
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
		<TITLE> LOGIN UTENTE SEDE </TITLE>
		<style>
@import url(https://fonts.googleapis.com/css?family=Open+Sans:300);
* {
  font-family: 'Open Sans', sans-serif;
}

body {
  margin: 0;
  padding: 0;
    overflow: hidden;
  background: #111;
  background-repeat: no-repeat;
}

.signupSection {
  background-repeat: no-repeat;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 800px;
  height: 450px;
  text-align: center;
  display: flex;
  color: white;
}

.info {
  width: 45%;
  height:83%;
  background: rgba(20, 20, 20, .8);
  padding: 30px 0;
  border-right: 5px solid rgba(30, 30, 30, .8);
  h2 {
    padding-top: 30px;
    font-weight: 300;
  }
  p {
    font-size: 18px;
  }
  .icon {
    font-size: 8em;
    padding: 20px 0;
    color: rgba(10, 180, 180, 1);
  }
}

.signupForm {
  width: 70%;
  padding: 30px 0;
    background: rgba(20, 40, 40, .8);
  transition: .2s;
}

.inputFields {
  margin: 15px 0;
  font-size: 16px;
  padding: 10px;
  width: 250px;
  border: 1px solid rgba(10, 180, 180, 1);
  border-top: none;
  border-left: none;
  border-right: none;
    background: rgba(20, 20, 20, .2);
  color: white;
  outline: none;
}

.noBullet {
  list-style-type: none;
  padding: 0;
}

#join-btn {
  border: 1px solid rgba(10, 180, 180, 1);
  background: rgba(20, 20, 20, .6);
  font-size: 18px;
  color: white;
  margin-top: 20px;
  padding: 10px 50px;
  cursor: pointer;
  transition: .4s;
  &:hover {
    background: rgba(20, 20, 20, .8);
    padding: 10px 80px;
  }
}



		</style>
	</HEAD>
	<BODY>
	<div class="signupSection">
	<div class="info">
		<h2>LOGIN REGIONE CAMPANIA</h2>		
		<br><br>
	<p><a href="../index.php" style="color:red;">Ritorna alla Home</a></p>
	</div>	
		
					<form method="post" class="signupForm" action="loginregione.php">
						<h1>ACCEDI</h1>
						<ul class="noBullet">
						<input type="hidden" name="MODE" value="login" />
							<li>
								<label for="username"><b>Nome Account:</b></label>
								<input class="inputFields" type="text" name="nome" required="" placeholder="Nome">
							</li>
							
							<li>
								<label for="password"><b>Password:</b></label>
								<input  class="inputFields" type="password" name="pass" required="" placeholder="Password">
							</li>
							 <li id="center-btn">
								<input type="submit" alt="Join" id="join-btn" value="LOGIN">
							</li>
						</ul>
					</form>
	</div>				
					
					
	</BODY>
	</HTML>