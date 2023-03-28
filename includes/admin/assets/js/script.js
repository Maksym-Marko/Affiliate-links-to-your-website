jQuery(document).ready(function ($) {

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

				jQuery.post(mxalfwp_admin_localize.ajaxurl, data, function (response) {

					location.reload()

				});

			}

		}

	});

	// Create table item
	$('#mxalfwp_form_create_table_item').on('submit', function (e) {

		e.preventDefault();

		var nonce = $(this).find('#mxalfwp_wpnonce').val();

		var title = $('#mxalfwp_title').val();
		var description = $('#mxalfwp_mx_description').val();

		var data = {

			'action': 'mxalfwp_create_item',
			'nonce': nonce,
			'title': title,
			'description': description

		};

		if (title == '' || description == '') {

			alert('Fill in all fields')

			return;

		}

		jQuery.post(mxalfwp_admin_localize.ajaxurl, data, function (response) {

			if (response === '1') {
				window.location.href = mxalfwp_admin_localize.main_page
			}
			alert('Created!');

		});

	});

	// Edit table item
	$('#mxalfwp_form_update').on('submit', function (e) {

		e.preventDefault();

		var nonce = $(this).find('#mxalfwp_wpnonce').val();

		var id = $('#mxalfwp_id').val();
		var title = $('#mxalfwp_title').val();
		var description = $('#mxalfwp_mx_description').val();

		var data = {

			'action': 'mxalfwp_update',
			'nonce': nonce,
			'id': id,
			'title': title,
			'description': description

		};

		if (id == '' || title == '' || description == '') {

			alert('Fill in all fields')

			return;

		}

		jQuery.post(mxalfwp_admin_localize.ajaxurl, data, function (response) {

			// console.log( response );
			alert('Updated!');

		});

	});

});


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
									:class="[errors.length>0 && attempt ? 'mxalfwp-input-error' : '']"
								/>
								<small>{{translation.text_3}}</small>
							</div>

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
					</div>

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
					attempt: false,
					errors: []
				}
			},
			methods: {
				isNumber() {
					if( isNaN( this.default_percent ) ) {
						return false;
					}
					return true;
				},
				isInRange() {
					if( this.default_percent > 99 || this.default_percent < 0.1 ) {
						return false;
					}
					return true;
				},
				saveChanges() {

					this.attempt = true;

					this.formChecking();

					if( ! this.isNumber() ) {
						return;
					}

					if( ! this.isInRange() ) {
						return;
					}

					this.savesettings({'percent':this.default_percent});

				},
				formChecking() {
					this.errors = [];

					if( ! this.isNumber() ) {
						this.errors.push( this.translation.text_5 );
					}

					if( ! this.isInRange() ) {
						this.errors.push( this.translation.text_6 );
					}
				}
			},
			watch: {
				default_percent() {
					this.formChecking();
				},
				percent() {
					this.default_percent = this.percent
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
				progress: false
			},
			methods: {
				saveSettings( obj ) {

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
						percent: obj.percent
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
                    this.percent = parseFloat( mxalfwp_admin_localize.percent )
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