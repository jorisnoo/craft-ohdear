/**
 * Oh Dear plugin for Craft CMS
 *
 * Oh Dear JS
 *
 * @author    webhub GmbH
 * @copyright Copyright (c) 2019 webhub GmbH
 * @link      https://webhub.de
 * @package   OhDear
 * @since     1.0.0
 */

import Vue from 'vue';
import { VTooltip } from 'v-tooltip'
import DayJs from "dayjs";
import Translator from "./Translator";
import CheckPermissions from "./CheckPermissions";

Vue.component('card', require('./components/Card').default);
Vue.component('overview', require('./components/pages/Overview').default);
Vue.component('uptime', require('./components/pages/Uptime').default);
Vue.component('lighthouse', require('./components/pages/Lighthouse').default);
Vue.component('performance', require('./components/pages/Performance').default);
Vue.component('performance-chart', require('./components/PerformanceChart').default);
Vue.component('broken-links', require('./components/pages/BrokenLinks').default);
Vue.component('mixed-content', require('./components/pages/MixedContent').default);
Vue.component('certificate-health', require('./components/pages/CertificateHealth').default);
Vue.component('application-health', require('./components/pages/ApplicationHealth').default);
Vue.component('http-status-badge', require('./components/HttpStatusBadge').default);
Vue.component('badge', require('./components/Badge').default);
Vue.component('check-badge', require('./components/CheckBadge').default);
Vue.component('check-body', require('./components/CheckBody').default);
Vue.component('loader', require('./components/Loader').default);
Vue.component('info-icon', require('./components/InfoIcon').default);
Vue.component('status-icon', require('./components/StatusIcon').default);

Vue.directive('tooltip', VTooltip)

Vue.mixin({
    methods: {
        $t(key, replace = {}) {
            return Translator(window.OhDear.translations, key, replace);
        },
        $can(ability, check) {
            return CheckPermissions(window.OhDear.permissions, ability, check);
        }
    },
})

/**
 * DayJs Plugins
 */
DayJs.extend(require('dayjs/plugin/customParseFormat'));
DayJs.extend(require('dayjs/plugin/utc'));
DayJs.extend(require('dayjs/plugin/timezone'));
DayJs.extend(require('dayjs/plugin/localizedFormat'));
DayJs.extend(require('dayjs/plugin/isoWeek'));
DayJs.extend(require('dayjs/plugin/relativeTime'));
DayJs.extend(require('dayjs/plugin/isToday'));

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */
let page = document.getElementById('ohdear-app');
if (page) {
    new Vue({
        el: page
    });
}
