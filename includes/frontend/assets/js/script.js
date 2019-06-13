jQuery( document ).ready( function( $ ){

	var mx_affiliate_link_user_token = Cookies.get( 'mx_affiliate_link_user_token' );

	// check $_GET params
	var parts = window.location.search.substr(1).split("&");

	var $_GET = {};

	for (var i = 0; i < parts.length; i++) {

	    var temp = parts[i].split("=");

	    $_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);

	}

	if( $_GET['mxpartnerlink'] !== undefined && $_GET['mxpartnerlink'] === 'true' && $_GET['usertoken'] !== undefined ) {

		// if there is affiliate link
		if( Cookies.get( 'mx_affiliate_link_user_token' ) === undefined ) {

			mx_affiliate_link_user_token = Cookies.set( 'mx_affiliate_link_user_token', $_GET['usertoken'] );

		}

		console.log( mx_affiliate_link_user_token );

	}

	// crate token
	if( $( '#mx_create_affiliate_link_input' ).length ) {

		var mxaltyw_token_value = '';

		// generate token
		$( '#mx_create_affiliate_link_button' ).on( 'click', function( e ) {

			e.preventDefault();

			var nonce = $( '#mxaltyw_nonce_user_token' ).val();

			var _input = $( '#mx_create_affiliate_link_input' );

			var show_to_user_field = $( '.mx_create_affiliate_link_show_to_user' );

			var save_button = $( '#mx_save_affiliate_link_button' );

			var generate_token_button = $( '#mx_create_affiliate_link_button' );

			var data = {

				'action': 'mxaltyw_generate_user_token',
				'nonce': nonce

			};

			jQuery.post( mxaltyw_frontend_script_localize.ajaxurl, data, function( response ) {

				// save token to input
				_input.val( response );

				// save token to variable
				mxaltyw_token_value = response;

				// insert token
				show_to_user_field.find( 'span' ).text( response );

				// show link
				show_to_user_field.show( 'slow' );

				// hide generate token button
				generate_token_button.hide( 'slow' );

				// show save button
				save_button.show( 'slow' );


			} );

		} );

		// save token
		$( '#mx_save_affiliate_link_button' ).on( 'click', function( e ) {

			e.preventDefault();

			var nonce = $( '#mxaltyw_nonce_user_token' ).val();

			if( mxaltyw_token_value !== '' ) {

				var data = {

				'action': 'mxaltyw_save_user_token',
				'nonce': nonce,
				'token': mxaltyw_token_value

			};

			jQuery.post( mxaltyw_frontend_script_localize.ajaxurl, data, function( response ) {

				$( '#mx_save_affiliate_link_button' ).hide( 'slow' );

				$( '.mx_create_affiliate_link_success' ).show( 'slow' );

			} );

			}

		} );

	}
	
} );

