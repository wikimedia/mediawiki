( function ( mw, $ ) {
	QUnit.module( 'mediawiki.special.recentchanges', QUnit.newMwEnvironment() );

	// TODO: verify checkboxes == [ 'nsassociated', 'nsinvert' ]

	QUnit.test( '"all" namespace disable checkboxes', 8, function ( assert ) {
		var selectHtml, $env, $options;

		// from Special:Recentchanges
		selectHtml = '<select id="namespace" name="namespace" class="namespaceselector">'
			+ '<option value="" selected="selected">all</option>'
			+ '<option value="0">(Main)</option>'
			+ '<option value="1">Talk</option>'
			+ '<option value="2">User</option>'
			+ '<option value="3">User talk</option>'
			+ '<option value="4">ProjectName</option>'
			+ '<option value="5">ProjectName talk</option>'
			+ '</select>'
			+ '<input name="invert" type="checkbox" value="1" id="nsinvert" title="no title" />'
			+ '<label for="nsinvert" title="no title">Invert selection</label>'
			+ '<input name="associated" type="checkbox" value="1" id="nsassociated" title="no title" />'
			+ '<label for="nsassociated" title="no title">Associated namespace</label>'
			+ '<input type="submit" value="Go" />'
			+ '<input type="hidden" value="Special:RecentChanges" name="title" />';

		$env = $( '<div>' ).html( selectHtml ).appendTo( 'body' );

		// TODO abstract the double strictEquals

		// At first checkboxes are enabled
		assert.strictEqual( $( '#nsinvert' ).prop( 'disabled' ), false );
		assert.strictEqual( $( '#nsassociated' ).prop( 'disabled' ), false );

		// Initiate the recentchanges module
		mw.special.recentchanges.init();

		// By default
		assert.strictEqual( $( '#nsinvert' ).prop( 'disabled' ), true );
		assert.strictEqual( $( '#nsassociated' ).prop( 'disabled' ), true );

		// select second option...
		$options = $( '#namespace' ).find( 'option' );
		$options.eq( 0 ).removeProp( 'selected' );
		$options.eq( 1 ).prop( 'selected', true );
		$( '#namespace' ).change();

		// ... and checkboxes should be enabled again
		assert.strictEqual( $( '#nsinvert' ).prop( 'disabled' ), false );
		assert.strictEqual( $( '#nsassociated' ).prop( 'disabled' ), false );

		// select first option ( 'all' namespace)...
		$options.eq( 1 ).removeProp( 'selected' );
		$options.eq( 0 ).prop( 'selected', true );
		$( '#namespace' ).change();

		// ... and checkboxes should now be disabled
		assert.strictEqual( $( '#nsinvert' ).prop( 'disabled' ), true );
		assert.strictEqual( $( '#nsassociated' ).prop( 'disabled' ), true );

		// DOM cleanup
		$env.remove();
	} );
}( mediaWiki, jQuery ) );
