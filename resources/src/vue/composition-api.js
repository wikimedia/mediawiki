/* global VueCompositionAPI */
// eslint-disable-next-line no-implicit-globals
var Vue = require( 'vue' );

// vue-composition-api.js requires the window.Vue global
window.Vue = Vue;

// Unfortunately, vue-composition-api.js creates a VueCompositionAPI global rather than exporting it
require( '../../lib/vue-composition-api/vue-composition-api.js' );
Vue.use( VueCompositionAPI );

module.exports = VueCompositionAPI;
