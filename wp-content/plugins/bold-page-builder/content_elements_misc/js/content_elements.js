(function( $ ) {
	$( document ).ready(function() {

		// slick slider
		$( '.slick-slider' ).slick();
		$( '.slick-slider .slick-prev, .slick-slider .slick-next' ).click(function() {
			$( this ).closest( '.slick-slider' ).slick( 'slickPause' );
		});
		// bt_bb_elements.js resets animated class
		$( '.slick-slider' ).on('beforeChange', function(event, slick, currentSlide, nextSlide){
		  $( this ).find( '.slick-slide .animated' ).removeClass( 'animated' );
		  $( this ).find( '.slick-slide[data-slick-index='+nextSlide+'] .animate' ).addClass( 'animated' );
		});

		// tabs
		$( '.bt_bb_tabs li' ).click(function() {
			$( this ).siblings().removeClass( 'on' );
			$( this ).addClass( 'on' );
			$( this ).closest( '.bt_bb_tabs' ).find( '.bt_bb_tab_item' ).removeClass( 'on' );
			$( this ).closest( '.bt_bb_tabs' ).find( '.bt_bb_tab_item' ).eq( $( this ).index() ).addClass( 'on' );
		});
		$( '.bt_bb_tabs' ).each(function() {
			$( this ).find( 'li' ).first().click();
		});

		// parallax
		if ( $( '.bt_bb_parallax' ).length > 0 ) {

			window.bt_bb_raf_lock = false;

			$( window ).on( 'mousewheel', function( e ) {

			});

			$( window ).on( 'scroll', function() {
				if ( ! window.bt_bb_raf_lock ) {
					window.bt_bb_raf_lock = true;
					bt_bb_requestAnimFrame( bt_bb_raf_loop );
				}
			});
			
			$( window ).on( 'load', function() {
				bt_bb_requestAnimFrame( bt_bb_raf_loop );
			});

			window.bt_bb_requestAnimFrame = function() {
				return (
					window.requestAnimationFrame       ||
					window.webkitRequestAnimationFrame ||
					window.mozRequestAnimationFrame    ||
					window.oRequestAnimationFrame      ||
					window.msRequestAnimationFrame     ||
					function( callback ) {
						window.setTimeout( callback, 1000 / 60 );
					}
				);
			}();

			bt_bb_raf_loop = function() {
				var win_h = window.innerHeight;
				$( '.bt_bb_parallax' ).each(function() {
					var bounds = this.getBoundingClientRect();
					if ( bounds.top < win_h && bounds.bottom > 0 ) {
						var speed = $( this ).data( 'parallax' ) + 0.0001;
						var offset = 0;
						if ( win_h > 1024 ) offset = parseFloat( $( this ).data( 'parallax-offset' ) );
						var ypos = ( bounds.top ) * speed;
						$( this )[0].style.backgroundPosition = '50% ' + ( ypos + offset ) + 'px';
					}

				});

				window.bt_bb_raf_lock = false;

			}

			bt_bb_requestAnimFrame( bt_bb_raf_loop );	

		}

	});

}( jQuery ));

// google maps
function bt_bb_gmap_init( map_id, zoom, custom_style ) {

	var myLatLng = new google.maps.LatLng( 0, 0 );
	var mapOptions = {
		zoom: zoom,
		center: myLatLng,
		scrollwheel: false,
		scaleControl:true,
		zoomControl: true,
		zoomControlOptions: {
			style: google.maps.ZoomControlStyle.SMALL,
			position: google.maps.ControlPosition.RIGHT_CENTER
		},
		streetViewControl: true,
		mapTypeControl: true
	}
	var map = new google.maps.Map( document.getElementById( map_id ), mapOptions );

	if ( custom_style != '' ) {
		
		var style_array = [];
		
		if ( custom_style != '' ) {
			style_array = JSON.parse( atob( custom_style ) );
		}
		
		var customMapType = new google.maps.StyledMapType( style_array, {
			name: 'Custom Style'
		});

		var customMapTypeId = 'custom_style';
		map.mapTypes.set( customMapTypeId, customMapType );
		map.setMapTypeId( customMapTypeId );
	}

	var n = 0;
	
	var locations = jQuery( '#' + map_id ).parent().find( '.bt_bb_google_maps_location' );
	
	locations.each(function() {
		
		var lat = jQuery( this ).data( 'lat' );
		var lng = jQuery( this ).data( 'lng' );
		var icon = jQuery( this ).data( 'icon' );

		var myLatLng = new google.maps.LatLng( lat, lng );
		var marker = new google.maps.Marker({
			position: myLatLng,
			map: map,
			icon: icon,
			count: n
		});
		
		if ( n == 0 ) {
			map.setCenter( myLatLng );
		}
		
		locations.eq( 0 ).addClass( 'bt_bb_google_maps_location_show' );
		
		marker.addListener( 'click', function() {
			//map.setZoom( zoom );
			//map.setCenter( marker.getPosition() );
			
			locations.removeClass( 'bt_bb_google_maps_location_show' );
			locations.eq( this.count ).addClass( 'bt_bb_google_maps_location_show' );
		});
		
		n++;
	});
	
}