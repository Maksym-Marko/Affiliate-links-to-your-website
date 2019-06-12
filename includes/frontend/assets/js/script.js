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

} );

