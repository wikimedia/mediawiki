QUnit.module( 'mediawiki.page.ready: checkboxHack', QUnit.newMwEnvironment(), ( hooks ) => {
	const checkboxHack = require( 'mediawiki.page.ready/checkboxHack.js' );
	let checkbox, target, unbind;

	hooks.beforeEach( () => {
		checkbox = document.createElement( 'input' );
		checkbox.type = 'checkbox';
		checkbox.checked = true;
		// The link has no href, so clicking it does not navigate away from the test page.
		target = document.createElement( 'div' );
		target.innerHTML =
			'<a class="test-link">' +
				'<span class="test-child">1</span>' +
				'<div class="test-nested"><span class="test-grandchild">Heading</span></div>' +
			'</a>' +
			'<span class="test-outside">Not a link</span>';
		document.getElementById( 'qunit-fixture' ).append( checkbox, target );
		unbind = checkboxHack.bindDismissOnClickLink( checkbox, target );
	} );

	hooks.afterEach( () => {
		unbind();
	} );

	QUnit.test.each( 'bindDismissOnClickLink()', {
		'Click on the link itself': [ '.test-link', false ],
		'Click on an element directly inside the link': [ '.test-child', false ],
		'Click on an element nested deeper inside the link': [ '.test-grandchild', false ],
		'Click on an element that is not inside a link': [ '.test-outside', true ]
	}, ( assert, [ selector, expectedChecked ] ) => {
		target.querySelector( selector ).click();
		assert.strictEqual( checkbox.checked, expectedChecked );
	} );
} );
