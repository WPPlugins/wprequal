/**
 * On button click, create a url encoded string from input fields.
 * Provide string PHP using ajax. PHP will procees data and return a response.
 * The response will be displayed in html upon successful ajax request.
 *
 * @summary   Process ajax request and display response.
 *
 * @link      https://code.wprequal.com/js/wprequal.js
 * @since     1.0
 * @requires  /wp-includes/js/jquery/jquery.js
 */
jQuery( document ).ready( function( $ ) {
	
	$( '.wprequal_button' ).on( 'click touchstart', function() {
		
		var ID = this.id;
		var next = $( this ).attr( 'next' );
		
		$( this ).addClass( 'wprequal_data' );
		
		var error = false;
		$( '.' + ID + '_input' ).each( function() {
			error = wprequalValidate ( this );
			if ( error === true ) return ( false );
		});
		
		if ( error === true ) return;
		
		$( '#current_' + ID ).addClass( 'fadeOut' );
		setTimeout( function() { 
			$( '#' + next ).addClass( 'fadeIn' );
			$( '#' + next ).removeClass( 'wprequal_hide' );
			$( '#current_' + ID ).addClass( 'wprequal_hide' );
			}, 
			1000
		);
		
		if (ID == 'wprequal_submit') {
			
			var string = '';
		
			$( '.wprequal_data' ).each( function() {
				var value = $( this ).val();
				if ( value.length > 0 ) {
					string += this.id + '=' + encodeURIComponent( value ) + '&';
				}
			 });
		
			jQuery.ajax ( {
				url : wprequal_url.ajax_url,
				type : 'post', 
				data : {
					action : 'wprequal_ajax_process',
					string : string,
					wprequal_ajax_nonce : wprequal_url.nonce,
				}
			} );
		}
		
	} );
	
	$( '.wprequal_back' ).on( 'click touchstart', function() {
		
		var back_id = '#' + $( this ).attr( 'back_id' );
		var current_id = '#' + $( this ).attr( 'current_id' );
		
		$( current_id ).addClass( 'fadeOut' ).to;
		setTimeout( function() { 
			$( back_id ).addClass( 'fadeIn' );
			$( back_id ).removeClass( 'fadeOut' );
			$( back_id ).removeClass( 'wprequal_hide' );
			$( current_id ).addClass( 'wprequal_hide' );
			$( current_id ).removeClass( 'fadeOut' );
			}, 
			1000
		);  
	} );
		
	function wprequalValidate ( input ){
		//Remove all previous error messages
		$( input ).removeClass( 'wprequal_error' );
			
		//Current and Home Search Zip Codes
		if ( input.id === 'wprequal_current_zipcode' || input.id === 'wprequal_search_zipcode' ) {
			if ( $( input ).val().length != 5 || ! $.isNumeric( $( input ).val() ) ) {
				$( input ).addClass( 'wprequal_error' );
				$( input ).attr( 'placeholder', 'Invalid format (i.e. 12345)' );
				return ( true );
			}
		}
			
		//Price
		if ( input.id === 'wprequal_price'  ) {
			if ( ! ValidatePrice( $( input ).val() ) ) {
				$( input ).addClass( 'wprequal_error' );
				$( input ).attr( 'placeholder', 'Invalid Format' );
				return ( true );
			}
		}
		//First and Last Name
		if ( input.id === 'wprequal_first_name' || input.id === 'wprequal_last_name' ) {
			if ( ! ValidateName( $( input ).val() ) ) {
				$( input ).addClass( 'wprequal_error' );
				$( input ).attr( 'placeholder', 'Invalid Name' );
				return ( true );
			}
		}
			
		//Email
		if ( input.id === 'wprequal_email'  ) {
			if ( ! ValidateEmail( $( input ).val() ) ) {
				$( input ).addClass( 'wprequal_error' );
				$( input ).attr( 'placeholder', 'Invalid Email (i.e. you@email.com)' );
				return ( true );
			}
		}
			
		//Phone
		if ( input.id === 'wprequal_phone'  ) {
			if ( ! phonenumber( $( input ).val() ) ) {
				$( input ).addClass( 'wprequal_error' );
				$( input ).attr( 'placeholder', 'Invalid Phone (i.e. 123 456-7890)' );
				return ( true );
			}
		}	
			
		if ( input.id === 'wprequal_subscribe'  ) {
			if( ! $( input ).is( ':checked' ) ) {
				alert('You must agree to the terms first.');
				return ( true );
			}
		}
			
		return false;
	}
		
	function ValidatePrice( input ) {  
		if ( /^(\d*([.,](?=\d{3}))?\d+)+((?!\2)[.,]\d\d)?$/.test( input ) ) {  
			return ( true )  
		}  
		return ( false )  
	}
		
	function ValidateName( input ) {  
		if ( /^[A-Za-z\s]+$/.test( input ) ) {  
			return ( true )  
		}
		return ( false )  
	}
		
	function ValidateEmail( input ) {  
		if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test( input ) ) {  
			return ( true )  
		}  
		return ( false )  
	}
		
	function phonenumber( input ) {  
		var phoneno = /^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/;  
		if ( ( input.match( phoneno ) ) ) {  
			return ( true );  
		}   
		return ( false );  
	}
	
} );