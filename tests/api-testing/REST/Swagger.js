'use strict';

const { assert, REST } = require( 'api-testing' );
const SwaggerParser = require( '@apidevtools/swagger-parser' );
const request = require( 'superagent' );

describe( 'Self-Documentation', () => {
	const client = new REST( 'rest.php/specs/v0' );

	describe( 'OpenAPI module specs', () => {
		it( 'Conforms to the OpenAPI schema', async () => {
			// NOTE: the module prefix "-" can be used to get a spec for the empty prefix
			const { status, body, text } = await client.get( '/module/-' );
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
			const { body: spec } = await client.get( '/module/-' );
			assert.nestedProperty( spec, 'info' );
			assert.nestedProperty( spec, 'servers' );
			assert.isDefined( spec.servers[ 0 ], 'first server' );
			assert.nestedProperty( spec.servers[ 0 ], 'url' );

			// Ensure that the server URL points to the same API root again, so we can load
			// the same swagger spec from it.
			const url = spec.servers[ 0 ].url + '/specs/v0/module/-';
			const { status, body: spec2, text } = await request( url );
			assert.deepEqual( status, 200, text );

			assert.nestedProperty( spec2, 'openapi' );
			assert.nestedProperty( spec2, 'info' );
			assert.deepEqual( spec2.info, spec.info );
			assert.deepEqual( spec2.servers, spec.servers );
		} );
	} );

	describe( 'discovery document', () => {
		it( 'contains basic info', async () => {
			const { body: ddoc } = await client.get( '/discovery' );
			assert.nestedProperty( ddoc, 'info' );

			const { info } = ddoc;
			assert.nestedProperty( info, 'title' );
			assert.nestedProperty( info, 'license' );
			assert.nestedProperty( info.license, 'url' );
			assert.nestedProperty( info, 'contact' );
			assert.nestedProperty( info.contact, 'email' );
		} );

		it( 'contains module list', async () => {
			const { body: ddoc } = await client.get( '/discovery' );
			assert.nestedProperty( ddoc, 'modules' );

			const { modules: dir } = ddoc;
			for ( const id in dir ) {
				const module = dir[ id ];
				assert.nestedProperty( module, 'base' );
				assert.nestedProperty( module, 'spec' );
			}
		} );

		it( 'contains usable servers', async () => {
			const { body: ddoc } = await client.get( '/discovery' );
			assert.nestedProperty( ddoc, 'servers' );
			assert.isDefined( ddoc.servers[ 0 ], 'first server' );

			const server = ddoc.servers[ 0 ];
			assert.nestedProperty( server, 'url' );

			// Ensure that the server URL points to the same API root again,
			// so we can load the same discovery document from it.
			const url = server.url + '/specs/v0/discovery';
			const { status, body: ddoc2, text } = await request( url );
			assert.deepEqual( status, 200, url + '\n' + text );
			assert.deepEqual( ddoc2, ddoc );
		} );
	} );
} );
