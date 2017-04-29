(function( $ ) {

	function bt_bb_video_background() {
		$( '.bt_bb_section.video' ).each(function() {
			var video = $( this ).find( 'video' );
			var w = $( this ).outerWidth();
			var h = $( this ).outerHeight();
			if ( w / h > 16 / 9 ) {
				video.css( 'width', '105%' );
				video.css( 'height', '' );
			} else {
				video.css( 'width', '' );
				video.css( 'height', '105%' );
			}
		});
	}

	window.bt_bb_video_callback = function( v ) {
		$( v ).parent().addClass( 'video_on' );
	}

	function bt_bb_animate_elements() {
		var $elems = $( '.animate:not(.animated)' );
		$elems.each(function() {
			var $elm = $( this );
			if ( $elm.isOnScreen() ) {
				$elm.addClass( 'animated' );
			}
		});
	}

	$( document ).ready(function () {
		bt_bb_video_background();
		var googleMapSelector = "iframe[src*=\"google.com/maps\"]";
		if ( $( googleMapSelector ).length > 0 ) {
			$( googleMapSelector ).scrollprevent().start();
		}
	});

	$( window ).on( 'resize', function( e ) {		
		bt_bb_video_background();
	});

	$( window ).on( 'scroll', function() {
		bt_bb_animate_elements();
	});

	$( window ).on( 'load', function() {
		bt_bb_animate_elements();	
	});

	$.fn.isOnScreen = function() {
		var element = this.get( 0 );
		if ( element == undefined ) return false;
		var bounds = element.getBoundingClientRect();
		return bounds.top + 75 < window.innerHeight && bounds.bottom > 0;
	}

	$( 'a[href*="#"]:not([href="#"])' ).not( '.menu-scroll-down' ).on( 'click', function() { // .menu-scroll-down - 2017 theme
		if ( location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname ) {
			var target = $( this.hash );
			target = target.length ? target : $( '[name=' + this.hash.slice(1) +']' );
				if ( target.length ) {
					$( 'html, body' ).animate({
						scrollTop: target.offset().top
					}, 1000 );
				return false;
			}
		}
	});
	
})( jQuery );