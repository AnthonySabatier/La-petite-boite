(function( $ ) {
	
	var bt_bb_resize = function() {
		$( '.bt_bb_masonry_image_grid .bt_bb_grid_item' ).each(function() {
			$( this ).height( Math.floor( $( this ).width() * $( this ).data( 'hw' ) ) );
		});
	}
	
	$( document ).ready(function() {
		
		bt_bb_resize();
		
		$( window ).on( 'resize', function() {
			bt_bb_resize();
		});

		$( '.bt_bb_masonry_image_grid' ).masonry({
			columnWidth: '.bt_bb_grid_sizer',
			itemSelector: '.bt_bb_grid_item',
			gutter: 0,
			percentPosition: true
		});
		
		$( '.bt_bb_masonry_image_grid' ).each(function() {
			$( this ).find( '.bt_bb_grid_item' ).each(function() {
				var img_src = $( this ).data( 'src' );
				var img_src_full = $( this ).data( 'src-full' );
				var title = $( this ).data( 'title' );
				$( this ).html( '<img src="' + img_src + '" data-src-full="' + img_src_full + '" title="' + title + '">' );
			});
		});

		$( '.bt_bb_masonry_image_grid' ).magnificPopup({
			delegate: 'img',
			type: 'image',
			gallery:{
				enabled: true
			},
			callbacks: {
				elementParse: function( item ) { item.src = item.el.data( 'src-full' ); }
			},
			closeBtnInside: false,
			fixedContentPos: false
		});
	});

})( jQuery );