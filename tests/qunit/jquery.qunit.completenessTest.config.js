// Return true to ignore
var mwTestIgnore = function( val, tester, funcPath ) {

	// Don't record methods of the properties of constructors,
	// to avoid getting into a loop (prototype.constructor.prototype..).
	// Since we're therefor skipping any injection for
	// "new mw.Foo()", manually set it to true here.
	if ( val instanceof mw.Map ) {
		tester.methodCallTracker['Map'] = true;
		return true;
	}
	if ( val instanceof mw.Title ) {
		tester.methodCallTracker['Title'] = true;
		return true;
	}

	// Don't record methods of the properties of a jQuery object
	if ( val instanceof $ ) {
		return true;
	}

	return false;
};

var mwTester = new CompletenessTest( mw, mwTestIgnore );
