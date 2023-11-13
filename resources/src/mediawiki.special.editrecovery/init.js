( function () {
	'use strict';
	const outer = document.querySelector( '.mw-EditRecovery-special' );
	if ( !outer ) {
		return;
	}
	const Vue = require( 'vue' );
	const App = require( './SpecialEditRecovery.vue' );
	Vue.createMwApp( App ).mount( outer );
}() );
