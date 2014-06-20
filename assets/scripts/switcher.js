/*-----------------------------------------------------------------------------------
/* Styles Switcher
-----------------------------------------------------------------------------------*/

window.console = window.console || (function(){
	var c = {}; c.log = c.warn = c.debug = c.info = c.error = c.time = c.dir = c.profile = c.clear = c.exception = c.trace = c.assert = function(){};
	return c;
})();


jQuery(document).ready(function($) {		

		$("#style-switcher h2 a").click(function(e){
			e.preventDefault();
			var div = $("#style-switcher");
			console.log(div.css("left"));
			if (div.css("left") === "-206px") {
				$("#style-switcher").animate({
					left: "0px"
				}); 
			} else {
				$("#style-switcher").animate({
					left: "-206px"
				});
			}
		});

		// Home Style
	   $("#home-style").change(function(e){
			if( $(this).val() == 1){
				window.location.href = "index_image.html";
				
			} else if( $(this).val() == 2){
				window.location.href = "index_slider.html";
				
			} else if( $(this).val() == 3){
				window.location.href = "index_video.html";
				
			} else if( $(this).val() == 4){
				window.location.href = "index_color.html";
			
			} else if( $(this).val() == 5){
				window.location.href = "index_pattern.html";
			
			} else if( $(this).val() == 6){
				window.location.href = "index_animated.html";
				
			}
		});
			
	});