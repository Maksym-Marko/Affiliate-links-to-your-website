jQuery( document ).ready( function ( $ ) {

	// Bulk actions
	$( '#mxalfwp_custom_talbe_form' ).on( 'submit', function ( e ) {

		e.preventDefault();

		var nonce = $( this ).find( '#_wpnonce' ).val();

		var bulk_action = $( this ).find( '#bulk-action-selector-top' ).val()

		if( bulk_action !== '-1') {
			
			var ids = []
			$( '.mxalfwp_bulk_input' ).each( function( index, element ) {
				if( $( element ).is(':checked') ) {
					ids.push( $( element ).val() )
				}
			} );

			if( ids.length > 0 ) {

				var data = {
					'action': 'mxalfwp_bulk_actions',
					'nonce': nonce,
					'bulk_action': bulk_action,
					'ids': ids
				}
	
				jQuery.post( mxalfwp_admin_localize.ajaxurl, data, function( response ) {

					location.reload()
		
				} );

			}

		}
	
	} );

	// Create table item
	$( '#mxalfwp_form_create_table_item' ).on( 'submit', function ( e ) {

		e.preventDefault();

		var nonce = $( this ).find( '#mxalfwp_wpnonce' ).val();

		var title = $( '#mxalfwp_title' ).val();
		var description = $( '#mxalfwp_mx_description' ).val();

		var data = {

			'action': 'mxalfwp_create_item',
			'nonce': nonce,
			'title': title,
			'description': description

		};

		if( title == '' || description == '' ) {

			alert( 'Fill in all fields' )

			return;

		}

		jQuery.post( mxalfwp_admin_localize.ajaxurl, data, function( response ) {

			if( response === '1' ) {
				window.location.href = mxalfwp_admin_localize.main_page
			}
			alert( 'Created!' );

		} );
	
	} );

	// Edit table item
	$( '#mxalfwp_form_update' ).on( 'submit', function ( e ) {

		e.preventDefault();

		var nonce = $( this ).find( '#mxalfwp_wpnonce' ).val();

		var id = $( '#mxalfwp_id' ).val();
		var title = $( '#mxalfwp_title' ).val();
		var description = $( '#mxalfwp_mx_description' ).val();

		var data = {

			'action': 'mxalfwp_update',
			'nonce': nonce,
			'id': id,
			'title': title,
			'description': description

		};

		if( id == '' || title == '' || description == '' ) {

			alert( 'Fill in all fields' )

			return;

		}

		jQuery.post( mxalfwp_admin_localize.ajaxurl, data, function( response ) {

			// console.log( response );
			alert( 'Updated!' );

		} );

	} );

} );