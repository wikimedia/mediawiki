const limitSelectors = require( 'mediawiki.pager.codex/limitSelectors.js' );

QUnit.module( 'mediawiki.pager.codex.limitSelectors', QUnit.newMwEnvironment( {
	beforeEach: function () {
		mw.config.set( {
			wgCodexTablePagerLimit: '10'
		} );
	}
} ) );

/**
 * Creates the HTML element for the limit selector
 *
 * @param {Function} submitCallback Provide a callback here that is called when
 *   the form has a submit event fired
 * @return {HTMLFormElement}
 */
function createLimitSelector( submitCallback ) {
	const formElement = document.createElement( 'form' );
	formElement.setAttribute( 'class', 'cdx-table-pager__limit-form' );
	$( formElement ).on( 'submit', ( event ) => {
		event.preventDefault();
		submitCallback();
	} );

	const selectorElement = document.createElement( 'select' );
	selectorElement.setAttribute( 'class', 'cdx-select' );
	selectorElement.setAttribute( 'name', 'limit' );

	const limitOptionOne = document.createElement( 'option' );
	limitOptionOne.setAttribute( 'value', '10' );
	limitOptionOne.setAttribute( 'selected', 'selected' );

	const limitOptionTwo = document.createElement( 'option' );
	limitOptionTwo.setAttribute( 'value', '20' );

	const submitButton = document.createElement( 'button' );
	submitButton.setAttribute( 'class', 'cdx-table-pager__limit-form__submit' );

	selectorElement.append( limitOptionOne );
	selectorElement.append( limitOptionTwo );
	formElement.append( selectorElement );
	formElement.append( submitButton );

	return formElement;
}

QUnit.test( 'should trigger form submit on any limit selector change', ( assert ) => {
	const done = assert.async();

	const $qunitFixture = $( '#qunit-fixture' );

	// Create two limit selectors on the page, and create variables to store whether the limit selectors have
	// had their forms submitted
	let firstSelectorFormSubmitted = false;
	const firstLimitSelectorForm = createLimitSelector( () => ( firstSelectorFormSubmitted = true ) );
	$qunitFixture.append( firstLimitSelectorForm );

	let secondSelectorFormSubmitted = false;
	const secondLimitSelectorForm = createLimitSelector( () => ( secondSelectorFormSubmitted = true ) );
	$qunitFixture.append( secondLimitSelectorForm );

	// Run the code we are testing
	limitSelectors( $qunitFixture );

	// Update the selected option on the first limit selector, then expect that the submit event has been fired
	// on the first limit selector (but not the second)
	$( '.cdx-select', firstLimitSelectorForm ).val( '20' ).trigger( 'change' );

	setTimeout( () => {
		assert.true( firstSelectorFormSubmitted, 'First selector form was submitted after interaction' );
		assert.false( secondSelectorFormSubmitted, 'Second selector form was not submitted as not interacted with' );

		// Then update the selected option on the second limit selector, and expect that the submit event is
		// fired on the second limit selector (but not the first)
		firstSelectorFormSubmitted = false;

		$( '.cdx-select', secondLimitSelectorForm ).val( '20' ).trigger( 'change' );
		setTimeout( () => {
			assert.true( secondSelectorFormSubmitted, 'Second selector form was submitted after interaction' );
			assert.false( firstSelectorFormSubmitted, 'First selector form was not submitted as not interacted with' );

			done();
		} );
	} );
} );
