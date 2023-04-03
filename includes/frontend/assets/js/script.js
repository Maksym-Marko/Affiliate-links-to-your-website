if (document.getElementById('mxalfwp_cabinet')) {

    if (typeof Vue === 'undefined') {
        console.warn(mxalfwp_frontend_localize.translation.text_13);
    } else {

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
                            <th>{{ translation.text_14 }}</th>
                            <th>{{ translation.text_6 }}</th>
                            <th>{{ translation.text_7 }}</th>
                            <th>{{ translation.text_8 }}</th>
                            <th>%</th>
                        </tr>
                
                    </thead>
                
                    <tbody
                        v-if="links.length===0"
                    >

                        <tr>
                            <td>
                                <h2
                                    v-if="!loading"
                                >No links yet.</h2>
                                <h2
                                    v-else
                                >Loading...</h2>
                            </td>
                        </tr>
                        
                    </tbody>

                    <tbody
                        v-else
                    >
                        
                        <tr
                            v-for="link in links"
                            :key="link.id"
                            :class="[link.status!=='active' ? 'mxalfwp_inactive_link' : '']"
                        >
                            <th>
                                <div class="mxalfwp_link_wrapper">
                                    <div class="mxalfwp_link_content">
                                        {{link.link}}/?mxpartnerlink={{link.link_key}}
                                    </div>

                                    <div
                                        v-if="link.status==='active'"
                                        class="mxalfwp_link_icon"
                                    >                                    
                                        <i
                                        class="fa fa-files-o"
                                        aria-hidden="true"
                                        id="mxalfwp_copy_link"
                                        @click.prevent="copyLink"
                                        :data-index="link.id"
                                        :data-link="link.link + '/?mxpartnerlink=' + link.link_key"
                                        v-if="copiedLink!==link.id"
                                        ></i>
                                        <i
                                        class="fa fa-check mxalfwp_copied"
                                        aria-hidden="true"
                                        v-else
                                        ></i>
                                    </div>
                                </div>
                            </th>
                            <td>{{pages(link.link_data)}}</td>
                            <td>{{views(link.link_data)}}</td>
                            <td>{{link.bought}}</td>
                            <td>$ {{link.earned}}</td>
                            <td>{{link.percent}}</td>
                        </tr>
                
                    </tbody>
                </table>


            </div>
        `,
            data() {
                return {
                    copiedLink: 0,
                    intervalAmoun: 3,
                    intervalBody: null,
                    loading: true,
                    loadingTimeout: null
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
                },
                loadingData() {
                    const self = this
                    clearTimeout(this.loadingTimeout)
                    this.loading = true;
                    this.loadingTimeout = setTimeout(function () {
                        self.loading = false;
                    }, 2000);
                },
                views(link_data) {

                    let views = 0;

                    for (const [key, value] of Object.entries(link_data.data)) {
                        for (const [_key, _value] of Object.entries(value)) {
                            views += 1;
                        }
                    }

                    return views;

                },
                pages(link_data) {

                    let pages = 0;

                    for (const [key, value] of Object.entries(link_data.data)) {
                        pages += 1;
                    }

                    return pages;

                },
            },
            watch: {
                links() {
                    this.loadingData()
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
                },
                getcurrentuserlinks: {
                    type: Function,
                    required: true
                },
                partnerstatus: {
                    type: String,
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
                    v-if="partnerstatus === 'active' || partnerstatus === '0'"
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

                <div
                    v-if="partnerstatus === 'blocked'"
                >
                    <h3 class="mxalfwp-blocked-text">{{ translation.text_15 }}</h3>
                </div>

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
                                    self.getcurrentuserlinks()
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
                links: [],
                perPage: 10,
                page: 1,
                partnerStatus: 'active'
            },
            methods: {
                getCurrentUserLinks() {

                    this.links = [];

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
                },
                getInitialData() {

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

                    // partner status
                    if (mxalfwp_frontend_localize.partner_status) {
                        this.partnerStatus = mxalfwp_frontend_localize.partner_status
                    }

                }
            },
            mounted() {

                this.getInitialData();

                this.getCurrentUserLinks();

            }
        });

    }

}