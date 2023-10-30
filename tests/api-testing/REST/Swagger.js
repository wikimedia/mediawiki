'use strict';

const { assert, REST } = require( 'api-testing' );
const SwaggerParser = require( '@apidevtools/swagger-parser' );
const request = require( 'superagent' );

describe( 'OpenAPI Self-Documentation', () => {
	const client = new REST( 'rest.php/coredev/v0' );

	it( 'Conforms to the OpenAPI schema', async () => {
		// NOTE: the module prefix "-" can be used to get a spec for the empty prefix
		const { status, body, text } = await client.get( '/specs/module/-' );
		assert.deepEqual( status, 200, text );

		try {
			await SwaggerParser.validate( body, {
				continueOnError: false,
				parse: {
					json: false,
					yaml: false,
					text: false
				},
				resolve: {
					file: false,
					http: false
				},
				dereference: {
					circular: false
				},
				validate: {
					spec: true
				}
			} );
		} catch ( err ) {
			assert.fail( err.message );
		}
	} );

	it( 'Returns sensible meta-data', async () => {
		const { body: spec } = await client.get( '/specs/module/-' );
		assert.nestedProperty( spec, 'info' );
		assert.nestedProperty( spec, 'servers' );
		assert.isDefined( spec.servers[ 0 ], 'first server' );
		assert.nestedProperty( spec.servers[ 0 ], 'url' );

		// Ensure that the server URL points to the same API root again, so we can load
		// the same swagger spec from it.
		const url = spec.servers[ 0 ].url + 'coredev/v0/specs/module/-';
		const { status, body: spec2, text } = await request( url );
		assert.deepEqual( status, 200, text );

		assert.nestedProperty( spec2, 'openapi' );
		assert.nestedProperty( spec2, 'info' );
		assert.deepEqual( spec2.info, spec.info );
		assert.deepEqual( spec2.servers, spec.servers );
	} );

} );
