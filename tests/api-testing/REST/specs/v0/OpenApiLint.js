'use strict';

const { openApiLinter } = require( 'api-testing' );

describe( 'OpenAPI linter', () => {
	it( 'lints the REST API OAD without failing the build', async () => {
		// This never throws - it only logs findings to the test output.
		// See the api-testing package's lib/openApiLinter.js for details.
		await openApiLinter.lint();
	} );
} );
