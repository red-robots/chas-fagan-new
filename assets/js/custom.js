/**
 *	Custom jQuery Scripts
 *	
 *	Developed by: Austin Crane	
 *	Designed by: Austin Crane
 */

jQuery(document).ready(function ($) {
	

	/*
	*
	*	Responsive iFrames
	*
	------------------------------------*/
	var $all_oembed_videos = $("iframe[src*='youtube']");
	
	$all_oembed_videos.each(function() {
	
		$(this).removeAttr('height').removeAttr('width').wrap( "<div class='embed-container'></div>" );
 	
 	});
	
	/*
	*
	*	Flexslider
	*
	------------------------------------*/
	$('.slideshow').flexslider({
		startAt: 0,
		animation: "fade",
		slideshowSpeed: 5000,
		pauseOnHover: false
	}); // end register flexslider
	
	/*
	*
	*	Fancybox 3
	*
	------------------------------------*/
	$('[data-fancybox="images"]').fancybox({
		clickContent : false,
		buttons: [
		    "slideShow",
		    "fullScreen",
		    "thumbs",
		    "close"
		],
		afterLoad : function( instance, current ) {
			var currentImageDiv = current.$content;
			var arrow_buttons = $(".fancybox-navigation button").clone();
			if ( instance.group.length > 1 && current.$content ) {
				currentImageDiv.append(arrow_buttons);
			}
		}
	});

	$('.colorbox').colorbox({
		rel:'gal',
		maxWidth: '95%',
		maxHeight: '90%'
	});

	/*
	*
	*	Isotope with Images Loaded
	*
	------------------------------------*/
	// var $container = $('.masonry').imagesLoaded( function() {
 //  	$container.isotope({
 //    // options
	//  itemSelector: '.item',
	// 	  	masonry: {
	// 			gutter: 0
	// 		}
 // 		 });
	// });

	/*
	*
	*	Smooth Scroll to Anchor
	*
	------------------------------------*/
	$('a[href*="#"]').not('[href="#"]').not('[href="#0"]').click(function(event) {
	    if (
	      location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') 
	      && 
	      location.hostname == this.hostname
	    ) {
	      // Figure out element to scroll to
	      var target = $(this.hash);
	      target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
	      // Does a scroll target exist?
	      if (target.length) {
	        // Only prevent default if animation is actually gonna happen
	        event.preventDefault();
	        $('html, body').animate({
	          scrollTop: target.offset().top
	        }, 1000, function() {
	          // Callback after animation
	          // Must change focus!
	          var $target = $(target);
	          $target.focus();
	          if ($target.is(":focus")) { // Checking if the target was focused
	            return false;
	          } else {
	            $target.attr('tabindex','-1'); // Adding tabindex for elements not focusable
	            $target.focus(); // Set focus again
	          };
	        });
	      }
	    }
	});
	
	
	/*
	*
	*	Equal Heights Divs
	*
	------------------------------------*/
	$('.js-blocks').matchHeight();

	/*
	*
	*	Wow Animation
	*
	------------------------------------*/
	new WOW().init();

	// $(".box-with-link").on("click",function(){
	// 	var url = $(this).attr('data-url');
	// 	window.location.href = url;
	// });

	// $('.hoverGalleryImage').hover(
	// 	function(){
	// 		do_action_gallery( $(this) );
	// 	}, function() {
			
	// 	}
	// );


	$(document).on("click",".hoverGalleryImage",function(e){
		e.preventDefault();
		do_action_gallery( $(this) );
	});

	function do_action_gallery(sThis) {
		var filename = sThis.attr('data-filename');
		var imageSrc = sThis.attr('data-href');
		var append = $('<img src="'+imageSrc+'" alt=""/>');
		var image1 = $("#mainImageFrame1").attr('data-filename');
		$('.hoverGalleryImage').removeClass('active');
		sThis.addClass('active');
		$("#mainImageFrame1").attr('data-filename',filename);
		$("#mainImageFrame1").html(append);
	}

	$(document).on("click","#mainImageFrame1",function(e){
		e.preventDefault();
		var filename = $(this).attr('data-filename');
		$(".gthumbnail").each(function(){
			var thumbname = $(this).attr('data-filename');
			if(thumbname==filename) {
				$(this).trigger('click');
			}
		});
	});

	$(document).on("click",".popUp2",function(e){
		e.preventDefault();
		var picname = $(this).attr('id');
		$('.referlink').each(function(){
			var slug = $(this).attr('data-slug');
			if(slug==picname) {
				$(this).trigger("click");
			}
		});
	});

});// END #####################################    END