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




// 	$("body:not('#new-messages')").click(function(){
// 		$("#new-messages").css("display","none");
// 		bool =0;
	
// });






    });