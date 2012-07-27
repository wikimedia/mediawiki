QUnit.module( 'mediawiki.Time', QUnit.newMwEnvironment() );

QUnit.test( 'Parse MW timestamp into valid mw.Time object with proper data', 5, function ( assert ) {
	var mwTimestamp = '20120726115134';
	var realDate = new Date( 2012, 06, 26, 11, 51, 34 );
	var mwTime = new mw.Time( mwTimestamp );
	var difference = mwTime.dateObj - realDate;
	assert.strictEqual( mwTime instanceof mw.Time, true, 'mw.Time object is the right type' );
	assert.strictEqual( mwTime.dateObj instanceof Date, true, 'Date object is the right type' );
	assert.strictEqual( isNaN( mwTime.dateObj.getTime() ), false, 'Date object is valid' );
	assert.strictEqual( difference, 0, 'Got the right date' );
	assert.deepEqual( realDate, mwTime.dateObj, 'The two Date objects are exactly the same' );
} );

QUnit.test( 'Parse ISO 8601 time string into a valid mw.Time object with proper data', 6, function ( assert ) {
	var realDate = new Date( 2012, 03, 28, 11, 30, 15 );
	var isoTimestamp = realDate.toISOString();
	var mwTime = new mw.Time( isoTimestamp );
	var difference = mwTime.dateObj - realDate;
	assert.strictEqual( mwTime instanceof mw.Time, true, 'mw.Time object is the right type' );
	assert.strictEqual( mwTime.dateObj instanceof Date, true, 'Date object is the right type' );
	assert.strictEqual( isNaN( mwTime.dateObj.getTime() ), false, 'Date object is valid' );
	assert.strictEqual( difference, 0, 'Got the right date' );
	assert.strictEqual( isoTimestamp, mwTime.dateObj.toISOString(), 'No data was lost' );
	assert.deepEqual( realDate, mwTime.dateObj, 'The two Date objects are exactly the same' );
} );

QUnit.test( 'Create valid mw.Time object with Date object, and make instances of the ago message', 5, function ( assert ) {
	var realDate = new Date();
	var mwTime = new mw.Time( realDate );
	assert.strictEqual( mwTime.getHumanTimestamp(), mw.msg( 'just-now' ) );

	realDate = new Date( ( + new Date() ) - 1000 );
	mwTime = new mw.Time( realDate );
	assert.strictEqual( mwTime.getHumanTimestamp(), mw.msg( 'ago', mw.msg( 'seconds', 1 ) ) );

	realDate = new Date( ( + new Date() ) - 60 * 1000 );
	mwTime = new mw.Time( realDate );
	assert.strictEqual( mwTime.getHumanTimestamp(), mw.msg( 'ago', mw.msg( 'minutes', 1 ) ) );

	realDate = new Date( ( + new Date() ) - 60 * 60 * 1000 );
	mwTime = new mw.Time( realDate );
	assert.strictEqual( mwTime.getHumanTimestamp(), mw.msg( 'ago', mw.msg( 'hours', 1 ) ) );

	realDate = new Date( ( + new Date() ) - 24 * 60 * 60 * 1000 );
	mwTime = new mw.Time( realDate );
	assert.strictEqual( mwTime.getHumanTimestamp(), mw.msg( 'ago', mw.msg( 'days', 1 ) ) );
} );

QUnit.test( 'Create valid mw.Time object with Date object, and make an MW-style timestamp', 1, function ( assert ) {
	var realDate = new Date( 2012, 03, 01, 10, 12, 14 );
	var mwTime = new mw.Time( realDate );
	assert.strictEqual( mwTime.getMwTimestamp(), '20120401101214' );
} );

QUnit.test( 'Create a new mw.Time object with no arguments, make sure it is the current time', 1, function ( assert ) {
	var realDate = new Date();
	var mwTime = new mw.Time();
	assert.strictEqual( mwTime.dateObj.toString(), realDate.toString() );
} );

QUnit.test( 'Intentionally cause an error', 1, function ( assert ) {
	assert.throws( function () {
		var mwTime = new mw.Time( 500 );
	}, Error, 'We cause an error by supplying an integer as the constructor argument' );
} );
