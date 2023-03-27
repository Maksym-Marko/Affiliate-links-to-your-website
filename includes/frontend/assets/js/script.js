if (document.getElementById('mxalfwp_cabinet')) {

    /**
     * Components 
     * */
    // Table
    Vue.component('mxalfwp_c_table', {
        props: {
            translation: {
                type: Object,
                required: true
            },
            links: {
                type: Array,
                required: true
            }
        },
        template: `
            <div>

                <!-- List of my Affiliate Links -->
                <h3>{{ translation.text_4 }}</h3>
                <table class="mxalfwp_table">
                
                    <thead>
                
                        <tr>
                            <th>{{ translation.text_5 }}</th>
                            <th>{{ translation.text_6 }}</th>
                            <th>{{ translation.text_7 }}</th>
                            <th>{{ translation.text_8 }}</th>
                            <th>{{ translation.text_9 }}</th>
                        </tr>
                
                    </thead>
                
                    <tbody
                        v-if="links.length===0"
                    >

                        <tr>
                            <td>
                                <h2>No links yet.</h2>
                            </td>
                        </tr>
                        
                    </tbody>

                    <tbody
                        v-else
                    >
                        
                        <tr
                            v-for="link in links"
                            :key="link.id"
                        >
                            <th>
                                <div class="mxalfwp_link_wrapper">
                                    <div class="mxalfwp_link_content">
                                        {{link.link}}/?mxpartnerlink={{link.user_id}}
                                    </div>
                                    <i
                                      class="fa fa-files-o mxalfwp_link_icon"
                                      aria-hidden="true"
                                      id="mxalfwp_copy_link"
                                      @click.prevent="copyLink"
                                      :data-index="link.id"
                                      :data-link="link.link + '/?mxpartnerlink=' + link.user_id"
                                      v-if="copiedLink!==link.id"
                                    ></i>
                                    <i
                                      class="fa fa-check mxalfwp_copied mxalfwp_link_icon"
                                      aria-hidden="true"
                                      v-else
                                    ></i>
                                </div>
                            </th>
                            <td>{{link.link_data.views}}</td>
                            <td>{{link.bought}}</td>
                            <td>$ {{link.earned}}</td>
                            <td>$ {{link.paied}}</td>
                        </tr>
                
                    </tbody>
                </table>


            </div>
        `,
        data() {
            return {
                copiedLink: 0,
                intervalAmoun: 3,
                intervalBody: null
            }
        },
        methods: {
            copyLink(e) {

                let link = e.target.getAttribute('data-link');
                let index = e.target.getAttribute('data-index');

                let input = document.createElement('input');

                document.body.appendChild(input);

                input.value = link;

                input.select()

                document.execCommand('copy');

                input.remove();

                this.copiedLink = index

                this.changeIcon();

            },
            changeIcon() {
                const self = this
                clearInterval(self.intervalBody);
                this.intervalBody = setInterval(function () {

                    if (self.intervalAmoun <= 0) {
                        self.intervalAmoun = 3;
                        self.copiedLink = 0;
                        clearInterval(self.intervalBody);
                        return;
                    } else {
                        self.intervalAmoun -= 1;
                    }

                }, 1000);
            }
        }
    });

    // Form
    Vue.component('mxalfwp_c_form', {
        props: {
            translation: {
                type: Object,
                required: true
            },
            ajaxdata: {
                type: Object,
                required: true
            },
            toquerystring: {
                type: Function,
                required: true
            }
        },
        template: `
            <div>

                <!-- Generate Affiliate Link -->
                <h3>{{ translation.text_1 }}</h3>
                <form 
                    class="mxalfwp-generate-link-form"
                    @submit.prevent="generateLink"
                >
                
                    <div>
                        <label for="mxalfwp-url">{{ translation.text_2 }}</label>
                
                        <div class="mxalfwp-input-wrap">
                
                            <input
                              v-model="url"
                              type="text"
                              :class="[errors.length>0 && attempt ? 'mxalfwp-input-error' : '']"
                              id="mxalfwp-url"
                              placeholder="http://affiliate-links-woocommerce.local/" 
                            />
                
                            <button 
								type="submit"
								:disabled="disableButton"
							>{{ translation.text_2 }}</button>
                
                        </div>
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
                
                </form>

            </div>
        `,
        data() {
            return {
                url: null,
                errors: [],
                attempt: false,
                disableButton: false
            }
        },
        methods: {
            urlValidate(url) {

                try {

                    new URL(url);
                    return true;

                } catch (error) {

                    return false;

                }


            },
            domainChecking(url) {

                this.errors = [];

                // URL checking
                if (!this.urlValidate(url)) {
                    this.errors.push(this.translation.text_11);
                    return false;
                }

                this.errors = [];

                // Domain checking
                const link = new URL(url);

                if (link.host !== window.location.host) {
                    this.errors.push(this.translation.text_10);
                    return false;
                }

                this.errors = [];

                return true;

            },
            generateLink() {

                const self = this;

                if (this.disableButton) {
                    return;
                }

                this.attempt = true;

                if (this.domainChecking(this.url)) {

                    this.disableButton = true;

                    // Request
                    const xmlhttp = new XMLHttpRequest();

                    xmlhttp.open('POST', this.ajaxdata.ajax_url);

                    xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");

                    xmlhttp.onload = function () {

                        if (this.status === 200) {

                            const res = JSON.parse(this.response);

                            if (res.status === 'success') {
                                alert(res.message);
                                self.url = null;
                                self.attempt = false;
                            } else {
                                self.errors.push(res.message);
                            }

                        } else {
                            self.errors.push(translation.text_12);
                        }

                        self.disableButton = false
                    }

                    const data = {
                        action: 'mxalfwp_link_generate',
                        nonce: this.ajaxdata.nonce,
                        url: this.url
                    }

                    xmlhttp.send(this.toquerystring(data));

                }

            }
        },
        watch: {
            url() {
                this.domainChecking(this.url);
            }
        }
    });

    /**
     *  Base object
     * */
    const app = new Vue({
        el: '#mxalfwp_cabinet',
        data: {
            translation: {},
            ajaxdata: {},
            links: []
        },
        methods: {
            getCurrentUserLinks() {

                const self = this;

                const xmlhttp = new XMLHttpRequest();

                xmlhttp.open('POST', this.ajaxdata.ajax_url);

                xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");

                xmlhttp.onload = function () {

                    if (this.status === 200) {

                        const links = JSON.parse(this.response);

                        self.links = links;

                    } else {
                        self.errors.push(translation.text_12);
                    }

                }

                const data = {
                    action: 'mxalfwp_get_links',
                    nonce: this.ajaxdata.nonce
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
            if (mxalfwp_frontend_localize.translation) {
                this.translation = mxalfwp_frontend_localize.translation
            }

            // ajax url
            if (mxalfwp_frontend_localize.ajax_url) {
                this.ajaxdata.ajax_url = mxalfwp_frontend_localize.ajax_url
            }

            // nonce
            if (mxalfwp_frontend_localize.nonce) {
                this.ajaxdata.nonce = mxalfwp_frontend_localize.nonce
            }

            this.getCurrentUserLinks();

        }
    });

}