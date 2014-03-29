<?php
	$script['api_google_map'] = array('googlemap-api', 'https://maps.googleapis.com/maps/api/js?key=AIzaSyD0bzuk1i_1CC2CldMPylb88Wj4AizQo-s&libraries=geometry&sensor=true&language=french&region=france');
	$script['scrollbar'] = array('jquery.mousewheel', 'jquery.scrollbar.min', 'becee_search_scrollbar');
	$script['jquery'] = array('jquery.min');
	$scripts = array_merge($script['api_google_map'], $script['scrollbar'], $script['jquery']);
	include('header.php');
?>

	<div class="container-fluid main-content" style="margin:0 15px;height:calc(100% - 60px); text-align: left;">
	    <div class="row" style="height:100%;">
	        <div class="col-md-4" style="height:100%;">
	        	<div class="containers2">
	        	<section class="buisness-contener">
				<?php
					$n = 40;
					for ($i=0; $i <= $n; $i++)
					{
				?>
					<div id="block<?php echo $i ?>" class="restaurant" onclick="document.getElementsById('block<?php echo $i ?>').removeClass('restaurant');">
						<div class="restaurant-info" style="width:70%">
							<div class="title">Le Restaurant Universitaire</div>
							<div class="address">1337, Rue du Place Holder</div>
							<div class="info" style="margin:7px">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam.</div>
						</div>
						<img class="buisness-image img-rounded img-responsive" src="./img/imagetest<?php echo rand(1,7) ?>.jpg" alt="Responsive image"/>
					</div>

				<?php
					}
				?>
				</section>
				</div>
	        </div>
	        <div class="col-md-8 visible-lg visible-md buisness-sheet">
				<div id="map-canvas" /></div>
				<div class="buisness-information">
					<div class="headline">
						<div class="bui-title"><button type="button" class="btn btn-primary btn-sm" style="background-color:#262b2f;height:20px;margin-top:-2px;line-height: 0;margin-right:5px;font-style:normal;">RESTAURANT</button> Restaurant Cap'U</div>
						<div class="price">  <button type="button" class="btn btn-primary btn-sm" style="background-color:#262b2f;height:20px;margin-top:-2px;line-height: 0;margin-right:5px;">PRIX</button> <i class="fa fa-euro"></i> <i class="fa fa-euro"></i> <i class="fa fa-euro"></i></div>
						<div class="categories"><button type="button" class="btn btn-primary btn-sm" style="background-color:#262b2f;height:20px;margin-top:-2px;line-height: 0;margin-right:5px;">CATEGORIE</button> Restaurant à thème</div>
					</div>
					<hr class="fancy-line"/>
					<div class="business-pub">
						<span class="about-bui">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</span>
						<img class="img-rounded" src="./img/imageholder1.jpg" style="width:30%;border: #dddddd solid 2px;float:right; margin:30px 50px" width="420px">
					</div>
					<div class="top-reviews">
						<span class="title-review">Top Reviews</span>
						<hr class="fancy-line" />
						<div class="review">
							<span class="pseudo"><img class="img-rounded" width="70px" src="./img/avatar.png" class="img-responsive" alt="Responsive image"/><br/>Patrick Reuters</span>
							<span class="about-bui" style="font-size: 1em;line-height: 20px;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</span>
						</div>
						<hr class="fancy-line"/>
						<div class="review">
							<span class="pseudo"><img class="img-rounded" width="70px" src="./img/avatar.png" class="img-responsive" alt="Responsive image"/><br/>Patrick Reuters</span>
							<span class="about-bui" style="font-size: 1em;line-height: 20px;">Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum</span>
						</div>
					</div><!-- end top review -->
				</div><!-- end buisness-information-->
	        </div>
	    </div>
	</div>
<?php
	include('footer.php')
?>