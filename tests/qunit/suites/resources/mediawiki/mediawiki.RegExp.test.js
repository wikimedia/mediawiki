( function ( mw ) {
	QUnit.module( 'mediawiki.RegExp' );

	QUnit.test( 'escape', 5, function ( assert ) {

		var special = '<!-- ([{+mW+}]) $^|?>';

		assert.equal(
			mw.RegExp.escape( special ),
			'<!\\-\\- \\(\\[\\{\\+mW\\+\\}\\]\\) \\$\\^\\|\\?>',
			'Escape special characters'
		);

		assert.propEqual(
			( 'x' + special + 'y' + special ).match(
				new RegExp( '(' + mw.RegExp.escape( special ) + ')', 'g' )
			),
			[ special, special ],
			'Verify output is a valid regex that can match the input'
		);

		assert.equal(
			mw.RegExp.escape( 'ABCDEFGHIJKLMNOPQRSTUVWXYZ' ),
			'ABCDEFGHIJKLMNOPQRSTUVWXYZ',
			'Leave uppercase characters alone'
		);

		assert.equal(
			mw.RegExp.escape( 'abcdefghijklmnopqrstuvwxyz' ),
			'abcdefghijklmnopqrstuvwxyz',
			'Leave lowercase characters alone'
		);

		assert.equal(
			mw.RegExp.escape( '0123456789' ),
			'0123456789',
			'Leave numbers alone'
		);
	} );

}( mediaWiki ) );
