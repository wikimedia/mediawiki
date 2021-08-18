( function () {
	var Vue = require( '../../lib/vue/vue.js' );

	Vue.use( require( './errorLogger.js' ) );
	Vue.use( require( './i18n.js' ) );

	module.exports = Vue;
}() );
