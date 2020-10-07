( function () {
	QUnit.module( 'mediawiki.special.recentchanges', QUnit.newMwEnvironment() );

	// TODO: verify checkboxes == [ 'nsassociated', 'nsinvert' ]

	QUnit.test( '"all" namespace hides checkboxes', function ( assert ) {
		var selectHtml, $env, $options,
			rc = require( 'mediawiki.special.recentchanges' );

		// from Special:Recentchanges
		selectHtml = '<select id="namespace" name="namespace" class="namespaceselector">' +
			'<option value="" selected="selected">all</option>' +
			'<option value="0">(Main)</option>' +
			'<option value="1">Talk</option>' +
			'<option value="2">User</option>' +
			'<option value="3">User talk</option>' +
			'<option value="4">ProjectName</option>' +
			'<option value="5">ProjectName talk</option>' +
			'</select>' +
			'<span class="mw-input-with-label mw-input-hidden">' +
			'<input name="invert" type="checkbox" value="1" id="nsinvert" title="no title" />' +
			'<label for="nsinvert" title="no title">Invert selection</label>' +
			'</span>' +
			'<span class="mw-input-with-label mw-input-hidden">' +
			'<input name="associated" type="checkbox" value="1" id="nsassociated" title="no title" />' +
			'<label for="nsassociated" title="no title">Associated namespace</label>' +
			'</span>' +
			'<input type="submit" value="Go" />' +
			'<input type="hidden" value="Special:RecentChanges" name="title" />';

		$env = $( '<div>' ).html( selectHtml ).appendTo( document.body );

		// TODO abstract the double strictEquals

		// At first checkboxes are hidden
		// eslint-disable-next-line no-jquery/no-class-state
		assert.strictEqual( $( '#nsinvert' ).closest( '.mw-input-with-label' ).hasClass( 'mw-input-hidden' ), true );
		// eslint-disable-next-line no-jquery/no-class-state
		assert.strictEqual( $( '#nsassociated' ).closest( '.mw-input-with-label' ).hasClass( 'mw-input-hidden' ), true );

		// Initiate the recentchanges module
		rc.init();

		// By default
		// eslint-disable-next-line no-jquery/no-class-state
		assert.strictEqual( $( '#nsinvert' ).closest( '.mw-input-with-label' ).hasClass( 'mw-input-hidden' ), true );
		// eslint-disable-next-line no-jquery/no-class-state
		assert.strictEqual( $( '#nsassociated' ).closest( '.mw-input-with-label' ).hasClass( 'mw-input-hidden' ), true );

		// select second option...
		$options = $( '#namespace' ).find( 'option' );
		$options.eq( 0 ).removeProp( 'selected' );
		$options.eq( 1 ).prop( 'selected', true );
		$( '#namespace' ).trigger( 'change' );

		// ... and checkboxes should be visible again
		// eslint-disable-next-line no-jquery/no-class-state
		assert.strictEqual( $( '#nsinvert' ).closest( '.mw-input-with-label' ).hasClass( 'mw-input-hidden' ), false );
		// eslint-disable-next-line no-jquery/no-class-state
		assert.strictEqual( $( '#nsassociated' ).closest( '.mw-input-with-label' ).hasClass( 'mw-input-hidden' ), false );

		// select first option ( 'all' namespace)...
		$options.eq( 1 ).removeProp( 'selected' );
		$options.eq( 0 ).prop( 'selected', true );
		$( '#namespace' ).trigger( 'change' );

		// ... and checkboxes should now be hidden
		// eslint-disable-next-line no-jquery/no-class-state
		assert.strictEqual( $( '#nsinvert' ).closest( '.mw-input-with-label' ).hasClass( 'mw-input-hidden' ), true );
		// eslint-disable-next-line no-jquery/no-class-state
		assert.strictEqual( $( '#nsassociated' ).closest( '.mw-input-with-label' ).hasClass( 'mw-input-hidden' ), true );

		// DOM cleanup
		$env.remove();
	} );
}() );
