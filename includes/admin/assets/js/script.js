jQuery(document).ready(function ($) {

	let mxalfwpSubmitKey = true;

	$('.mxalfwp-block-user-form').on('submit', function (e) {

		e.preventDefault();

		if (confirm(mxalfwp_admin_localize.translation.text_8)) {

			if (!mxalfwpSubmitKey) return;

			let userKey = $(this).find('.mxalfwp_user_key').val();
			let userId = $(this).find('.mxalfwp_user_id').val();
			let status = $(this).find('.mxalfwp_user_status').val();

			let data = {
				'action': 'mxalfwp_block_partner',
				'nonce': mxalfwp_admin_localize.nonce,
				'user_key': userKey,
				'user_id': userId,
				'status': status
			};

			mxalfwpSubmitKey = false;

			jQuery.post(mxalfwp_admin_localize.ajax_url, data, function (response) {

				if (mxalfwpIsJSON(response)) {

					alert(JSON.parse(response).message);

				}

				mxalfwpSubmitKey = true;

				location.reload();

			});

		}

	});

	// Pay to a partner
	$('.mxalfwp-payment-form').on('submit', function (e) {

		e.preventDefault();

		if (!mxalfwpSubmitKey) return;

		let amount = Number($(this).find('#mxalfwp_payment_partner').val());
		amount = parseFloat(amount).toFixed(2);

		let userKey = $(this).find('.mxalfwp_user_key').val();
		let userId = $(this).find('.mxalfwp_user_id').val();

		let data = {
			'action': 'mxalfwp_pay_partner',
			'nonce': mxalfwp_admin_localize.nonce,
			'amount': amount,
			'user_key': userKey,
			'user_id': userId
		};

		mxalfwpSubmitKey = false;

		jQuery.post(mxalfwp_admin_localize.ajax_url, data, function (response) {

			if (mxalfwpIsJSON(response)) {

				alert(JSON.parse(response).message);

				location.reload();

			}

			mxalfwpSubmitKey = true;

		});

	});

	// Bulk actions
	$('#mxalfwp_custom_talbe_form').on('submit', function (e) {

		e.preventDefault();

		var nonce = $(this).find('#_wpnonce').val();

		var bulk_action = $(this).find('#bulk-action-selector-top').val()

		if (bulk_action !== '-1') {

			var ids = []
			$('.mxalfwp_bulk_input').each(function (index, element) {
				if ($(element).is(':checked')) {
					ids.push($(element).val())
				}
			});

			if (ids.length > 0) {

				var data = {
					'action': 'mxalfwp_bulk_actions',
					'nonce': nonce,
					'bulk_action': bulk_action,
					'ids': ids
				}

				jQuery.post(mxalfwp_admin_localize.ajax_url, data, function (response) {

					location.reload();

				});

			}

		}

	});

});

function mxalfwpIsJSON(str) {
	try {
		JSON.parse(str);
	} catch (e) {
		return false;
	}
	return true;
}


