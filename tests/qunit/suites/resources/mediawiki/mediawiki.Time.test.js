( function ( mw ) {

	QUnit.module( 'mediawiki.Time', QUnit.newMwEnvironment() );

	QUnit.test( 'Parsing MW timestamp', 1, function ( assert ) {
		var tsMW = '20120726115130';
		var tsUnix = 1343303490000;
		var difference = new mw.Time( tsMW ).getDateObj().getTime() - tsUnix;

		// Compare by unix timestamp instead of using new Date, because that
		// is influenced by local timezone.
		assert.strictEqual( difference, 0, 'Got the right date' );
	} );

	QUnit.test( 'Parse ISO 8601 timestamp', 1, function ( assert ) {
		var tsISO = '2012-07-26T11:51:30.000Z';
		var tsUnix = 1343303490000;
		var difference = new mw.Time( tsISO ).getDateObj().getTime() - tsUnix;

		assert.strictEqual( difference, 0, 'Got the right date' );
	} );

	QUnit.test( 'getDateObj', 2, function ( assert) {
		var tsMW = '20120726115134';
		var time = new mw.Time( tsMW );

		assert.strictEqual( time.getDateObj() instanceof Date, true, 'return the right type' );
		assert.strictEqual( isNaN( time.dateObj.getTime() ), false, 'is not valid' );
	} );

	QUnit.test( 'getHumanTimestamp', 5, function ( assert ) {
		var realDate = new Date();
		var time = new mw.Time( realDate );
		assert.strictEqual( time.getHumanTimestamp(), mw.msg( 'just-now' ) );

		realDate = new Date( ( + new Date() ) - 1000 );
		time = new mw.Time( realDate );
		assert.strictEqual( time.getHumanTimestamp(), mw.msg( 'ago', mw.msg( 'seconds', 1 ) ) );

		realDate = new Date( ( + new Date() ) - 60 * 1000 );
		time = new mw.Time( realDate );
		assert.strictEqual( time.getHumanTimestamp(), mw.msg( 'ago', mw.msg( 'minutes', 1 ) ) );

		realDate = new Date( ( + new Date() ) - 60 * 60 * 1000 );
		time = new mw.Time( realDate );
		assert.strictEqual( time.getHumanTimestamp(), mw.msg( 'ago', mw.msg( 'hours', 1 ) ) );

		realDate = new Date( ( + new Date() ) - 24 * 60 * 60 * 1000 );
		time = new mw.Time( realDate );
		assert.strictEqual( time.getHumanTimestamp(), mw.msg( 'ago', mw.msg( 'days', 1 ) ) );
	} );

	QUnit.test( 'getMwTimestamp', 1, function ( assert ) {
		var tsMW = '20120726115130';
		var tsUnix = 1343303490000;

		var realDate = new Date();
		realDate.setTime( tsUnix );

		var time = new mw.Time( realDate );

		assert.strictEqual( time.getMwTimestamp(), tsMW );
	} );

	QUnit.test( 'Constructor', 2, function ( assert ) {
		var realDate = new Date();
		var time = new mw.Time();
		assert.strictEqual( time.getDateObj().toString(), realDate.toString(), 'Constructing with no arguments gives current time' );

		assert.throws( function () {
			var time = new mw.Time( 500 );
		}, Error, 'Constructing with invalid arguments (integer) throws an error' );
	} );

}( mediaWiki ) );
