( function () {
	'use strict';
	const outer = document.querySelector( '.mw-htmlform' );
	if ( !outer ) {
		return;
	}
	const Vue = require( 'vue' );
	const App = require( './SpecialBlock.vue' );
	Vue.createMwApp( App ).mount( outer );
}() );
