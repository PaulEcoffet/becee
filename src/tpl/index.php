<?php
	include('header.php')
?>
	<div class="container-fluid main-content" style="margin:0 15px; text-align: left;">
	    <div class="row">
	        <div class="col-md-12">
	        	<div id="head" class="container">
	        		<div style="text-align:center;">
	        			<center><img src="./img/leitmotiv.png"/ width="740" style="display:block;margin:auto;margin-top:150px;"></center><br/>
	        					<ul id="nav-tools" style="width:425px;margin:auto;float:none">
			<li>
				<a class="btn-categories btn-default" href="#">
				    <i class="fa fa-beer fa-lg" style="vertical-align: middle;"></i>
				    <span>
				        Bars
				    </span>
				</a>
			</li>
			<li>
				<a class="btn-categories btn-default" href="#">
				    <i class="fa fa-coffee fa-lg"></i>
				    <span>
				        Cafés
				    </span>
				</a>
			</li>
			<li>
				<a class="btn-categories btn-default" href="#">
				    <i class="fa fa-cutlery fa-lg"></i>
				    <span>
				        Restaurants
				    </span>
				</a>
			</li>
			<li>
				<a class="btn-categories btn-default" href="#">
				    <i class="fa fa-gear fa-lg" ></i>
				    <span>
				        Custom
				    </span>
				</a>
			</li>
		</ul>
	        		</div>
	        	</div>
	        </div><!-- end col-md-12-->
	    </div><!-- end row -->
		<div class="container-fluid" id="center-row">
		    <div class="row">
		        <div id="main" class="col-md-12">
		        	<div class="panel">
					    <div class="panel-heading"></div>
					    <div class="panel-body">
						    <span style="line-height: 30px; vertical-align:middle;">Inscrivez votre entreprise gratuitement et rapidement !</span>
						    <a href="#" class="btn btn-primary btn-large" style="display:inline-block;float:right">
						        Inscription PRO
						    </a>
					    </div>
					</div><!-- end panel panel-info -->
					<span style="font-family: 'Roboto';	font-size: 2em;font-weight: 800;">Restaurants populaires</span> (Bordeaux)<br/>
					<?php
						$n = 6;
						for ($i=0; $i < $n; $i++)
						{
					?>
					<div class="row">
						<?php
							$m = 2;
							for ($j=0; $j < $m; $j++)
							{
						?>
						<div class="col-md-6 home-business">
							<a href="./search.php"><img src="./img/home-holder<?php echo rand(1,4) ?>.png" class="img-responsive center" alt="Responsive image"></a>
							<div style="height:60px;border:2px dotted rgba(0,0,0,0.08);border-top:0;width:100%;text-align:left;padding:5px; max-width:396px; margin:auto">
								<span style="	font-family: 'Roboto';	font-size: 1.2em;	font-weight: 800;">Restaurant Cap'U</span><br/>
								<span style="	font-family: 'Roboto';	font-size: 1em;	font-weight: 400;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</span>
							</div>
						</div>
					<?php
						}
					?>
					</div>
				<?php
					}
				?>
		        </div><!-- end col-md-12-->
		    </div><!-- end row -->
		</div>
	</div><!-- end container-fluid main-content -->

<?php
	include('footer.php')
?>