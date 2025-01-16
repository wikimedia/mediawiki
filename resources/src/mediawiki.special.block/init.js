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
		// Sync server-provided target input with what will be used in the Vue app.
		const targetInput = document.getElementById( 'mw-bi-target' );
		if ( targetInput && targetInput.value &&
			targetInput.value !== mw.config.get( 'blockTargetUser' )
		) {
			targetInput.disabled = true;
			// Used by UserLookup.vue
			mw.config.set( 'blockTargetUserInput', targetInput.value );
		}

		Vue.createMwApp( App )
			.use( createPinia() )
			.mount( outer );
		// We keep the wrapping form but never want it to submit.
		outer.addEventListener( 'submit', ( e ) => e.preventDefault() );
	} );
}
