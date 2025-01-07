<HTML>
	<HEAD>
		<TITLE> CAMP-VAX </TITLE>
		  <meta charset="utf-8">
	  <meta name="viewport" content="width=device-width, initial-scale=1">
	  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
	  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
	</HEAD>
	<BODY>
	<?php
		if(isset($_GET['mod']))
		{
			echo '<script>alert("PASSWORD CAMBIATA CON SUCCESSO, RIEFFETTUA IL LOGIN")</script>';
		}
	?>
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
					<li class="active"><a href="index.php">Home</a></li>
					<li><a href="http://campvax.altervista.org/php/loginutente.php">Area Utente</a></li>
					<li><a href="http://campvax.altervista.org/php/loginsede.php">Area Sede</a></li>
					<li><a href="http://campvax.altervista.org/php/loginregione.php">Area Regione</a></li>
				  </ul>
				</div>
			  </div>
			</nav>

			<div class="container">
			<div class="row">
			  <div class="col-sm-8">
				<div id="myCarousel" class="carousel slide" data-ride="carousel">
				  <!-- Indicators -->
				  <ol class="carousel-indicators">
					<li data-target="#myCarousel" data-slide-to="0" class="active"></li>
					<li data-target="#myCarousel" data-slide-to="1"></li>
				  </ol>

				  <!-- Wrapper for slides -->
				  <div class="carousel-inner" role="listbox">
					<div class="item active">
					  <img src="img/logo.jpg" alt="Image">
					  <div class="carousel-caption">
						<h3>L'ITALIA RINASCE COME UN FIORE</h3>
						<p>Vaccinati anche tu!</p>
					  </div>      
					</div>

					<div class="item">
					  <img src="img/infermiera.jpg" alt="Image">
					  <div class="carousel-caption">
						<h3>PRESTA ATTENZIONE A QUELLO CHE FAI</h3>
						<p>Ognuno è chiamato a fare la sua parte!</p>
					  </div>      
					</div>
					
				  </div>

				  <!-- Left and right controls -->
				  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
					<span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
					<span class="sr-only">Previous</span>
				  </a>
				  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
					<span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
					<span class="sr-only">Next</span>
				  </a>
				</div>
			  </div>
			  <div class="col-sm-4">
				<div class="well">
				  <p>IL VACCINO E' L'UNICA ARMA CHE ABBIAMO PER SCONFIGGERE IL COVID</p>
				</div>
				<div class="well">
				   <p>I VACCINI SONO SICURI</p>
				</div>
			  </div>
			</div>
			<hr>
			</div>

			<div class="container text-center">    
			  <h3>SCOPRI QUELLO CHE FACCIAMO</h3>
			  <br>
			  <div class="row">
				<div class="col-sm-3">
				  <iframe width="300" height="150" src="https://www.youtube.com/embed/JSV4spOlyj8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
				  <p>Esempio della Caserma Garibaldi a Caserta</p>
				</div>
				<div class="col-sm-3">  
				</div>
				<div class="col-sm-3">
				  <div class="well">
				  <img src="img/vaccini.jpg" class="img-responsive" style="width:100%;" alt="Image">
				   <p>RICHIEDI IL VACCINO</p>
				  </div>
				</div>
				<div class="col-sm-3">
				  <div class="well">
				  <img src="img/pc.jpg" class="img-responsive" style="width:100%;" alt="Image">
				   <p>TUTTO CON UN SEMPLICE CLICK!</p>
				  </div>
				</div>  
			  </div>
			  <hr>
			</div>

			<div class="container text-center">    
			  <h3>VACCINI DISPONIBILI</h3>
			  <br>
			  <div class="row">
				<div class="col-sm-2">
				</div>
				<div class="col-sm-2"> 
				  <img src="img/pfizer.jpg" class="img-responsive" width="100%" alt="Image">
				  <p>Pfizer</p>    
				</div>
				<div class="col-sm-2"> 
				  <img src="img/moderna.jpg" class="img-responsive" style="width:100%" alt="Image">
				  <p>Moderna</p>
				</div>
				<div class="col-sm-2"> 
				  <img src="img/johnson.png" class="img-responsive" style="width:100%" alt="Image">
				  <p>Johnson & Johnson</p>
				</div> 
				<div class="col-sm-2"> 
				  <img src="img/astrazeneca.png" class="img-responsive" style="width:100%" alt="Image">
				  <p>Astrazeneca</p>
				</div>     
			  </div>
			</div><br>


	</BODY>
</HTML>