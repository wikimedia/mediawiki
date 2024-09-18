'use strict';

const outer = document.querySelector( '.mw-htmlform' );
if ( outer ) {
	outer.classList.add( 'mw-block-form' );
	const Vue = require( 'vue' );
	const App = require( './SpecialBlock.vue' );
	Vue.createMwApp( App ).mount( outer );
}
