$(function() {

	// $(".fa-deconnect").hover(function(){
		
	// 	$(".deco-moove").addClass( "fa-unlock");
	// 	// $(".deco-moove").removeClass("fa-lock");
	// }else{
	// 	$(".deco-moove").removeClass( "fa-unlock");
	// });


	$('.fa-deconnect').hover(
       function(){ $(".deco-moove").addClass("fa-lock");$(".deco-moove").removeClass("fa-unlock") },
       function(){ $(".deco-moove").removeClass("fa-lock");$(".deco-moove").addClass("fa-unlock") }
	)

	var bool =0;
	$( ".fa-message" ).click(function() {
		if (bool == 0) {
		  	$( "#new-messages" ).css("display","block");
		  	$( "#new-messages-modal" ).css("display","block");
		  	bool =1;
	  	}else{
	  		$( "#new-messages" ).css("display","none");
	  		$( "#new-messages-modal" ).css("display","none");
		  	bool =0;
	  	}
	});
	$( "#new-messages-modal" ).click(function() {
	  		$( "#new-messages" ).css("display","none");
	  		$( "#new-messages-modal" ).css("display","none");
		  	bool =0;
	});



	/*$( ".user-modal" ).hide();
	$( ".user-modal-okay" ).hide();
	$( ".user-modal-clarify" ).hide();
	$( ".user-modal-error" ).hide();



	$( "#bouton-okay-test" ).click(function() {
			$( ".user-modal" ).show();
	  		$( ".user-modal-okay" ).show();
	});
	$( "#bouton-affiner-test" ).click(function() {
			$( ".user-modal" ).show();
	  		$( ".user-modal-clarify" ).show();
	});
	$( "#bouton-error-test" ).click(function() {
			$( ".user-modal" ).show();
	  		$( ".user-modal-error" ).show();
	});



	$(".user-modal").click(function() {
	  		$( ".user-modal-okay" ).hide();
	  		$( ".user-modal-clarify" ).hide();
	  		$( ".user-modal-error" ).hide();
	  		$( ".user-modal" ).hide();
	});
*/







    });