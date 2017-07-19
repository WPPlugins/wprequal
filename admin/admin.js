/**
 * Add color picker WPrequal Admin settings page.
 *
 * @summary   Add color picker.
 *
 * @link      admin/wprequal.js
 * @since     1.0
 * @requires  /wp-includes/js/jquery/jquery.js
 */
 
jQuery( document ).ready( function( $ ) {
    
	$( '.wprequal_colors' ).wpColorPicker();
	
	var wprequalOptions = {
		// a callback to fire when the input is emptied or an invalid color
		clear: function() {},
		// hide the color picker controls on load
		hide: true,
		// show a group of common colors beneath the square
		// or, supply an array of colors to customize further
		palettes: true
	};
 
	$( '.wprequal_colors' ).wpColorPicker( wprequalOptions );
	
	$( '.wprequal_customize_button' ).on( 'click touchstart', function( event ) {
		$( '.wprequal_admin_input' ).each( function() {
			$( this ).removeClass( 'wprequal_red' );
			if ( $( this ).val().length < 1 ) {
				$( this ).addClass( 'wprequal_red' );
				event.preventDefault();
			}
		});
	});
	
} );