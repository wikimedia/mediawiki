module( 'mediawiki.special.recentchanges', QUnit.newMwEnvironment() );

test( '-- Initial check', function() {
	expect( 2 );
	ok( mw.special.recentchanges.init, 'mw.special.recentchanges.init defined' );
	ok( mw.special.recentchanges.updateCheckboxes, 'mw.special.recentchanges.updateCheckboxes defined' );
	// TODO: verify checkboxes == [ 'nsassociated', 'nsinvert' ]
});

test( '"all" namespace disable checkboxes', function() {

	// from Special:Recentchanges
	var select =
	'<select id="namespace" name="namespace" class="namespaceselector">'
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
	+ '<input type="hidden" value="Special:RecentChanges" name="title" />'
	;

	var $env = $( '<div>' ).html( select ).appendTo( 'body' );

	// TODO abstract the double strictEquals

	// At first checkboxes are enabled
	strictEqual( $( '#nsinvert' ).prop( 'disabled' ), false );
	strictEqual( $( '#nsassociated' ).prop( 'disabled' ), false );

	// Initiate the recentchanges module
	mw.special.recentchanges.init();

	// By default
	strictEqual( $( '#nsinvert' ).prop( 'disabled' ), true );
	strictEqual( $( '#nsassociated' ).prop( 'disabled' ), true );

	// select second option...
	var $options = $( '#namespace' ).find( 'option' );
	$options.eq(0).removeProp( 'selected' );
	$options.eq(1).prop( 'selected', true );
	$( '#namespace' ).change();

	// ... and checkboxes should be enabled again
	strictEqual( $( '#nsinvert' ).prop( 'disabled' ), false );
	strictEqual( $( '#nsassociated' ).prop( 'disabled' ), false );

	// select first option ( 'all' namespace)...
	$options.eq(1).removeProp( 'selected' );
	$options.eq(0).prop( 'selected', true );
	$( '#namespace' ).change();

	// ... and checkboxes should now be disabled
	strictEqual( $( '#nsinvert' ).prop( 'disabled' ), true );
	strictEqual( $( '#nsassociated' ).prop( 'disabled' ), true );

	// DOM cleanup
	$env.remove();
});
