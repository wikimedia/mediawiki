QUnit.module( 'jquery.tabIndex', QUnit.newMwEnvironment() );

QUnit.test( 'firstTabIndex', 2, function ( assert ) {
	var testEnvironment =
'<form>' +
	'<input tabindex="7" />' +
	'<input tabindex="9" />' +
	'<textarea tabindex="2">Foobar</textarea>' +
	'<textarea tabindex="5">Foobar</textarea>' +
'</form>';

	var $testA = $( '<div>' ).html( testEnvironment ).appendTo( '#qunit-fixture' );
	assert.strictEqual( $testA.firstTabIndex(), 2, 'First tabindex should be 2 within this context.' );

	var $testB = $( '<div>' );
	assert.strictEqual( $testB.firstTabIndex(), null, 'Return null if none available.' );
});

QUnit.test( 'lastTabIndex', 2, function ( assert ) {
	var testEnvironment =
'<form>' +
	'<input tabindex="7" />' +
	'<input tabindex="9" />' +
	'<textarea tabindex="2">Foobar</textarea>' +
	'<textarea tabindex="5">Foobar</textarea>' +
'</form>';

	var $testA = $( '<div>' ).html( testEnvironment ).appendTo( '#qunit-fixture' );
	assert.strictEqual( $testA.lastTabIndex(), 9, 'Last tabindex should be 9 within this context.' );

	var $testB = $( '<div>' );
	assert.strictEqual( $testB.lastTabIndex(), null, 'Return null if none available.' );
});
