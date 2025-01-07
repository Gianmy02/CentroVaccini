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
		
			header( "location: camp-vax.php" ) ;
		}
		if ( $_GET['MODE'] == "agg" )
		{
			$ndosi = $_GET['ndosi'];
			$vaccino = $_GET['vaccino'];
			$query = "SELECT ID,N_dosi FROM Disponibilita WHERE IDSede=".$_SESSION['IDSede']." AND IDVaccino=".$vaccino.";";
			$risp = mysqli_query($conn, $query);
			if($risp)
			{
				if(mysqli_num_rows($risp)>0)
				{
					$riga = mysqli_fetch_array($risp);
					$dosi=$riga['N_dosi']+$ndosi;
					$query1= "UPDATE Disponibilita SET N_dosi=".$dosi." WHERE ID=".$riga['ID'].";";
					$risp1 = mysqli_query($conn, $query1);
					if(!$risp1)
					{
						echo('Errore : '.mysqli_error($conn));
						exit;
					}	
				}
				else
				{
					$query1="INSERT INTO Disponibilita (N_dosi,IDVaccino,IDSede) VALUES (".$ndosi.",".$vaccino.",".$_SESSION['IDSede'].");";
					$risp1 = mysqli_query($conn, $query1);
					if(!$risp1)
					{
						echo('Errore : '.mysqli_error($conn));
						exit;
					}	
				}
				echo('<script>alert("DOSI AGGIUNTE")</script>');
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
		<TITLE> AGGIUNTA DOSI </TITLE>
					 			  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>




	<style>
/* class applies to select element itself, not a wrapper element */
.select-css {
  display: block;
  font-size: 16px;
  font-family: sans-serif;
  font-weight: 700;
  color: #444;
  line-height: 1.3;
  padding: .6em 1.4em .5em .8em;
  width: 100%;
  max-width: 100%; /* useful when width is set to anything other than 100% */
  box-sizing: border-box;
  margin: 0;
  border: 1px solid #aaa;
  box-shadow: 0 1px 0 1px rgba(0,0,0,.04);
  border-radius: .5em;
  -moz-appearance: none;
  -webkit-appearance: none;
  appearance: none;
  background-color: #fff;
  /* note: bg image below uses 2 urls. The first is an svg data uri for the arrow icon, and the second is the gradient. 
    for the icon, if you want to change the color, be sure to use `%23` instead of `#`, since it's a url. You can also swap in a different svg icon or an external image reference
    
  */
  background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22%23007CB2%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'),
    linear-gradient(to bottom, #ffffff 0%,#e5e5e5 100%);
  background-repeat: no-repeat, repeat;
  /* arrow icon position (1em from the right, 50% vertical) , then gradient position*/
  background-position: right .7em top 50%, 0 0;
  /* icon size, then gradient */
  background-size: .65em auto, 100%;
}
/* Hide arrow icon in IE browsers */
.select-css::-ms-expand {
  display: none;
}
/* Hover style */
.select-css:hover {
  border-color: #888;
}
/* Focus style */
.select-css:focus {
  border-color: #aaa;
  /* It'd be nice to use -webkit-focus-ring-color here but it doesn't work on box-shadow */
  box-shadow: 0 0 1px 3px rgba(59, 153, 252, .7);
  box-shadow: 0 0 0 3px -moz-mac-focusring;
  color: #222; 
  outline: none;
}

/* Set options to normal weight */
.select-css option {
  font-weight:normal;
}

/* Support for rtl text, explicit support for Arabic and Hebrew */
*[dir="rtl"] .select-css, :root:lang(ar) .select-css, :root:lang(iw) .select-css {
  background-position: left .7em top 50%, 0 0;
  padding: .6em .8em .5em 1.4em;
}

/* Disabled styles */
.select-css:disabled, .select-css[aria-disabled=true] {
  color: graytext;
  background-image: url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg%22%20width%3D%22292.4%22%20height%3D%22292.4%22%3E%3Cpath%20fill%3D%22graytext%22%20d%3D%22M287%2069.4a17.6%2017.6%200%200%200-13-5.4H18.4c-5%200-9.3%201.8-12.9%205.4A17.6%2017.6%200%200%200%200%2082.2c0%205%201.8%209.3%205.4%2012.9l128%20127.9c3.6%203.6%207.8%205.4%2012.8%205.4s9.2-1.8%2012.8-5.4L287%2095c3.5-3.5%205.4-7.8%205.4-12.8%200-5-1.9-9.2-5.5-12.8z%22%2F%3E%3C%2Fsvg%3E'),
    linear-gradient(to bottom, #ffffff 0%,#e5e5e5 100%);
}

.select-css:disabled:hover, .select-css[aria-disabled=true] {
  border-color: #aaa;
}


body {
  margin: 2rem;
}
input[type=number] {
  border: none;
  border-bottom: 2px solid red;
}
input[type=number]:focus {
  background-color: lightblue;
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
					<li><a href="gestionevaccini.php">MENU</a></li>
					<li><a href="modificaPasswordUtente.php?prov=Utente_Sede"> MODIFICA LA PASSWORD DELL'ACCOUNT</a></li>
					<li class="active"><a href="aggiuntadosi.php">AGGIUNGI NUOVE DOSI</a></li>
					<li><a href="gestionevaccini.php?MODE=logout">LOGOUT</a></li>
				  </ul>
				</div>
			  </div>
			</nav>
		
		<h1>AGGIUNGI DOSI</h1>
			<form name="mioform" method="GET" action="aggiuntadosi.php">
				<input type="hidden" name="MODE" value="agg" />
				<label><b>Numero di dosi da aggiungere</b></label><br/>
				<input type="number" name="ndosi" required='' placeholder="Numero dosi" min=1 style="width:30%; height:3%;">
				<br>
				<br>
				<label><b>Vaccino</b></label><br/>
				<select name="vaccino" id="vaccino" class="select-css" style="width:30%;" align="center">
				<?php
					$query="SELECT * FROM Vaccino;";
					$risp = mysqli_query($conn, $query);
					if($risp)
					{
						while($riga = mysqli_fetch_array($risp))
						{
				?>
							<option value="<?php echo $riga['ID'];?>"><?php echo $riga['Nome'];?></option>
				<?php
						}
					}
					else
					{
						echo('Errore : '.mysqli_error($conn));
						exit;
					}
				?>
				</select>
				
				<br>
				<button style="background-color: red; color:white;">Aggiungi</button>	
			</form>
		<p>
	</BODY>
<HTML>