// Vue 2
if (document.getElementById('mxalfwp_admin_settings')) {

	if (typeof Vue === 'undefined') {
		console.warn(mxalfwp_admin_localize.translation.text_1);
	} else {

		/**
		 * Components 
		 * */
		// Form
		Vue.component('mxalfwp_admin_settings_form', {
			props: {
				translation: {
					type: Object,
					required: true
				},
				percent: {
					type: Number,
					required: true
				},
				currency: {
					type: String,
					required: true
				},
				savesettings: {
					type: Function,
					required: true
				},
				progress: {
					type: Boolean,
					required: true
				}
			},
			template: `
			<form @submit.prevent="saveChanges">

				<!-- fields -->
				<div class="mxalfwp-row mxalfwp-justify-content-center mxalfwp-mt-15">
		
					<!-- <div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
						<div class="mxalfwp-white-box mxalfwp-analytics-info">
							<h3 class="mxalfwp-box-title">
								<?php echo __('Percent Per Buy', 'mxalfwp-domain'); ?>
							</h3>
							<ul class="mxalfwp-list-inline mxalfwp-two-part mxalfwp-d-flex mxalfwp-align-items-center mxalfwp-mb-0">
								<li class="mxalfwp-ms-auto">
									<span class="mxalfwp-counter mxalfwp-text-success">659</span>
								</li>
							</ul>
						</div>
					</div> -->
			
					<!-- percent -->
					<div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
						<div class="mxalfwp-white-box mxalfwp-analytics-info">
							<h3 class="mxalfwp-box-title">
								{{translation.text_2}}
							</h3>
							<div>
								<input
									type="number"
									step="0.1"
									name="mxalfwp_default_percent"
									class="mxalfwp-input"
									v-model="default_percent"
								/>
								<small>{{translation.text_3}}</small>
							</div>

						</div>
					</div>

					<!-- currency sign -->
					<div class="mxalfwp-col-lg-4 mxalfwp-col-md-12">
						<div class="mxalfwp-white-box mxalfwp-analytics-info">
							<h3 class="mxalfwp-box-title">
								{{translation.text_9}}
							</h3>
							<div>
								<input
									type="text"
									name="mxalfwp_default_currency_sign"
									class="mxalfwp-input"
									v-model="default_currency_sign"
								/>
								<small>{{translation.text_10}}</small>
							</div>							

						</div>
					</div>

				</div>

				<!-- errors -->
				<div class="mxalfwp-mt-15 mxalfwp-text-center">

					<!-- Errors -->
					<ul
						v-if="errors.length>0 && attempt"
						class="mxalfwp-errors"
					>
						<li
							v-for="(error, index) in errors"
							:key="index"
							class="mxalfwp-error"
						>
							{{ error }}
						</li>
					</ul>

				</div>

				<div class="mxalfwp-mt-15 mxalfwp-text-center">
					
					<button 
						class="
						mxalfwp-ms-auto
						mxalfwp-btn mxalfwp-btn-primary
						mxalfwp-pull-right
						mxalfwp-ms-3
						mxalfwp-hidden-xs mxalfwp-hidden-sm
						mxalfwp-waves-effect mxalfwp-waves-light
						mxalfwp-text-white"
						:class="[progress ? 'mxalfwp-disabled' : '']"
						:disabled="progress"
					>{{translation.text_4}}</button>

				</div>				
		
			</form>
			`,
			data() {
				return {
					default_percent: 0,
					default_currency_sign: '$',
					attempt: false,
					errors: []
				}
			},
			methods: {
				isNumber(str) {
					if (isNaN(str)) {
						return false;
					}
					return true;
				},
				isInRange(num) {
					if (num > 99 || num < 0.1) {
						return false;
					}
					return true;
				},
				isInLength(str) {
					if (str.length < 1 || str.length > 5) {
						return false;
					}
					return true;
				},
				saveChanges() {

					this.attempt = true;

					this.formChecking();

					if (!this.isNumber(this.default_percent)) {
						return;
					}

					if (!this.isInRange(this.default_percent)) {
						return;
					}

					if(!this.isInLength(this.default_currency_sign)) {
						return;
					}

					this.savesettings({ 
						'percent': this.default_percent,
						'currency':this.default_currency_sign
					});

				},
				formChecking() {
					this.errors = [];

					if (!this.isNumber(this.default_percent)) {
						this.errors.push(this.translation.text_5);
					}

					if (!this.isInRange(this.default_percent)) {
						this.errors.push(this.translation.text_6);
					}

					if(!this.isInLength(this.default_currency_sign)) {
						this.errors.push(this.translation.text_11);
					}

				}
			},
			watch: {
				default_percent() {
					this.formChecking();
				},
				percent() {
					this.default_percent = this.percent;
				},
				default_currency_sign() {
					this.formChecking();
				},
				currency() {
					this.default_currency_sign = this.currency;
				}
			}
		})

		/**
		 *  Base object
		 * */
		const app = new Vue({
			el: '#mxalfwp_admin_settings',
			data: {
				translation: {},
				ajaxdata: {},
				percent: 0,
				currency: '$',
				progress: false
			},
			methods: {
				saveSettings(obj) {

					this.progress = true;

					const self = this;

					const xmlhttp = new XMLHttpRequest();

					xmlhttp.open('POST', this.ajaxdata.ajax_url);

					xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");

					xmlhttp.onload = function () {

						if (this.status === 200) {

							const res = JSON.parse(this.response);

							if (res.status === 'success') {
								alert(res.message);
							} else {
								alert(res.message);
							}

						} else {
							self.errors.push(translation.text_7);
						}

						self.progress = false;

					}

					const data = {
						action: 'mxalfwp_save_settings',
						nonce: this.ajaxdata.nonce,
						percent: obj.percent,
						currency: obj.currency
					}

					xmlhttp.send(this.toQueryString(data));

				},
				toQueryString(obj) {
					var str = [];
					for (var p in obj)
						if (obj.hasOwnProperty(p)) {
							str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
						}
					return str.join("&");
				}
			},
			mounted() {

				// translation
				if (mxalfwp_admin_localize.translation) {
					this.translation = mxalfwp_admin_localize.translation
				}

				// get default percent
				if (mxalfwp_admin_localize.percent) {
					this.percent = parseFloat(mxalfwp_admin_localize.percent)
				}

				// get default currency sign
				if (mxalfwp_admin_localize.currency) {
					this.currency = mxalfwp_admin_localize.currency
				}

				// ajax url
				if (mxalfwp_admin_localize.ajax_url) {
					this.ajaxdata.ajax_url = mxalfwp_admin_localize.ajax_url
				}

				// nonce
				if (mxalfwp_admin_localize.nonce) {
					this.ajaxdata.nonce = mxalfwp_admin_localize.nonce
				}

			}
		});

	}

}