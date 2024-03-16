$( function () {
	/**
	* Fired on page load to enhance any HTML forms on the page.
	*
	* @event ~'htmlform.enhance'
	* @param {jQuery} document
	* @memberof Hooks
	*/
	mw.hook( 'htmlform.enhance' ).fire( $( document ) );
} );

mw.hook( 'htmlform.enhance' ).add( function ( $root ) {
	// Turn HTML5 form validation back on, in cases where it was disabled server-side (see
	// HTMLForm::needsJSForHtml5FormValidation()) because we need extra logic implemented in JS to
	// validate correctly. Currently, this is only used for forms containing fields with 'hide-if'.
	$root.find( '.mw-htmlform' ).removeAttr( 'novalidate' );

	// Enable collapsible forms
	var $collapsible = $root.find( '.mw-htmlform-ooui .oo-ui-fieldsetLayout.mw-collapsible' );
	if ( $collapsible.length ) {
		mw.loader.using( 'jquery.makeCollapsible' ).then( function () {
			$collapsible.makeCollapsible();
		} );
	}
} );

// Collect other hook handlers
require( './autocomplete.js' );
require( './autoinfuse.js' );
require( './cloner.js' );
require( './cond-state.js' );
require( './multiselect.js' );
require( './selectandother.js' );
require( './selectorother.js' );
require( './timezone.js' );
