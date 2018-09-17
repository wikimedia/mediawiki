( function () {
	QUnit.module( 'jquery.tabIndex', QUnit.newMwEnvironment() );

	QUnit.test( 'firstTabIndex', function ( assert ) {
		var html, $testA, $testB;
		html = '<form>' +
			'<input tabindex="7" />' +
			'<input tabindex="9" />' +
			'<textarea tabindex="2">Foobar</textarea>' +
			'<textarea tabindex="5">Foobar</textarea>' +
			'</form>';

		$testA = $( '<div>' ).html( html ).appendTo( '#qunit-fixture' );
		assert.strictEqual( $testA.firstTabIndex(), 2, 'First tabindex should be 2 within this context.' );

		$testB = $( '<div>' );
		assert.strictEqual( $testB.firstTabIndex(), null, 'Return null if none available.' );
	} );

	QUnit.test( 'lastTabIndex', function ( assert ) {
		var html, $testA, $testB;
		html = '<form>' +
			'<input tabindex="7" />' +
			'<input tabindex="9" />' +
			'<textarea tabindex="2">Foobar</textarea>' +
			'<textarea tabindex="5">Foobar</textarea>' +
			'</form>';

		$testA = $( '<div>' ).html( html ).appendTo( '#qunit-fixture' );
		assert.strictEqual( $testA.lastTabIndex(), 9, 'Last tabindex should be 9 within this context.' );

		$testB = $( '<div>' );
		assert.strictEqual( $testB.lastTabIndex(), null, 'Return null if none available.' );
	} );
}() );
