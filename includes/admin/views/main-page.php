<div class="mx-main-page-text-wrap">
	
	<h1><?php echo __( 'Affiliate links Settins', 'mxaltyw-domain' ); ?></h1>

	<div class="mx-block_wrap">

		<h2><?php echo __( 'Create Affiliate link', 'mxaltyw-domain' ); ?></h2>

		<form id="mxaltyw_form_update" class="mx-settings" method="post" action="">

			<p><?php echo __( 'Set the link to particular page.', 'mxaltyw-domain' ); ?></p>

			<input name="mxaltyw_link_root" id="mxaltyw_link_root" value="<?php echo esc_url( $data['link_root'] ); ?>" />

			<p>
				<?php echo __( 'Copy this short code and place it on the page where the user can create his own affiliate link:', 'mxaltyw-domain' ); ?> <br>
				<span class="mx-affiliate-link-shortcode">
					[mxaltyw_affiliate_link]
				</span>
			</p>

			<p class="mx-submit_button_wrap">
				<input type="hidden" id="mxaltyw_wpnonce" name="mxaltyw_wpnonce" value="<?php echo wp_create_nonce( 'mxaltyw_nonce_request' ) ;?>" />
				<input class="button-primary" type="submit" name="mxaltyw_submit" value="Create Affiliate Link" />
			</p>

		</form>

	</div>

</div>