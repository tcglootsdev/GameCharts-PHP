<nav class="navbar navbar-expand-lg navbar-light bg-gradient-green fixed-top">
	<a href="http://gamecharts.local"><img src="http://gamecharts.local/assets/images/logo-1.png" class="logoGameCharts" alt="Game Charts logo" width="235px" height="60px"></a>
	<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
	<span class="navbar-toggler-icon"></span>
	</button>

	<div class="collapse navbar-collapse" id="navbarTogglerDemo02">
		<ul class="navbar-nav mr-auto mt-2 mt-lg-0">
			<li class="nav-item active">
			<span class="nav-link game-subject">Realtime game analysis and charts</span>
			</li>
		</ul>
		<ul class="list-unstyled topbar-nav navbar-search">
			<li class="hide-phone app-search">
				<form role="search" class="">
					<input type="text" id="searchBox" placeholder="Search..." class="form-control bg-light-gray"/>
					<i id="searchBoxIcon" class="fas fa-search"></i>
				</form>
				<script type="text/javascript">
				   window.onload = function() {
				      document.getElementById("searchBoxIcon").onclick = function() {
				         var value = document.getElementById("searchBox").value;
		
					 if (value.length >= 2){
						window.location.href = "http://gamecharts.local/search/" + value.toLowerCase ();
					 }
				      };
				   };
				</script>
				<div id="searched_game">
					<div class="item"> Not Games Found </div>
				</div>
			</li>
		</ul>
	</div>
</nav>
