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
		Vue.createMwApp( App )
			.use( createPinia() )
			.mount( form );
		// We keep the wrapping form but never want it to submit.
		form.addEventListener( 'submit', ( e ) => e.preventDefault() );
	} );
}

/**
 * Fired after a successful (re-)block on Special:Block. Only applicable on wikis with
 * {@link https://www.mediawiki.org/wiki/Manual:$wgEnableMultiBlocks multiblocks} enabled.
 *
 * @event ~'SpecialBlock.block'
 * @memberof Hooks
 * @param {Object} data Response from the block API.
 * @stable
 */

/**
 * Fired when the form on Special:Block is opened or closed. Only applicable on wikis with
 * {@link https://www.mediawiki.org/wiki/Manual:$wgEnableMultiBlocks multiblocks} enabled.
 *
 * @event ~'SpecialBlock.form'
 * @memberof Hooks
 * @param {boolean} open Whether the form is open or closed.
 * @param {string} target Username, IP, or IP range.
 * @param {number|null} id The block ID, when editing an existing block.
 * @stable
 */
