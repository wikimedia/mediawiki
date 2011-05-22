module( 'jquery.tabIndex.js' );

test( '-- Initial check', function(){

	ok( $.fn.firstTabIndex, '$.fn.firstTabIndex defined' );
	ok( $.fn.lastTabIndex, '$.fn.lastTabIndex defined' );

});

test( 'firstTabIndex', function(){

	var testEnvironment = 
'<form>\
	<input tabindex="7" />\
	<input tabindex="9" />\
	<textarea tabindex="2">Foobar</textarea>\
	<textarea tabindex="5">Foobar</textarea>\
</form>';
	var $testA = $( '<div />' ).html( testEnvironment ).appendTo( 'body' );

	deepEqual( $testA.firstTabIndex(), 2, 'First tabindex should be 2 within this context.' );

	var $testB = $( '<div />' );

	deepEqual( $testB.firstTabIndex(), null, 'Return null if none available.' );

	// Clean up
	$testA.add( $testB).remove();
});

test( 'lastTabIndex', function(){

	var testEnvironment = 
'<form>\
	<input tabindex="7" />\
	<input tabindex="9" />\
	<textarea tabindex="2">Foobar</textarea>\
	<textarea tabindex="5">Foobar</textarea>\
</form>';
	var $testA = $( '<div />' ).html( testEnvironment ).appendTo( 'body' );

	deepEqual( $testA.lastTabIndex(), 9, 'Last tabindex should be 9 within this context.' );

	var $testB = $( '<div />' );

	deepEqual( $testB.lastTabIndex(), null, 'Return null if none available.' );

	// Clean up
	$testA.add( $testB).remove();
});