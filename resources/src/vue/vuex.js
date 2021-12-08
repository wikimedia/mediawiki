/* global Vuex */
const Vue = require( 'vue' );

// Unfortunately, vuex.js creates a Vuex global rather than exporting it
require( '../../lib/vuex/vuex.js' );
Vue.use( Vuex );

module.exports = window.Vuex;
