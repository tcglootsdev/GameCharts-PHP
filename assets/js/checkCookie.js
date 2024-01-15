$(document).ready(function(){
	//console.log($.cookie("accept_cookie"));
	if (!$.cookie("accept_cookie")) {
		$("#cookie_accept_div").show();
	}
	$("#cookie_accept_div a").on('click', function(){
		//console.log("clicked");
		$.cookie("accept_cookie", true);
		$("#cookie_accept_div").hide();
	});
});
