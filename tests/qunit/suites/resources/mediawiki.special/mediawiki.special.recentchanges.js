module( 'mediawiki.special.preferences.js' );

test( '-- Initial check', function() {
	expect( 2 );
	ok( mediaWiki.special.recentchanges.init,
	   'mediaWiki.special.recentchanges.init defined'
	  );
	ok( mediaWiki.special.recentchanges.updateCheckboxes,
	   'mediaWiki.special.recentchanges.updateCheckboxes defined'
	  );
	// TODO: verify checkboxes == [ 'nsassociated', 'nsinvert' ]
});

test( 'foobar', function() {

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
	var enabled = undefined;

	// TODO abstract the double strictEquals

	// At first checkboxes are enabled
	strictEqual( $('input#nsinvert').attr('disabled'), enabled);
	strictEqual( $('input#nsassociated').attr('disabled'), enabled);

	// load our magic code to disable them
	mediaWiki.special.recentchanges.init();
	strictEqual( $('#nsinvert').attr('disabled'), 'disabled');
	strictEqual( $('#nsassociated').attr('disabled'), 'disabled' );

	// select second option...
	$('select#namespace option:nth-child(1)').removeAttr( 'selected' );
	$('select#namespace option:nth-child(2)').attr( 'selected', 'selected' );
	$('select#namespace').change();
	// ... and checkboxes should be enabled again
	strictEqual( $('input#nsinvert').attr('disabled'), enabled);
	strictEqual( $('input#nsassociated').attr('disabled'), enabled);

	// select first option ('all' namespace)...
	$('select#namespace option:nth-child(1)').attr( 'selected', 'selected' );
	$('select#namespace option:nth-child(2)').removeAttr( 'selected' );
	$('select#namespace').change();
	// ... and checkboxes should now be disabled
	strictEqual( $('#nsinvert').attr('disabled'), 'disabled');
	strictEqual( $('#nsassociated').attr('disabled'), 'disabled' );

	// DOM cleanup
	$env.remove();
});
