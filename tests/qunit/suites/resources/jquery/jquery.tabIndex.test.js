module( 'jquery.tabIndex', QUnit.newMwEnvironment() );

test( '-- Initial check', function() {
	expect(2);

	ok( $.fn.firstTabIndex, '$.fn.firstTabIndex defined' );
	ok( $.fn.lastTabIndex, '$.fn.lastTabIndex defined' );
});

test( 'firstTabIndex', function() {
	expect(2);

	var testEnvironment =
'<form>' +
	'<input tabindex="7" />' +
	'<input tabindex="9" />' +
	'<textarea tabindex="2">Foobar</textarea>' +
	'<textarea tabindex="5">Foobar</textarea>' +
'</form>';

	var $testA = $( '<div>' ).html( testEnvironment ).appendTo( '#qunit-fixture' );
	strictEqual( $testA.firstTabIndex(), 2, 'First tabindex should be 2 within this context.' );

	var $testB = $( '<div>' );
	strictEqual( $testB.firstTabIndex(), null, 'Return null if none available.' );
});

test( 'lastTabIndex', function() {
	expect(2);

	var testEnvironment =
'<form>' +
	'<input tabindex="7" />' +
	'<input tabindex="9" />' +
	'<textarea tabindex="2">Foobar</textarea>' +
	'<textarea tabindex="5">Foobar</textarea>' +
'</form>';

	var $testA = $( '<div>' ).html( testEnvironment ).appendTo( '#qunit-fixture' );
	strictEqual( $testA.lastTabIndex(), 9, 'Last tabindex should be 9 within this context.' );

	var $testB = $( '<div>' );
	strictEqual( $testB.lastTabIndex(), null, 'Return null if none available.' );
});
