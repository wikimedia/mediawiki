module( 'mediawiki.Time', QUnit.newMwEnvironment() );

test( '-- Initial check', function () {
	expect( 2 );

	// Make sure the mw.msg dependency loaded.
	ok( mw.msg, 'mw.msg defined' );

	// Ensure we have a generic mw.Time constructor.
	ok( mw.Time, 'mw.Time defined' );
} );

test( 'Parse MW timestamp into valid mw.Time object with proper data', function () {
	expect( 5 );
	var mwTimestamp = '20120726115134';
	var realDate = new Date( 2012, 06, 26, 11, 51, 34 );
	var mwTime = new mw.Time( mwTimestamp );
	var difference = mwTime.dateObj - realDate;
	strictEqual( mwTime instanceof mw.Time, true, 'mw.Time object is the right type' );
	strictEqual( mwTime.dateObj instanceof Date, true, 'Date object is the right type' );
	strictEqual( isNaN( mwTime.dateObj.getTime() ), false, 'Date object is valid' );
	strictEqual( difference, 0, 'Got the right date' );
	deepEqual( realDate, mwTime.dateObj, 'The two Date objects are exactly the same' );
} );

test( 'Parse ISO 8601 time string into a valid mw.Time object with proper data', function () {
	expect( 6 );
	var realDate = new Date( 2012, 03, 28, 11, 30, 15 );
	var isoTimestamp = realDate.toISOString();
	var mwTime = new mw.Time( isoTimestamp );
	var difference = mwTime.dateObj - realDate;
	strictEqual( mwTime instanceof mw.Time, true, 'mw.Time object is the right type' );
	strictEqual( mwTime.dateObj instanceof Date, true, 'Date object is the right type' );
	strictEqual( isNaN( mwTime.dateObj.getTime() ), false, 'Date object is valid' );
	strictEqual( difference, 0, 'Got the right date' );
	strictEqual( isoTimestamp, mwTime.dateObj.toISOString(), 'No data was lost' );
	deepEqual( realDate, mwTime.dateObj, 'The two Date objects are exactly the same' );
} );

test( 'Create valid mw.Time object with Date object, and make instances of the ago message', function () {
	expect( 5 );
	var realDate = new Date();
	var mwTime = new mw.Time( realDate );
	strictEqual( mwTime.getHumanTimestamp(), mw.msg( 'justnow' ) );

	realDate = new Date( Date.now() - 1000 );
	mwTime = new mw.Time( realDate );
	strictEqual( mwTime.getHumanTimestamp(), mw.msg( 'ago', mw.msg( 'seconds', 1 ) ) );

	realDate = new Date( Date.now() - 60 * 1000 );
	mwTime = new mw.Time( realDate );
	strictEqual( mwTime.getHumanTimestamp(), mw.msg( 'ago', mw.msg( 'minutes', 1 ) ) );

	realDate = new Date( Date.now() - 60 * 60 * 1000 );
	mwTime = new mw.Time( realDate );
	strictEqual( mwTime.getHumanTimestamp(), mw.msg( 'ago', mw.msg( 'hours', 1 ) ) );

	realDate = new Date( Date.now() - 24 * 60 * 60 * 1000 );
	mwTime = new mw.Time( realDate );
	strictEqual( mwTime.getHumanTimestamp(), mw.msg( 'ago', mw.msg( 'days', 1 ) ) );
} );

test( 'Create valid mw.Time object with Date object, and make an MW-style timestamp', function () {
	expect( 1 );
	var realDate = new Date( 2012, 03, 01, 10, 12, 14 );
	var mwTime = new mw.Time( realDate );
	strictEqual( mwTime.getMwTimestamp(), '20120401101214' );
} );

test( 'Create a new mw.Time object with no arguments, make sure it is the current time', function () {
	expect( 1 );
	var realDate = new Date();
	var mwTime = new mw.Time();
	deepEqual( mwTime.dateObj, realDate );
} );

test( 'Intentionally cause an error', function () {
	expect( 1 );
	raises( function () {
		var mwTime = new mw.Time( 500 );
	}, Error, 'We cause an error by supplying an integer as the constructor argument' );
} );
