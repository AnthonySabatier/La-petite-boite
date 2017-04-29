(function( $ ) {
	$( '.bt_bb_accordion_item_title' ).click(function() {
		$( this ).closest( '.bt_bb_accordion' ).find( '.bt_bb_accordion_item' ).removeClass( 'on' );
		$( this ).closest( '.bt_bb_accordion_item' ).addClass( 'on' );
	});
	$( '.bt_bb_accordion' ).each(function() {
		$( this ).find( '.bt_bb_accordion_item_title' ).first().click();
	});
})( jQuery );