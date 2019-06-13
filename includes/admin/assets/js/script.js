jQuery( document ).ready( function( $ ) {

	$( '#mxaltyw_form_update' ).on( 'submit', function( e ){

		e.preventDefault();

		var nonce = $( this ).find( '#mxaltyw_wpnonce' ).val();

		var link_root = $( '#mxaltyw_link_root' ).val();

		var data = {

			'action': 'mxaltyw_update_option',
			'nonce': nonce,
			'mxaltyw_link_root': link_root

		};

		jQuery.post( ajaxurl, data, function( response ){

			alert( 'Value updated.' );

		} );

	} );

} );