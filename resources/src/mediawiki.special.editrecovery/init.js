( function () {
	'use strict';
	const outer = document.querySelector( '.mw-special-EditRecovery-app' );
	if ( !outer ) {
		return;
	}
	const Vue = require( 'vue' );
	const App = require( './SpecialEditRecovery.vue' );
	Vue.createMwApp( App ).mount( outer );
}() );
