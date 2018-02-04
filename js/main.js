/*global $:false */

jQuery(document).ready(function(){'use strict';

	// Sticky Nav
	jQuery(window).on('scroll', function(){'use strict';
		if ( jQuery(window).scrollTop() > 60 ) {
			jQuery('#masthead').addClass('animated fadeInDown sticky');
		} else {
			jQuery('#masthead').removeClass('animated fadeInDown sticky');
		}
	});

	jQuery('.featured-wrap .share-btn').on('click',function(){
		jQuery('.share-btn-pop').slideToggle(500);
	});

	/* -------------------------------------- */
	// 		RTL Support Visual Composer
	/* -------------------------------------- */	
	var delay = 1;
	function themeum_rtl() {
		if( jQuery("html").attr("dir") == 'rtl' ){
			if( jQuery( ".entry-content > div" ).attr( "data-vc-full-width" ) =='true' )	{
				jQuery('.entry-content > div').css({'left':'auto','right':jQuery('.entry-content > div').css('left')});	
			}
		}
	}
	setTimeout( themeum_rtl , delay);

	jQuery( window ).resize(function() {
		setTimeout( themeum_rtl , delay);
	});	

    
    jQuery('.schedule-masonry').masonry({
        itemSelectory: '.eventum-handpick-column'
    });

	//image popup
    jQuery('.plus-icon').magnificPopup({
        type: 'image',
        mainClass: 'mfp-with-zoom',
        zoom: {
            enabled: true,
            duration: 300,
            easing: 'ease-in-out',
            opener: function (openerElement) {
                return openerElement.next('img') ? openerElement : openerElement.find('img');
            }
        },
        gallery: {
            enabled: true,
            navigateByImgClick: true,
            preload: [0, 1]
        }

    });
    jQuery('.plus-icon').on('click', function () {
        jQuery('html').css('overflow', 'inherit');
    });	

	/* --------------------------------------
	*		Shortcode hover color effect 
	*  -------------------------------------- */
	var clr = '';
	var clr_bg = '';
	jQuery(".thm-color").on({
	    mouseenter: function () {
	     	clr = jQuery(this).css('color');
			clr_bg = jQuery(this).css('backgroundColor');
			jQuery(this).css("color", jQuery(this).data("hover-color"));
			jQuery(this).css("background-color", jQuery(this).data("hover-bg-color"));
	    },
	    mouseleave: function () {
	        jQuery(this).css("color", clr );
			jQuery(this).css("background-color", clr_bg );
	    }
	});


	//Woocommerce
	jQuery( ".woocart" ).hover(function() {
		jQuery(this).find('.widget_shopping_cart').stop( true, true ).fadeIn();
	}, function() {
		jQuery(this).find('.widget_shopping_cart').stop( true, true ).fadeOut();
	});	



	jQuery('.woocart a').html( jQuery('.woo-cart').html() );

	jQuery('.add_to_cart_button').on('click',function(){'use strict';

			jQuery('.woocart a').html( jQuery('.woo-cart').html() );		    

			var total = 0;
			if( jQuery('.woo-cart-items span.cart-has-products').html() != 0 ){
				if( jQuery('#navigation ul.cart_list').length  > 0 ){
					for ( var i = 1; i <= jQuery('#navigation ul.cart_list').length; i++ ) {
						var total_string = jQuery('#navigation ul.cart_list li:nth-child('+i+') .quantity').text();
						total_string = total_string.substring(-3, total_string.length);
						total_string = total_string.replace('×', '');
						total_string = parseInt( total_string.trim() );
						//alert( total_string );
						if( !isNaN(total_string) ){ total = total_string + total; }
					}
				}
			}
			jQuery('.woo-cart-items span.cart-has-products').html( total+1 );

    });	

	/* --------------------------------------
	*		Google Map Code
	*  -------------------------------------- */
	var wplatitude = document.getElementById('wplatitude');
	if (wplatitude != null){

		jQuery(function($){
			google.maps.event.addDomListener(window, 'load', function(){

			var wplatitude = document.getElementById('wplatitude').innerHTML;
			var wplongitude = document.getElementById('wplongitude').innerHTML;
			var wpmap_color = document.getElementById('wpmap_color').innerHTML;
			var wpaddress = document.getElementById('wpaddress').innerHTML;
			

				
			var mapId = 'gmap';

		      // Get data
		      var zoom = 9;
		      var mousescroll = false;

		      var latlng = new google.maps.LatLng( wplatitude, wplongitude);
		      var mapOptions = {
		      	zoom: zoom,
		      	center: latlng,
		      	disableDefaultUI: true,
		      	scrollwheel: mousescroll
		      };

		      var map = new google.maps.Map(document.getElementById(mapId), mapOptions);
		      var marker = new google.maps.Marker({
		      	position: latlng,
		      	map: map
		      });


		      	var contentString = '<div id="map-info-content">'+wpaddress+'</div>';

		      	var infowindow = new google.maps.InfoWindow({
		      		content: contentString
		      	});

		      	infowindow.open(map, marker);

		      	marker.addListener('click', function() {
		      		infowindow.open(map, marker);
		      	});


		      	map.setMapTypeId(google.maps.MapTypeId['ROADMAP']);

		      //Get colors
		      var fill_color                   = wpmap_color;

		      if(fill_color != '') {
		      	var styles = [
		      	{
		      		"featureType": "water",
		      		"elementType": "geometry",
		      		"stylers": [
		      		{
		      			"color": fill_color
		      		}
		      		]
		      	}
		        ]; // END gmap styles
		    }

		    // Set styles to map
		   map.setOptions({styles: styles});


		});
		});

	}
	//event counter 2
	loopcounter('counter-class');


	jQuery('.cloud-zoom').magnificPopup({
		type: 'image',
		closeOnContentClick: true,
		mainClass: 'mfp-img-mobile',
		image: {
			verticalFit: true
			},
	});








});