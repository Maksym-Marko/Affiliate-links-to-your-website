const mxalfwp_links_tracker = {
    urlData: null,
    rootUrl: '',
    $_GET: {},
    checkStorage: true,
    init: function () {
        this.getUrlData();
        this.manageQuery();
        this.manageLocalStorage();
    },
    linkIdentifier: function () {
        return this.getCookie('mxalfwpLinkIdentifier')
    },
    getUrlData: function () {

        this.urlData = window.location;

        // get root url
        this.rootUrl = this.urlData.origin + this.urlData.pathname

    },
    manageQuery: function () {

        const searchItems = this.urlData.search.substr(1).split("&");

        // if no GET items
        if (searchItems[0] === '') return;

        // parse GET items
        for (let i = 0; i < searchItems.length; i++) {

            const temp = searchItems[i].split("=");

            if (temp[0] === 'mxpartnerlink') {
                this.$_GET[decodeURIComponent(temp[0])] = decodeURIComponent(temp[1]);
            }

        }

        // if there is "mxpartnerlink" parameter
        if (typeof this.$_GET.mxpartnerlink === 'undefined') return;

        // partner id
        // this.linkIdentifier = this.$_GET.mxpartnerlink;

        this.setCookie('mxalfwpLinkIdentifier', this.$_GET.mxpartnerlink, 100)

        // don't check localStorage
        this.checkStorage = false;

        // attempt to save data
        this.maybeSaveData();

    },
    manageLocalStorage: function () {

        if (!this.checkStorage) return;

        const searchItems = this.urlData.search.substr(1).split("&");

        // if no GET items
        if (this.getCookie('mxalfwpLinkIdentifier') === '') return;

        this.maybeSaveData();

    },
    maybeSaveData: function () {

        const self = this;

        const xmlhttp = new XMLHttpRequest();

        xmlhttp.open('POST', mxalfwp_frontend_localize.ajax_url);

        xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded;");

        xmlhttp.onload = function () {

            if (this.status === 200) {

                if (this.response === 'restore') {
                    self.setCookie('mxalfwpLinkIdentifier', null, -1)
                }

            }

        }

        const data = {
            action: 'mxalfwp_save_link_data',
            nonce: mxalfwp_frontend_localize.nonce,
            url: this.rootUrl,
            link_key: this.linkIdentifier()
        }

        xmlhttp.send(this.toQueryString(data));

    },
    toQueryString: function (obj) {
        var str = [];
        for (var p in obj)
            if (obj.hasOwnProperty(p)) {
                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
            }
        return str.join("&");
    },
    setCookie: function (cname, cvalue, exdays) {
        const d = new Date();
        d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
        let expires = "expires=" + d.toUTCString();
        document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
    },
    getCookie: function (cname) {
        let name = cname + "=";
        let decodedCookie = decodeURIComponent(document.cookie);
        let ca = decodedCookie.split(';');
        for (let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) == ' ') {
                c = c.substring(1);
            }
            if (c.indexOf(name) == 0) {
                return c.substring(name.length, c.length);
            }
        }
        return "";
    }
};

// 
mxalfwp_links_tracker.init();