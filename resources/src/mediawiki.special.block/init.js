'use strict';

const outer = document.querySelector( '.mw-htmlform' );
if ( outer ) {
	outer.classList.add( 'mw-block-form' );
	const Vue = require( 'vue' );
	const App = require( './SpecialBlock.vue' );
	const { createPinia } = require( 'pinia' );

	// Load any extension-provided messages added by the PHP GetAllBlockActions hook.
	( new mw.Api() ).loadMessagesIfMissing(
		Object.keys( mw.config.get( 'partialBlockActionOptions' ) || {} )
	).then( () => {
		Vue.createMwApp( App )
			.use( createPinia() )
			.mount( outer );
	} );
}
