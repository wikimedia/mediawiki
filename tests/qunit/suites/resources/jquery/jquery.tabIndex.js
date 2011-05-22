module( 'jquery.tabIndex.js' );

test( '-- Initial check', function(){

	ok( $.fn.firstTabIndex, '$.fn.firstTabIndex defined' );
	ok( $.fn.lastTabIndex, '$.fn.lastTabIndex defined' );

});

test( 'firstTabIndex', function(){

	var testEnvironment = 
'<form>\
	<input tabindex="7" />\
	<input tabindex="2" />\
	<textarea tabindex="9">Foobar</textarea>\
</form>';
	var $test = $( '<div />' ).html( testEnvironment ).appendTo( 'body' );

	deepEqual( $test.firstTabIndex(), 2, 'First tabindex should be 2 within this context.' );

	// Clean up
	$test.remove();
});

test( 'lastTabIndex', function(){

	var testEnvironment = 
'<form>\
	<input tabindex="7" />\
	<input tabindex="2" />\
	<textarea tabindex="9">Foobar</textarea>\
</form>';
	var $test = $( '<div />' ).html( testEnvironment ).appendTo( 'body' );

	deepEqual( $test.lastTabIndex(), 9, 'Last tabindex should be 9 within this context.' );

	// Clean up
	$test.remove();
});