'use strict';

const outer = document.querySelector( '.mw-htmlform' );
if ( outer ) {
	outer.classList.add( 'mw-block-form' );
	const Vue = require( 'vue' );
	const App = require( './SpecialBlock.vue' );
	const { createPinia } = require( 'pinia' );
	Vue.createMwApp( App )
		.use( createPinia() )
		.mount( outer );
}
