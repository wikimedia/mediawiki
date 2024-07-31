'use strict';

describe( 'legacy Page Source', () => {
	// Cache deletion ensures tests will execute for both legacy and module paths
	// Doing this twice protects against changes in test execution order
	const testsFile = __dirname + '/content.v1/Page.js';
	delete require.cache[ testsFile ];
	require( testsFile ).init( 'rest.php/v1' );
	delete require.cache[ testsFile ];
} );
