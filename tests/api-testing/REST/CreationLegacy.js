'use strict';

describe( 'legacy POST /page', () => {
	// Cache deletion ensures tests will execute for both legacy and module paths
	// Doing this twice protects against changes in test execution order
	const testsFile = __dirname + '/content/v1/Creation.js';
	delete require.cache[ testsFile ];
	require( testsFile ).init( '/v1', '/-' );
	delete require.cache[ testsFile ];
} );
