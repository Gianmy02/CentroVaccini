<HTML>
	<HEAD>
		<TITLE>Installazione DB</TITLE>
	</HEAD>
	<BODY>
		<?php
												/* Database access data */
		  $filename = 'esame.sql';
		  $mysql_host = 'localhost';
		  $mysql_username = 'root';
		  $mysql_password = '';
												/* Trying to connect to the DB */
			$conn = mysqli_connect($mysql_host, $mysql_username, $mysql_password);
			if(!$conn) 
			{
				echo('Errore : '.mysqli_error($conn));
				exit;
			}
		
		  echo('<h1>Installazione DB </h1>');
		  $templine = '';
												/* Opening the installation setup file */
		  $lines = file($filename);
												/* Reading the installation file */
		  foreach($lines as $lines_num => $line)
		  {
			//echo($lines_num.'['.$line.']<br>'); // decommentare per studio.
												/* Comments are not considered */
			if(substr($line, 0, 2) != '--' && $line != '')
			{
			  $templine .= $line;
			  if(substr(trim($line), -1, 1) == ';')
			  {
											  /* Sending the code as a query so that it can be executed 
												and the DB can be created */
				//echo($templine . '<br>');	// decommentare per studio.
				$risp = mysqli_query($conn, $templine);
				if(!$risp) 
				{
					echo('Errore : '.mysqli_error($conn));
					exit;
				}				
				$templine = '';
			  }
			}
		  }
										  /* Closing connection to DB */
		  mysqli_close($conn);
		  echo('<h1>Fine Installazione DB </h1>');
		?>
	</BODY>
</HTML>