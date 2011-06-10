// Return true to ignore
var mwTestIgnore = function( val, tester, funcPath ) {

	// Don't record methods of the properties of mw.Map instances
	// Because we're therefor skipping any injection for
	// "new mw.Map()", manually set it to true here.
	if ( val instanceof mw.Map ) {
		tester.methodCallTracker['Map'] = true;
		return true;
	}

	// Don't record methods of the properties of a jQuery object
	if ( val instanceof $ ) {
		return true;
	}

	return false;
};

var mwTester = new CompletenessTest( mw, mwTestIgnore );
