'use strict';

const form = document.querySelector( '.mw-htmlform' );
if ( form ) {
	form.id = 'mw-block-form';
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
			.mount( form );
		// We keep the wrapping form but never want it to submit.
		form.addEventListener( 'submit', ( e ) => e.preventDefault() );
	} );
}
