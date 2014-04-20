animheader = function(){

	$(window).scroll(function(){
		var $this = $(this),
		pos   = $this.scrollTop();


		if (pos > 300){
			$('header').addClass('menu-small');
			$('.first-section').addClass('pad-top');

		} else {
			$('header').removeClass('menu-small');
			$('.first-section').removeClass('pad-top');
		}
	});
};



$(document).ready(function() {
	animheader();
});




