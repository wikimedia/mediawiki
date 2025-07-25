/** Cases for testing the Parsoid API through HTTP */

'use strict';

const { action, assert, REST, utils } = require( 'api-testing' );

const chai = require( 'chai' );
const expect = chai.expect;

const chaiResponseValidator = require( 'chai-openapi-response-validator' ).default;

const domino = require( 'domino' );
const should = require( 'chai' ).should();
const semver = require( 'semver' );
const fs = require( 'fs' );

const parsoidOptions = {
	// Limits from DevelopmentSettings.php
	limits: {
		// Measured in bytes, per ParserOptions::getMaxIncludeSize.
		wt2html: { maxWikitextSize: 20 * 1024 },

		// Measured in characters.
		html2wt: { maxHTMLSize: 100 * 1024 }
	}
};

// FIXME(T283875): These should all be re-enabled
const skipForNow = true;

// Minimal version required, caret semantic applies
const defaultContentVersion = '2.4.0';

// section wrappers are a distraction from the main business of
// this file which is to verify functionality of API end points
// independent of what they are returning and computing.
//
// Verifying the correctness of content is actually the job of
// parser tests and other tests.
//
// So, hide most of that that distraction in a helper.
//
// Right now, all uses of this helper have empty lead sections.
// But, maybe in the future, this may change. So, retain the option.
function validateDoc( doc, nodeName, emptyLead ) {
	const leadSection = doc.body.firstChild;
	leadSection.nodeName.should.equal( 'SECTION' );
	if ( emptyLead ) {
		// Could have whitespace and comments
		leadSection.childElementCount.should.equal( 0 );
	}
	const nonEmptySection = emptyLead ? leadSection.nextSibling : leadSection;
	nonEmptySection.firstChild.nodeName.should.equal( nodeName );
}

// Matcher checking that the result status is 200, including the actual response
// text in the output if it isn't.
function status200( res ) {
	assert.strictEqual( res.status, 200, res.text );
}

// Return a matcher function that checks whether a content type matches the given parameters.
function contentTypeMatcher( expectedMime, expectedSpec, expectedVersion ) {
	const pattern = /^([-\w]+\/[-\w]+); charset=utf-8; profile="https:\/\/www.mediawiki.org\/wiki\/Specs\/([-\w]+)\/(\d+\.\d+\.\d+)"$/;

	return ( actual ) => {
		const parts = pattern.exec( actual );
		if ( !parts ) {
			return false;
		}

		const [ , mime, spec, version ] = parts;

		// match version using caret semantics
		if ( !semver.satisfies( version, `^${ expectedVersion || defaultContentVersion }` ) ) {
			return false;
		}

		if ( mime !== expectedMime || spec !== expectedSpec ) {
			return false;
		}

		return true;
	};
}

function validateSpec( response ) {
	// eslint-disable-next-line no-unused-expressions
	expect( response ).to.satisfyApiSpec;
}

function validateDefaultSpec( response ) {
	// eslint-disable-next-line no-unused-expressions
	expect( response.text ).to.satisfySchemaInApiSpec( 'GenericErrorResponseModel' );
}

// TODO: Replace all occurrences of (Lint Page/Lint_Page) with `page`.
describe( '/transform/ endpoint', () => {
	const client = new REST();
	const endpointPrefix = client.pathPrefix = 'rest.php';
	const page = utils.title( 'TransformSource_' );
	const pageEncoded = encodeURIComponent( page );
	const pageContent = '{|\nhi\n|ho\n|}';
	let revid, openApiSpec;

	before( async function () {
		this.timeout( 30000 );

		const alice = await action.alice();

		// Create pages
		let edit = await alice.edit( page, { text: pageContent } );
		edit.result.should.equal( 'Success' );
		revid = edit.newrevid;

		edit = await alice.edit( 'JSON Page', {
			text: '[1]', contentmodel: 'json'
		} );
		edit.result.should.equal( 'Success' );

		const { status, text } = await client.get( '/specs/v0/module/-' );
		assert.deepEqual( status, 200 );

		openApiSpec = JSON.parse( text );
		chai.use( chaiResponseValidator( openApiSpec ) );
	} );

	describe( 'formats', () => {

		it( 'should accept application/x-www-form-urlencoded', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.type( 'form' )
				.send( {
					wikitext: '== h2 =='
				} )
				.expect( status200 )
				.expect( ( res ) => {
					validateDoc( domino.createDocument( res.text ), 'H2', true );
				} )
				.end( done );
		} );

		it( 'should accept application/json', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.type( 'json' )
				.send( {
					wikitext: '== h2 =='
				} )
				.expect( status200 )
				.expect( ( res ) => {
					validateDoc( domino.createDocument( res.text ), 'H2', true );
					validateSpec( res );
				} )
				.end( done );
		} );

		it( 'should accept multipart/form-data', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.field( 'wikitext', '== h2 ==' )
				.expect( status200 )
				.expect( ( res ) => {
					validateDoc( domino.createDocument( res.text ), 'H2', true );
				} )
				.end( done );
		} );
	} ); // formats

	const acceptableHtmlResponse = function ( contentVersion, expectFunc ) {
		return function ( res ) {
			res.statusCode.should.equal( 200, res.txt );
			res.headers.should.have.property( 'content-type' );
			res.headers[ 'content-type' ].should.satisfy(
				contentTypeMatcher( 'text/html', 'HTML', contentVersion )
			);
			res.text.should.not.equal( '' );
			if ( expectFunc ) {
				return expectFunc( res.text );
			}
		};
	};

	const acceptablePageBundleResponse = function ( contentVersion, expectFunc ) {
		return function ( res ) {
			res.statusCode.should.equal( 200, res.txt );
			res.headers.should.have.property( 'content-type' );
			res.headers[ 'content-type' ].should.satisfy( contentTypeMatcher( 'application/json', 'pagebundle', contentVersion ) );
			res.body.should.have.property( 'html' );
			res.body.html.should.have.property( 'headers' );
			res.body.html.headers.should.have.property( 'content-type' );
			res.body.html.headers[ 'content-type' ].should.satisfy( contentTypeMatcher( 'text/html', 'HTML', contentVersion ) );
			res.body.html.should.have.property( 'body' );
			res.body.should.have.property( 'data-parsoid' );
			res.body[ 'data-parsoid' ].should.have.property( 'headers' );
			res.body[ 'data-parsoid' ].headers.should.have.property( 'content-type' );
			res.body[ 'data-parsoid' ].headers[ 'content-type' ].should.satisfy( contentTypeMatcher( 'application/json', 'data-parsoid', contentVersion ) );
			res.body[ 'data-parsoid' ].should.have.property( 'body' );

			if ( semver.gte( contentVersion, '999.0.0' ) ) {
				res.body.should.have.property( 'data-mw' );
				res.body[ 'data-mw' ].should.have.property( 'headers' );
				res.body[ 'data-mw' ].headers.should.have.property( 'content-type' );
				res.body[ 'data-mw' ].headers[ 'content-type' ].should.satisfy( contentTypeMatcher( 'application/json', 'data-mw', contentVersion ) );
				res.body[ 'data-mw' ].should.have.property( 'body' );
			}
			if ( expectFunc ) {
				return expectFunc( res.body.html.body );
			}
		};
	};

	describe( 'accepts', () => {

		it( 'should not accept requests for older content versions (html)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.set( 'Accept', 'text/html; profile="https://www.mediawiki.org/wiki/Specs/HTML/0.0.0"' )
				.send( { wikitext: '== h2 ==' } )
				.expect( 406 )
				.expect( ( res ) => {
					// FIXME: See skipped html error test above
					JSON.parse( res.error.text ).errorKey.should.equal(
						'rest-unsupported-target-format'
					);
				} )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should not accept requests for older content versions (pagebundle)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.set( 'Accept', 'application/json; profile="https://www.mediawiki.org/wiki/Specs/HTML/0.0.0"' )
				.send( { wikitext: '== h2 ==' } )
				.expect( 406 )
				.expect( ( res ) => {
					JSON.parse( res.error.text ).message.should.equal(
						'Not acceptable'
					);
					validateDefaultSpec( res );
				} )
				.end( done );
		} );

		it( 'should not accept requests for other profiles (html)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.set( 'Accept', 'text/html; profile="something different"' )
				.send( { wikitext: '== h2 ==' } )
				.expect( 406 )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should not accept requests for other profiles (pagebundle)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.set( 'Accept', 'application/json; profile="something different"' )
				.send( { wikitext: '== h2 ==' } )
				.expect( 406 )
				.expect( ( res ) => {
					validateDefaultSpec( res );

				} )
				.end( done );
		} );

		it( 'should accept wildcards (html)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.set( 'Accept', '*/*' )
				.send( { wikitext: '== h2 ==' } )
				.expect( status200 )
				.expect( ( res ) => {
					validateSpec( res );
				} )
				.expect( acceptableHtmlResponse( defaultContentVersion ) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should accept wildcards (pagebundle)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.set( 'Accept', '*/*' )
				.send( { wikitext: '== h2 ==' } )
				.expect( status200 )
				.expect( ( res ) => {
					validateSpec( res );
				} )
				.expect( acceptablePageBundleResponse( defaultContentVersion ) )
				.end( done );
		} );

		// T347426: Support for non-default major HTML versions has been disabled
		it.skip( 'should prefer higher quality (html)', ( done ) => {
			const contentVersion = '999.0.0';
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.set( 'Accept',
					'text/html; profile="https://www.mediawiki.org/wiki/Specs/HTML/2.4.0"; q=0.5,' +
					'text/html; profile="https://www.mediawiki.org/wiki/Specs/HTML/999.0.0"; q=0.8' )
				.send( { wikitext: '== h2 ==' } )
				.expect( status200 )
				.expect( acceptableHtmlResponse( contentVersion ) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should prefer higher quality (pagebundle)', ( done ) => {
			const contentVersion = '999.0.0';
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.set( 'Accept',
					'application/json; profile="https://www.mediawiki.org/wiki/Specs/pagebundle/2.4.0"; q=0.5,' +
					'application/json; profile="https://www.mediawiki.org/wiki/Specs/pagebundle/999.0.0"; q=0.8' )
				.send( { wikitext: '== h2 ==' } )
				.expect( status200 )
				.expect( ( res ) => {
					validateSpec( res );
				} )
				.expect( acceptablePageBundleResponse( contentVersion ) )
				.end( done );
		} );

		it( 'should accept requests for the latest content version (html)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.send( { wikitext: '== h2 ==' } )
				.expect( status200 )
				.expect( acceptableHtmlResponse( defaultContentVersion ) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should accept requests for the latest content version (pagebundle)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.send( { wikitext: '== h2 ==' } )
				.expect( status200 )
				.expect( acceptablePageBundleResponse( defaultContentVersion ) )
				.end( done );
		} );

		it( 'should accept requests for content version 2.x (html)', ( done ) => {
			const contentVersion = '2.4.0';
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.set( 'Accept', 'text/html; profile="https://www.mediawiki.org/wiki/Specs/HTML/' + contentVersion + '"' )
				.send( { wikitext: '{{1x|hi}}' } )
				.expect( status200 )
				.expect( acceptableHtmlResponse( contentVersion ) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should accept requests for content version 2.x (pagebundle)', ( done ) => {
			const contentVersion = '2.4.0';
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.set( 'Accept', 'application/json; profile="https://www.mediawiki.org/wiki/Specs/pagebundle/' + contentVersion + '"' )
				.send( { wikitext: '{{1x|hi}}' } )
				.expect( status200 )
				.expect( ( res ) => {
					validateSpec( res );
				} )
				.expect( acceptablePageBundleResponse( contentVersion, ( html ) => {
					// In < 999.x, data-mw is still inline.
					html.should.match( /\s+data-mw\s*=\s*['"]/ );
				} ) )
				.end( done );
		} );

		// Note that these tests aren't that useful directly after a major version bump

		it( 'should accept requests for older content version 2.x (html)', ( done ) => {
			const contentVersion = '2.4.0';
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.set( 'Accept', 'text/html; profile="https://www.mediawiki.org/wiki/Specs/HTML/2.0.0"' ) // Keep this on the older version
				.send( { wikitext: '{{1x|hi}}' } )
				.expect( status200 )
				.expect( acceptableHtmlResponse( contentVersion ) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should accept requests for older content version 2.x (pagebundle)', ( done ) => {
			const contentVersion = '2.4.0';
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.set( 'Accept', 'application/json; profile="https://www.mediawiki.org/wiki/Specs/pagebundle/2.0.0"' ) // Keep this on the older version
				.send( { wikitext: '{{1x|hi}}' } )
				.expect( status200 )
				.expect( ( res ) => {
					validateSpec( res );
				} )
				.expect( acceptablePageBundleResponse( contentVersion, ( html ) => {
					// In < 999.x, data-mw is still inline.
					html.should.match( /\s+data-mw\s*=\s*['"]/ );
				} ) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should sanity check 2.x content (pagebundle)', ( done ) => {
			// Missing files in wiki
			const contentVersion = '2.4.0';
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.set( 'Accept', 'application/json; profile="https://www.mediawiki.org/wiki/Specs/pagebundle/' + contentVersion + '"' )
				.send( { wikitext: '[[File:Audio.oga]]' } )
				.expect( status200 )
				.expect( ( res ) => {
					validateSpec( res );
				} )
				.expect( acceptablePageBundleResponse( contentVersion, ( html ) => {
					const doc = domino.createDocument( html );
					doc.querySelectorAll( 'audio' ).length.should.equal( 1 );
					doc.querySelectorAll( 'video' ).length.should.equal( 0 );
				} ) )
				.end( done );
		} );

		// T347426: Support for non-default major HTML versions has been disabled
		it.skip( 'should accept requests for content version 999.x (html)', ( done ) => {
			const contentVersion = '999.0.0';
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.set( 'Accept', 'text/html; profile="https://www.mediawiki.org/wiki/Specs/HTML/' + contentVersion + '"' )
				.send( { wikitext: '{{1x|hi}}' } )
				.expect( status200 )
				.expect( acceptableHtmlResponse( contentVersion ) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should accept requests for content version 999.x (pagebundle)', ( done ) => {
			const contentVersion = '999.0.0';
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.set( 'Accept', 'application/json; profile="https://www.mediawiki.org/wiki/Specs/pagebundle/' + contentVersion + '"' )
				.send( { wikitext: '{{1x|hi}}' } )
				.expect( status200 )
				.expect( ( res ) => {
					validateSpec( res );
				} )
				.expect( acceptablePageBundleResponse( contentVersion, ( html ) => {
					// In 999.x, data-mw is in the pagebundle.
					html.should.not.match( /\s+data-mw\s*=\s*['"]/ );
				} ) )
				.end( done );
		} );

	} ); // accepts

	const validWikitextResponse = function ( expected ) {
		return function ( res ) {
			res.statusCode.should.equal( 200 );
			res.headers.should.have.property( 'content-type' );
			res.headers[ 'content-type' ].should.equal(
				// note that express does some reordering
				'text/plain; charset=utf-8; profile="https://www.mediawiki.org/wiki/Specs/wikitext/1.0.0"'
			);
			validateSpec( res );
			if ( expected !== undefined ) {
				res.text.should.equal( expected );
			} else {
				res.text.should.not.equal( '' );
			}
		};
	};

	const validHtmlResponse = function ( expectFunc ) {
		return function ( res ) {
			res.statusCode.should.equal( 200 );
			res.headers.should.have.property( 'content-type' );
			res.headers[ 'content-type' ].should.satisfy( contentTypeMatcher( 'text/html', 'HTML' ) );
			validateSpec( res );
			const doc = domino.createDocument( res.text );
			if ( expectFunc ) {
				return expectFunc( doc );
			} else {
				res.text.should.not.equal( '' );
			}
		};
	};

	const validPageBundleResponse = function ( expectFunc ) {
		return function ( res ) {
			res.statusCode.should.equal( 200 );
			validateSpec( res );
			res.body.should.have.property( 'html' );
			res.body.html.should.have.property( 'headers' );
			res.body.html.headers.should.have.property( 'content-type' );
			res.body.html.headers[ 'content-type' ].should.satisfy( contentTypeMatcher( 'text/html', 'HTML' ) );
			res.body.html.should.have.property( 'body' );
			res.body.should.have.property( 'data-parsoid' );
			res.body[ 'data-parsoid' ].should.have.property( 'headers' );
			res.body[ 'data-parsoid' ].headers.should.have.property( 'content-type' );
			res.body[ 'data-parsoid' ].headers[ 'content-type' ].should.satisfy( contentTypeMatcher( 'application/json', 'data-parsoid' ) );
			res.body[ 'data-parsoid' ].should.have.property( 'body' );
			// TODO: Check data-mw when 999.x is the default.
			console.assert( !semver.gte( defaultContentVersion, '999.0.0' ) );
			const doc = domino.createDocument( res.body.html.body );
			if ( expectFunc ) {
				return expectFunc( doc, res.body[ 'data-parsoid' ].body );
			}
		};
	};

	describe( 'wt2lint', () => {

		it( 'should lint the given wikitext', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/lint/' )
				.send( {
					wikitext: {
						headers: {
							'content-type': 'text/plain;profile="https://www.mediawiki.org/wiki/Specs/wikitext/1.0.0"'
						},
						body: '{|\nhi\n|ho\n|}'
					}
				} )
				.expect( status200 )
				.expect( ( res ) => {
					res.body.should.be.instanceof( Array );
					res.body.length.should.equal( 1 );
					res.body[ 0 ].type.should.equal( 'fostered' );
					validateSpec( res );
				} )
				.end( done );
		} );

		it( 'should lint the given revision, transform', ( done ) => {
			client.req
				.post( `${ endpointPrefix }/v1/transform/wikitext/to/lint/${ pageEncoded }/${ revid }` )
				.send( {} )
				.expect( status200 )
				.expect( ( res ) => {
					res.body.should.be.instanceof( Array );
					res.body.length.should.equal( 1 );
					res.body[ 0 ].type.should.equal( 'fostered' );
					validateSpec( res );
				} )
				.end( done );
		} );

		it( 'should lint the given revision, transform (GET)', ( done ) => {
			client.req
				.get( `${ endpointPrefix }/v1/transform/wikitext/to/lint/${ pageEncoded }/${ revid }` )
				.expect( status200 )
				.expect( ( res ) => {
					res.body.should.be.instanceof( Array );
					res.body.length.should.equal( 1 );
					res.body[ 0 ].type.should.equal( 'fostered' );
					validateSpec( res );
				} )
				.end( done );
		} );

		it( 'should lint the given page, transform', ( done ) => {
			client.req
				.post( `${ endpointPrefix }/v1/transform/wikitext/to/lint/${ pageEncoded }` )
				.send( {} )
				.expect( status200 )
				.expect( ( res ) => {
					res.body.should.be.instanceof( Array );
					res.body.length.should.equal( 1 );
					res.body[ 0 ].type.should.equal( 'fostered' );
					validateSpec( res );
				} )
				.end( done );
		} );

		it( 'should lint the given page, transform (GET)', ( done ) => {
			client.req
				.get( `${ endpointPrefix }/v1/transform/wikitext/to/lint/${ pageEncoded }` )
				.send( {} )
				.expect( status200 )
				.expect( ( res ) => {
					res.body.should.be.instanceof( Array );
					res.body.length.should.equal( 1 );
					res.body[ 0 ].type.should.equal( 'fostered' );
					validateSpec( res );
				} )
				.end( done );
		} );

		it( 'should lint multibyte wikitext', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/lint/' )
				.send( {
					wikitext: {
						headers: {
							'content-type': 'text/plain;profile="https://www.mediawiki.org/wiki/Specs/wikitext/1.0.0"'
						},
						body: "ăăă ''test"
					}
				} )
				.expect( status200 )
				.expect( ( res ) => {
					res.body.should.be.instanceof( Array );
					res.body.length.should.equal( 1 );
					res.body[ 0 ].type.should.equal( 'missing-end-tag' );
					// res.body[ 0 ].dsr.should.eql( [ 7, 13, 2, 0 ] ); // 'byte' offsets
					res.body[ 0 ].dsr.should.eql( [ 4, 10, 2, 0 ] ); // 'ucs2' offsets
					validateSpec( res );
				} )
				.end( done );
		} );

	} );

	describe( 'wt2html', () => {

		it( 'should accept wikitext as a string for html', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.send( {
					wikitext: '== h2 =='
				} )
				.expect( validHtmlResponse( ( doc ) => {
					validateDoc( doc, 'H2', true );
				} ) )
				.end( done );
		} );

		it( 'should accept json contentmodel as a string for html', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.send( {
					wikitext: '{"1":2}',
					contentmodel: 'json'
				} )
				.expect( validHtmlResponse( ( doc ) => {
					doc.body.firstChild.nodeName.should.equal( 'TABLE' );
				} ) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should accept wikitext as a string for pagebundle', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.send( {
					wikitext: '== h2 =='
				} )
				.expect( validPageBundleResponse( ( doc ) => {
					validateDoc( doc, 'H2', true );
				} ) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should accept json contentmodel as a string for pagebundle', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.send( {
					wikitext: '{"1":2}',
					contentmodel: 'json'
				} )
				.expect( validPageBundleResponse( ( doc ) => {
					doc.body.firstChild.nodeName.should.equal( 'TABLE' );
					should.not.exist( doc.querySelector( '*[typeof="mw:Error"]' ) );
				} ) )
				.end( done );
		} );

		it( 'should accept wikitext with headers', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.send( {
					wikitext: {
						headers: {
							'content-type': 'text/plain;profile="https://www.mediawiki.org/wiki/Specs/wikitext/1.0.0"'
						},
						body: '== h2 =='
					}
				} )
				.expect( validHtmlResponse( ( doc ) => {
					validateDoc( doc, 'H2', true );
				} ) )
				.end( done );
		} );

		it( 'should require a title when no wikitext is provided (html)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.send( {} )
				.expect( 400 )
				.expect( ( res ) => {
					validateDefaultSpec( res );
				} )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should require a title when no wikitext is provided (pagebundle)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.send( {} )
				.expect( 400 )
				.expect( ( res ) => {
					validateDefaultSpec( res );
				} )
				.end( done );
		} );

		it( 'should error when revision not found (transform, wt2html)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/Doesnotexist' )
				.send( {} )
				.expect( 404 )
				.expect( ( res ) => {
					validateDefaultSpec( res );
				} )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should error when revision not found (transform, wt2pb)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/Doesnotexist' )
				.send( {} )
				.expect( 404 )
				.expect( ( res ) => {
					validateDefaultSpec( res );
				} )
				.end( done );
		} );

		it( 'should accept an original title (html)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.send( {
					// no revid or wikitext source provided
					original: {
						title: page
					}
				} )
				.expect( validHtmlResponse() )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should accept an original title (pagebundle)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.send( {
					// no revid or wikitext source provided
					original: {
						title: page
					}
				} )
				.expect( validPageBundleResponse() )
				.end( done );
		} );

		it( 'should accept an original title, other than main', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.send( {
					original: {
						title: page
					}
				} )
				.expect( validHtmlResponse() )
				.end( done );
		} );

		it( 'should not require a title when empty wikitext is provided (html)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.send( {
					wikitext: ''
				} )
				.expect( validHtmlResponse( ( doc ) => {
					doc.body.children.length.should.equal( 1 ); // empty lead section
					doc.body.firstChild.nodeName.should.equal( 'SECTION' );
					doc.body.firstChild.children.length.should.equal( 0 );
				} ) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should not require a title when empty wikitext is provided (pagebundle)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.send( {
					wikitext: ''
				} )
				.expect( validPageBundleResponse() )
				.end( done );
		} );

		it( 'should not require a title when wikitext is provided', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.send( {
					wikitext: '== h2 =='
				} )
				.expect( validHtmlResponse( ( doc ) => {
					validateDoc( doc, 'H2', true );
				} ) )
				.end( done );
		} );

		it( 'should not require a rev id when wikitext and a title is provided', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/Main_Page' )
				.send( {
					wikitext: '== h2 =='
				} )
				.expect( validHtmlResponse( ( doc ) => {
					validateDoc( doc, 'H2', true );
				} ) )
				.end( done );
		} );

		it( 'should accept the wikitext source as original data', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/Main_Page/1' )
				.send( {
					original: {
						wikitext: {
							headers: {
								'content-type': 'text/plain;profile="https://www.mediawiki.org/wiki/Specs/wikitext/1.0.0"'
							},
							body: '== h2 =='
						}
					}
				} )
				.expect( validHtmlResponse( ( doc ) => {
					validateDoc( doc, 'H2', true );
				} ) )
				.end( done );
		} );

		it( 'should use the proper source text', function ( done ) {
			if ( skipForNow ) {
				return this.skip();
			} // Missing template 1x
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/Main_Page/1' )
				.send( {
					original: {
						wikitext: {
							headers: {
								'content-type': 'text/plain;profile="https://www.mediawiki.org/wiki/Specs/wikitext/1.0.0"'
							},
							body: '{{1x|foo|bar=bat}}'
						}
					}
				} )
				.expect( validHtmlResponse( ( doc ) => {
					validateDoc( doc, 'P', false );
					const span = doc.querySelector( 'span[typeof="mw:Transclusion"]' );
					const dmw = JSON.parse( span.getAttribute( 'data-mw' ) );
					const template = dmw.parts[ 0 ].template;
					template.target.wt.should.equal( '1x' );
					template.params[ 1 ].wt.should.equal( 'foo' );
					template.params.bar.wt.should.equal( 'bat' );
				} ) )
				.end( done );
		} );

		it( 'should accept the wikitext source as original without a title or revision', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.send( {
					original: {
						wikitext: {
							headers: {
								'content-type': 'text/plain;profile="https://www.mediawiki.org/wiki/Specs/wikitext/1.0.0"'
							},
							body: '== h2 =='
						}
					}
				} )
				.expect( validHtmlResponse( ( doc ) => {
					validateDoc( doc, 'H2', true );
				} ) )
				.end( done );
		} );

		it( 'should respect body parameter in wikitext->html (body_only)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.send( {
					wikitext: "''foo''",
					body_only: 1
				} )
				.expect( validHtmlResponse() )
				.expect( ( res ) => {
					// v3 only returns children of <body>
					res.text.should.not.match( /<body/ );
					res.text.should.match( /<p/ );
				} )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should respect body parameter in wikitext->pagebundle requests (body_only)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.send( {
					wikitext: "''foo''",
					body_only: 1
				} )
				.expect( validPageBundleResponse() )
				.expect( ( res ) => {
					// v3 only returns children of <body>
					res.body.html.body.should.not.match( /<body/ );
					res.body.html.body.should.match( /<p/ );
					// No section wrapping in body-only mode
					res.body.html.body.should.not.match( /<section/ );
				} )
				.end( done );
		} );

		it( 'should implement subst - simple', function ( done ) {
			if ( skipForNow ) {
				return this.skip();
			} // Missing template 1x
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.send( { wikitext: '{{1x|foo}}', subst: 'true' } )
				.expect( validHtmlResponse( ( doc ) => {
					const body = doc.body;
					// <body> should have one child, <section>, the lead section
					body.childElementCount.should.equal( 1 );
					const p = body.firstChild.firstChild;
					p.nodeName.should.equal( 'P' );
					p.innerHTML.should.equal( 'foo' );
					// The <p> shouldn't be a template expansion, just a plain ol' one
					p.hasAttribute( 'typeof' ).should.equal( false );
					// and it shouldn't have any data-parsoid in it
					p.hasAttribute( 'data-parsoid' ).should.equal( false );
				} ) )
				.end( done );
		} );

		it( 'should implement subst - internal tranclusion', function ( done ) {
			if ( skipForNow ) {
				return this.skip();
			} // Missing template 1x
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.send( { wikitext: '{{1x|foo {{1x|bar}} baz}}', subst: 'true' } )
				.expect( validHtmlResponse( ( doc ) => {
					const body = doc.body;
					// <body> should have one child, <section>, the lead section
					body.childElementCount.should.equal( 1 );
					const p = body.firstChild.firstChild;
					p.nodeName.should.equal( 'P' );
					// The <p> shouldn't be a template expansion, just a plain ol' one
					p.hasAttribute( 'typeof' ).should.equal( false );
					// and it shouldn't have any data-parsoid in it
					p.hasAttribute( 'data-parsoid' ).should.equal( false );
					// The internal tranclusion should be presented as such
					const tplp = p.firstChild.nextSibling;
					tplp.nodeName.should.equal( 'SPAN' );
					tplp.getAttribute( 'typeof' ).should.equal( 'mw:Transclusion' );
					// And not have data-parsoid, so it's used as new content
					tplp.hasAttribute( 'data-parsoid' ).should.equal( false );
				} ) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should not allow subst with pagebundle', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.send( { wikitext: '{{1x|foo}}', subst: 'true' } )
				.expect( 501 )
				.expect( ( res ) => {
					validateDefaultSpec( res );
				} )
				.end( done );
		} );

		it( 'should return a request too large error when just over limit (post wt)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.send( {
					// One over limit.
					// Use single-byte characters, since the limit is in byte.
					wikitext: 'a'.repeat( parsoidOptions.limits.wt2html.maxWikitextSize + 1 )
				} )
				.expect( 413 )
				.expect( ( res ) => {
					validateDefaultSpec( res );
				} )
				.end( done );
		} );

		it( 'should not return a request too large error when just under limit (post wt)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.send( {
					// One under limit.
					// Use single-byte characters, since the limit is in byte.
					wikitext: 'a'.repeat( parsoidOptions.limits.wt2html.maxWikitextSize - 1 )
				} )
				.expect( 200 )
				.end( done );
		} );

		it( 'should add redlinks for transform (html)', function ( done ) {
			if ( skipForNow ) {
				return this.skip();
			} // Fix redlinks count, by creating pages
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
				.send( {
					wikitext: '[[Special:Version]] [[Doesnotexist]] [[Redirected]]'
				} )
				.expect( validHtmlResponse( ( doc ) => {
					doc.body.querySelectorAll( 'a' ).length.should.equal( 3 );
					const redLinks = doc.body.querySelectorAll( '.new' );
					redLinks.length.should.equal( 1 );
					redLinks[ 0 ].getAttribute( 'title' ).should.equal( 'Doesnotexist' );
					const redirects = doc.body.querySelectorAll( '.mw-redirect' );
					redirects.length.should.equal( 1 );
					redirects[ 0 ].getAttribute( 'title' ).should.equal( 'Redirected' );
				} ) )
				.end( done );
		} );

		it( 'should add redlinks for transform (pagebundle)', function ( done ) {
			if ( skipForNow ) {
				return this.skip();
			} // Fix redlinks count, by creating pages
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.send( {
					wikitext: '[[Special:Version]] [[Doesnotexist]] [[Redirected]]'
				} )
				.expect( validPageBundleResponse( ( doc ) => {
					doc.body.querySelectorAll( 'a' ).length.should.equal( 3 );
					const redLinks = doc.body.querySelectorAll( '.new' );
					redLinks.length.should.equal( 1 );
					redLinks[ 0 ].getAttribute( 'title' ).should.equal( 'Doesnotexist' );
					const redirects = doc.body.querySelectorAll( '.mw-redirect' );
					redirects.length.should.equal( 1 );
					redirects[ 0 ].getAttribute( 'title' ).should.equal( 'Redirected' );
				} ) )
				.end( done );
		} );

		// Continue to accept sr-el for a while in headers, to remain compatible
		// with apps which might still be sending the old codes
		[ 'sr-Latn', 'sr-el' ].forEach( ( srLatn ) => {
			describe( 'Variant conversion ' + srLatn, () => {
				it( 'should perform variant conversion for transform given pagelanguage in HTTP header (html)', ( done ) => {
					client.req
						.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
						.set( 'Accept-Language', srLatn )
						.set( 'Content-Language', 'sr' )
						.send( {
							wikitext: 'абвг abcd x'
						} )
						.expect( 'Content-Language', 'sr-Latn' )
						.expect( 'Vary', /\bAccept-Language\b/i )
						.expect( validHtmlResponse( ( doc ) => {
							doc.body.textContent.should.equal( 'abvg abcd x' );
						} ) )
						.end( done );
				} );

				it( 'should perform variant conversion for transform given pagelanguage in HTTP header (pagebundle)', function ( done ) {
					if ( skipForNow ) {
						return this.skip();
					} // page bundle not supported
					client.req
						.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
						.set( 'Accept-Language', srLatn )
						.set( 'Content-Language', 'sr' )
						.send( {
							wikitext: 'абвг abcd x'
						} )
						.expect( validPageBundleResponse( ( doc ) => {
							doc.body.textContent.should.equal( 'abvg abcd x' );
						} ) )
						.expect( ( res ) => {
							const headers = res.body.html.headers;
							headers.should.have.property( 'content-language' );
							headers.should.have.property( 'vary' );
							headers[ 'content-language' ].should.equal( 'sr-Latn' );
							headers.vary.should.match( /\bAccept-Language\b/i );
						} )
						.end( done );
				} );

				it( 'should perform variant conversion for transform given pagelanguage in JSON header (html)', ( done ) => {
					client.req
						.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
						.set( 'Accept-Language', srLatn )
						.send( {
							wikitext: {
								headers: {
									'content-language': 'sr'
								},
								body: 'абвг abcd x'
							}
						} )
						.expect( 'Content-Language', 'sr-Latn' )
						.expect( 'Vary', /\bAccept-Language\b/i )
						.expect( validHtmlResponse( ( doc ) => {
							doc.body.textContent.should.equal( 'abvg abcd x' );
						} ) )
						.end( done );
				} );

				it( 'should perform variant conversion for transform given pagelanguage in JSON header (pagebundle)', function ( done ) {
					if ( skipForNow ) {
						return this.skip();
					} // page bundle not supported
					client.req
						.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
						.set( 'Accept-Language', srLatn )
						.send( {
							wikitext: {
								headers: {
									'content-language': 'sr'
								},
								body: 'абвг abcd'
							}
						} )
						.expect( validPageBundleResponse( ( doc ) => {
							doc.body.textContent.should.equal( 'abvg abcd' );
						} ) )
						.expect( ( res ) => {
							const headers = res.body.html.headers;
							headers.should.have.property( 'content-language' );
							headers.should.have.property( 'vary' );
							headers[ 'content-language' ].should.equal( 'sr-Latn' );
							headers.vary.should.match( /\bAccept-Language\b/i );
						} )
						.end( done );
				} );

				it( 'should perform variant conversion for transform given pagelanguage from oldid (html)', ( done ) => {
					client.req
						.post( endpointPrefix + '/v1/transform/wikitext/to/html/' )
						.set( 'Accept-Language', srLatn )
						.set( 'Content-Language', 'sr' )
						.send( {
							original: { revid: 104 },
							wikitext: {
								body: 'абвг abcd x'
							}
						} )
						.expect( 'Content-Language', 'sr-Latn' )
						.expect( 'Vary', /\bAccept-Language\b/i )
						.expect( validHtmlResponse( ( doc ) => {
							doc.body.textContent.should.equal( 'abvg abcd x' );
						} ) )
						.end( done );
				} );

				it( 'should perform variant conversion for transform given pagelanguage from oldid (pagebundle)', function ( done ) {
					if ( skipForNow ) {
						return this.skip();
					} // page bundle not supported
					client.req
						.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
						.set( 'Accept-Language', srLatn )
						.send( {
							original: { revid: 104 },
							wikitext: 'абвг abcd'
						} )
						.expect( validPageBundleResponse( ( doc ) => {
							doc.body.textContent.should.equal( 'abvg abcd' );
						} ) )
						.expect( ( res ) => {
							const headers = res.body.html.headers;
							headers.should.have.property( 'content-language' );
							headers.should.have.property( 'vary' );
							headers[ 'content-language' ].should.equal( 'sr-Latn' );
							headers.vary.should.match( /\bAccept-Language\b/i );
						} )
						.end( done );
				} );

			} );
		} );

	} ); // end wt2html

	const getTextFromFile = function ( name ) {
		// eslint-disable-next-line security/detect-non-literal-fs-filename
		return fs.readFileSync( __dirname + '/../data/Transform/' + name, 'utf-8' ).trim();
	};

	describe( 'html2wt', () => {
		const htmlOfMainPageWithDataParsoid = getTextFromFile( 'MainPage-data-parsoid.html' );
		const htmlOfMainPageWithDataParsoid_1_1_1 = getTextFromFile( 'MainPage-data-parsoid-1.1.1.html' );
		const htmlOfMainPageOriginal = getTextFromFile( 'MainPage-original.html' );
		const dataParsoidOfMainPageOriginal = JSON.parse( getTextFromFile( 'MainPage-original.data-parsoid' ) );
		const htmlOfImage = getTextFromFile( 'Image.html' );
		const htmlOfImageWithDataMW = getTextFromFile( 'Image-data-mw.html' );

		it( 'should require html when serializing', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/' )
				.send( {} )
				.expect( 400 )
				.end( done );
		} );

		it( 'should error when revision not found (transform, html2wt)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/Doesnotexist/2020' )
				.send( {
					html: '<pre>hi ho</pre>'
				} )
				.expect( 404 )
				.end( done );
		} );

		it( 'should not error when oldid not supplied (transform, html2wt)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/Doesnotexist' )
				.send( {
					html: '<pre>hi ho</pre>'
				} )
				.expect( validWikitextResponse( ' hi ho\n' ) )
				.end( done );
		} );

		it( 'should accept html as a string', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/' )
				.send( {
					html: htmlOfMainPageWithDataParsoid
				} )
				.expect( validWikitextResponse() )
				.end( done );
		} );

		const htmlOfJsonConfig = getTextFromFile( 'JsonConfig.html' );
		it( 'should accept html for json contentmodel as a string', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/' )
				.send( {
					html: htmlOfJsonConfig,
					contentmodel: 'json'
				} )
				.expect( ( res ) => {
					res.statusCode.should.equal( 200 );
					res.headers.should.have.property( 'content-type' );
					res.headers[ 'content-type' ].should.equal( 'application/json' );
					res.text.should.equal( '{"a":4,"b":3}' );
				} )
				.end( done );
		} );

		it( 'should accept html with headers', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/' )
				.send( {
					html: {
						headers: {
							'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/' + defaultContentVersion + '"'
						},
						body: htmlOfMainPageWithDataParsoid
					}
				} )
				.expect( validWikitextResponse() )
				.end( done );
		} );

		it( 'should allow a title in the url', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/Main_Page' )
				.send( {
					html: htmlOfMainPageWithDataParsoid
				} )
				.expect( validWikitextResponse() )
				.end( done );
		} );

		it( 'should allow a title in the original data', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/' )
				.send( {
					html: htmlOfMainPageWithDataParsoid,
					original: {
						title: 'Main_Page'
					}
				} )
				.expect( validWikitextResponse() )
				.end( done );
		} );

		it( 'should allow a revision id in the url', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/Main_Page/1' )
				.send( {
					html: htmlOfMainPageWithDataParsoid
				} )
				.expect( status200 )
				.expect( validWikitextResponse() )
				.end( done );
		} );

		it( 'should allow a revision id in the original data', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/' )
				.send( {
					html: htmlOfMainPageWithDataParsoid,
					original: {
						revid: 1
					}
				} )
				.expect( status200 )
				.expect( validWikitextResponse() )
				.end( done );
		} );

		it( 'should accept original wikitext as src', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/' )
				.send( {
					html: htmlOfMainPageWithDataParsoid,
					original: {
						wikitext: {
							headers: {
								'content-type': 'text/plain;profile="https://www.mediawiki.org/wiki/Specs/wikitext/1.0.0"'
							},
							body: '<strong>MediaWiki has been successfully installed.</strong>\n\nConsult the [//meta.wikimedia.org/wiki/Help:Contents User\'s Guide] for information on using the wiki software.\n\n== Getting started ==\n* [//www.mediawiki.org/wiki/Special:MyLanguage/Manual:Configuration_settings Configuration settings list]\n* [//www.mediawiki.org/wiki/Special:MyLanguage/Manual:FAQ MediaWiki FAQ]\n* [https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]\n* [//www.mediawiki.org/wiki/Special:MyLanguage/Localisation#Translation_resources Localise MediaWiki for your language]\n'
						}
					}
				} )
				.expect( validWikitextResponse() )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should accept original html for selser (default)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: htmlOfMainPageWithDataParsoid,
					original: {
						html: {
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/' + defaultContentVersion + '"'
							},
							body: htmlOfMainPageOriginal
						},
						'data-parsoid': {
							headers: {
								'content-type': 'application/json;profile="https://www.mediawiki.org/wiki/Specs/data-parsoid/' + defaultContentVersion + '"'
							},
							body: dataParsoidOfMainPageOriginal
						}
					}
				} )
				.expect( validWikitextResponse() )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should accept original html for selser (1.1.1, meta)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: htmlOfMainPageWithDataParsoid_1_1_1,
					original: {
						html: {
							headers: {
								'content-type': 'text/html; profile="mediawiki.org/specs/html/1.1.1"'
							},
							body: htmlOfMainPageOriginal
						},
						'data-parsoid': {
							headers: {
								'content-type': 'application/json;profile="https://www.mediawiki.org/wiki/Specs/data-parsoid/0.0.1"'
							},
							body: dataParsoidOfMainPageOriginal
						}
					}
				} )
				.expect( validWikitextResponse() )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should accept original html for selser (1.1.1, headers)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					// Don't set the mw:html:version so that we get it from the original/headers
					html: htmlOfMainPageWithDataParsoid,
					original: {
						html: {
							headers: {
								'content-type': 'text/html; profile="mediawiki.org/specs/html/1.1.1"'
							},
							body: htmlOfMainPageOriginal
						},
						'data-parsoid': {
							headers: {
								'content-type': 'application/json;profile="https://www.mediawiki.org/wiki/Specs/data-parsoid/0.0.1"'
							},
							body: dataParsoidOfMainPageOriginal
						}
					}
				} )
				.expect( validWikitextResponse() )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should return http 400 if supplied data-parsoid is empty', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: '<html><head></head><body><p>hi</p></body></html>',
					original: {
						html: {
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/' + defaultContentVersion + '"'
							},
							body: '<html><head></head><body><p>ho</p></body></html>'
						},
						'data-parsoid': {
							headers: {
								'content-type': 'application/json;profile="https://www.mediawiki.org/wiki/Specs/data-parsoid/' + defaultContentVersion + '"'
							},
							body: {}
						}
					}
				} )
				.expect( 400 )
				.end( done );
		} );

		// FIXME: This test never passed. Pagebundle validation in general is needed
		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should return http 400 if supplied data-parsoid is a string', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: '<html><head></head><body><p>hi</p></body></html>',
					original: {
						html: {
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/' + defaultContentVersion + '"'
							},
							body: '<html><head></head><body><p>ho</p></body></html>'
						},
						'data-parsoid': {
							headers: {
								'content-type': 'application/json;profile="https://www.mediawiki.org/wiki/Specs/data-parsoid/' + defaultContentVersion + '"'
							},
							body: 'Garbled text from RESTBase.'
						}
					}
				} )
				.expect( 400 )
				.end( done );
		} );

		// The following three tests should all serialize as:
		//   "<div>Selser test"
		// However, we're deliberately setting the original wikitext in
		// the first two to garbage so that when selser doesn't detect any
		// difference between the new and old html, it'll just reuse that
		// string and we have a reliable way of determining that selser
		// was used.

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should use selser with supplied wikitext', ( done ) => {
			// Create Junk Page
			// New and old html are identical, which should produce no diffs
			// and reuse the original wikitext.
			client.req
				// Need to provide an oldid so that selser mode is enabled
				// Without an oldid, serialization falls back to non-selser wts.
				// The oldid is used to fetch wikitext, but if wikitext is provided
				// (as in this test), it is not used. So, for testing purposes,
				// we can use any old random id, as long as something is present.
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: '<html><body id="mwAA"><div id="mwBB">Selser test</div></body></html>',
					original: {
						title: 'Junk Page',
						revid: 1234,
						wikitext: {
							body: '1. This is just some junk. See the comment above.'
						},
						html: {
							body: '<html><body id="mwAA"><div id="mwBB">Selser test</div></body></html>',
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/' + defaultContentVersion + '"'
							}
						},
						'data-parsoid': {
							body: {
								ids: {
									mwAA: {},
									mwBB: { autoInsertedEnd: true, stx: 'html' }
								}
							}
						}
					}
				} )
				.expect( validWikitextResponse(
					'1. This is just some junk. See the comment above.'
				) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should use selser with wikitext fetched from the mw api', ( done ) => {
			// Creat Junk Page
			// New and old html are identical, which should produce no diffs
			// and reuse the original wikitext.
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: '<html><body id="mwAA"><div id="mwBB">Selser test</div></body></html>',
					original: {
						revid: 2,
						title: 'Junk Page',
						html: {
							body: '<html><body id="mwAA"><div id="mwBB">Selser test</div></body></html>',
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/' + defaultContentVersion + '"'
							}
						},
						'data-parsoid': {
							body: {
								ids: {
									mwAA: {},
									mwBB: { autoInsertedEnd: true, stx: 'html' }
								}
							}
						}
					}
				} )
				.expect( validWikitextResponse(
					'2. This is just some junk. See the comment above.'
				) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should fallback to non-selective serialization', ( done ) => {
			// Without the original wikitext and an unavailable
			// TemplateFetch for the source (no revision id provided),
			// it should fallback to non-selective serialization.
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: '<html><body id="mwAA"><div id="mwBB">Selser test</div></body></html>',
					original: {
						title: 'Junk Page',
						html: {
							body: '<html><body id="mwAA"><div id="mwBB">Selser test</div></body></html>',
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/' + defaultContentVersion + '"'
							}
						},
						'data-parsoid': {
							body: {
								ids: {
									mwAA: {},
									mwBB: { autoInsertedEnd: true, stx: 'html' }
								}
							}
						}
					}
				} )
				.expect( validWikitextResponse(
					'<div>Selser test'
				) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should apply data-parsoid to duplicated ids', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: '<html><body id="mwAA"><div id="mwBB">data-parsoid test</div><div id="mwBB">data-parsoid test</div></body></html>',
					original: {
						title: 'Doesnotexist',
						html: {
							body: '<html><body id="mwAA"><div id="mwBB">data-parsoid test</div></body></html>',
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/' + defaultContentVersion + '"'
							}
						},
						'data-parsoid': {
							body: {
								ids: {
									mwAA: {},
									mwBB: { autoInsertedEnd: true, stx: 'html' }
								}
							}
						}
					}
				} )
				.expect( validWikitextResponse(
					'<div>data-parsoid test<div>data-parsoid test'
				) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should return a 400 for missing inline data-mw (2.x)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: '<p about="#mwt1" typeof="mw:Transclusion" id="mwAQ">hi</p>',
					original: {
						title: 'Doesnotexist',
						'data-parsoid': {
							body: {
								ids: { mwAQ: { pi: [ [ { k: '1' } ] ] } }
							}
						},
						html: {
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/2.4.0"'
							},
							body: '<p about="#mwt1" typeof="mw:Transclusion" id="mwAQ">ho</p>'
						}
					}
				} )
				.expect( 400 )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should return a 400 for not supplying data-mw', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: '<p about="#mwt1" typeof="mw:Transclusion" id="mwAQ">hi</p>',
					original: {
						title: 'Doesnotexist',
						'data-parsoid': {
							body: {
								ids: { mwAQ: { pi: [ [ { k: '1' } ] ] } }
							}
						},
						html: {
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/999.0.0"'
							},
							body: '<p about="#mwt1" typeof="mw:Transclusion" id="mwAQ">ho</p>'
						}
					}
				} )
				.expect( 400 )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should apply original data-mw', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: '<p about="#mwt1" typeof="mw:Transclusion" id="mwAQ">hi</p>',
					original: {
						title: 'Doesnotexist',
						'data-parsoid': {
							body: {
								ids: { mwAQ: { pi: [ [ { k: '1' } ] ] } }
							}
						},
						'data-mw': {
							body: {
								ids: {
									mwAQ: {
										parts: [ {
											template: {
												target: { wt: '1x', href: './Template:1x' },
												params: { 1: { wt: 'hi' } },
												i: 0
											}
										} ]
									}
								}
							}
						},
						html: {
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/999.0.0"'
							},
							body: '<p about="#mwt1" typeof="mw:Transclusion" id="mwAQ">ho</p>'
						}
					}
				} )
				.expect( validWikitextResponse( '{{1x|hi}}' ) )
				.end( done );
		} );

		// Sanity check data-mw was applied in the previous test
		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should return a 400 for missing modified data-mw', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: '<p about="#mwt1" typeof="mw:Transclusion" id="mwAQ">hi</p>',
					original: {
						title: 'Doesnotexist',
						'data-parsoid': {
							body: {
								ids: { mwAQ: { pi: [ [ { k: '1' } ] ] } }
							}
						},
						'data-mw': {
							body: {
								ids: { mwAQ: {} } // Missing data-mw.parts!
							}
						},
						html: {
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/999.0.0"'
							},
							body: '<p about="#mwt1" typeof="mw:Transclusion" id="mwAQ">ho</p>'
						}
					}
				} )
				.expect( 400 )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should give precedence to inline data-mw over original', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: '<p about="#mwt1" typeof="mw:Transclusion" data-mw=\'{"parts":[{"template":{"target":{"wt":"1x","href":"./Template:1x"},"params":{"1":{"wt":"hi"}},"i":0}}]}\' id="mwAQ">hi</p>',
					original: {
						title: 'Doesnotexist',
						'data-parsoid': {
							body: {
								ids: { mwAQ: { pi: [ [ { k: '1' } ] ] } }
							}
						},
						'data-mw': {
							body: {
								ids: { mwAQ: {} } // Missing data-mw.parts!
							}
						},
						html: {
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/999.0.0"'
							},
							body: '<p about="#mwt1" typeof="mw:Transclusion" id="mwAQ">ho</p>'
						}
					}
				} )
				.expect( validWikitextResponse( '{{1x|hi}}' ) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should not apply original data-mw if modified is supplied', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: '<p about="#mwt1" typeof="mw:Transclusion" id="mwAQ">hi</p>',
					'data-mw': {
						body: {
							ids: {
								mwAQ: {
									parts: [ {
										template: {
											target: { wt: '1x', href: './Template:1x' },
											params: { 1: { wt: 'hi' } },
											i: 0
										}
									} ]
								}
							}
						}
					},
					original: {
						title: 'Doesnotexist',
						'data-parsoid': {
							body: {
								ids: { mwAQ: { pi: [ [ { k: '1' } ] ] } }
							}
						},
						'data-mw': {
							body: {
								ids: { mwAQ: {} } // Missing data-mw.parts!
							}
						},
						html: {
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/999.0.0"'
							},
							body: '<p about="#mwt1" typeof="mw:Transclusion" id="mwAQ">ho</p>'
						}
					}
				} )
				.expect( validWikitextResponse( '{{1x|hi}}' ) )
				.end( done );
		} );

		// The next three tests, although redundant with the above precedence
		// tests, are an attempt to show clients the semantics of separate
		// data-mw in the API.  The main idea is,
		//
		//   non-inline-data-mw = modified || original
		//   inline-data-mw > non-inline-data-mw

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should apply original data-mw when modified is absent (captions 1)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: htmlOfImage,
					original: {
						title: 'Doesnotexist',
						'data-parsoid': {
							body: {
								ids: {
									mwAg: { optList: [ { ck: 'caption', ak: 'Testing 123' } ] },
									mwAw: { a: { href: './File:Foobar.jpg' }, sa: {} },
									mwBA: {
										a: { resource: './File:Foobar.jpg', height: '28', width: '240' },
										sa: { resource: 'File:Foobar.jpg' }
									}
								}
							}
						},
						'data-mw': {
							body: {
								ids: {
									mwAg: { caption: 'Testing 123' }
								}
							}
						},
						html: {
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/999.0.0"'
							},
							body: htmlOfImage
						}
					}
				} )
				.expect( validWikitextResponse( '[[File:Foobar.jpg|Testing 123]]' ) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should give precedence to inline data-mw over modified (captions 2)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: htmlOfImageWithDataMW,
					'data-mw': {
						body: {
							ids: {
								mwAg: { caption: 'Testing 123' }
							}
						}
					},
					original: {
						title: 'Doesnotexist',
						'data-parsoid': {
							body: {
								ids: {
									mwAg: { optList: [ { ck: 'caption', ak: 'Testing 123' } ] },
									mwAw: { a: { href: './File:Foobar.jpg' }, sa: {} },
									mwBA: {
										a: { resource: './File:Foobar.jpg', height: '28', width: '240' },
										sa: { resource: 'File:Foobar.jpg' }
									}
								}
							}
						},
						'data-mw': {
							body: {
								ids: {
									mwAg: { caption: 'Testing 123' }
								}
							}
						},
						html: {
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/999.0.0"'
							},
							body: htmlOfImage
						}
					}
				} )
				.expect( validWikitextResponse( '[[File:Foobar.jpg]]' ) )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should give precedence to modified data-mw over original (captions 3)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: htmlOfImage,
					'data-mw': {
						body: {
							ids: {
								mwAg: {}
							}
						}
					},
					original: {
						title: 'Doesnotexist',
						'data-parsoid': {
							body: {
								ids: {
									mwAg: { optList: [ { ck: 'caption', ak: 'Testing 123' } ] },
									mwAw: { a: { href: './File:Foobar.jpg' }, sa: {} },
									mwBA: {
										a: { resource: './File:Foobar.jpg', height: '28', width: '240' },
										sa: { resource: 'File:Foobar.jpg' }
									}
								}
							}
						},
						'data-mw': {
							body: {
								ids: {
									mwAg: { caption: 'Testing 123' }
								}
							}
						},
						html: {
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/999.0.0"'
							},
							body: htmlOfImage
						}
					}
				} )
				.expect( validWikitextResponse( '[[File:Foobar.jpg]]' ) )
				.end( done );
		} );

		it( 'should apply extra normalizations', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/' )
				.send( {
					html: '<h2></h2>\n\nrandom',
					original: { title: 'Doesnotexist' }
				} )
				.expect( validWikitextResponse(
					'\nrandom'
				) )
				.end( done );
		} );

		it( 'should return a request too large error when just over limit', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/' )
				.send( {
					// One over limit.
					// Use multi-byte characters, since the limit is in characters.
					html: 'ä'.repeat( parsoidOptions.limits.html2wt.maxHTMLSize + 1 )
				} )
				.expect( 413 )
				.end( done );
		} );

		it( 'should not return a request too large error when just under limit', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/' )
				.send( {
					// One under limit.
					// Use multi-byte characters, since the limit is in characters.
					html: 'ä'.repeat( parsoidOptions.limits.html2wt.maxHTMLSize - 1 )
				} )
				.expect( 200 )
				.end( done );
		} );

		// Support for transforming from/to pagebundle is disabled in production.
		it.skip( 'should fail to downgrade the original version for an unknown transition', ( done ) => {
			const htmlOfMinimal = getTextFromFile( 'Minimal.html' );
			const htmlOfMinimal2222 = getTextFromFile( 'Minimal-2222.html' );
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: htmlOfMinimal,
					original: {
						title: 'Doesnotexist',
						'data-parsoid': { body: { ids: {} } },
						html: {
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/2222.0.0"'
							},
							body: htmlOfMinimal2222
						}
					}
				} )
				.expect( 400 )
				.end( done );
		} );

	} ); // end html2wt

	// Support for transforming from/to pagebundle is disabled in production.
	describe.skip( 'pb2pb', () => {

		it( 'should require an original or previous version', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/pagebundle/Reuse_Page/100' )
				.send( {} )
				.expect( 400 )
				.end( done );
		} );

		const previousRevHTML = {
			revid: 99,
			html: {
				headers: {
					'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/' + defaultContentVersion + '"'
				},
				body: '<p about="#mwt1" typeof="mw:Transclusion" data-mw=\'{"parts":[{"template":{"target":{"wt":"colours of the rainbow","href":"./Template:Colours_of_the_rainbow"},"params":{},"i":0}}]}\' id="mwAg">pink</p>'
			},
			'data-parsoid': {
				headers: {
					'content-type': 'application/json;profile="https://www.mediawiki.org/wiki/Specs/data-parsoid/' + defaultContentVersion + '"'
				},
				body: {
					counter: 2,
					ids: {
						mwAg: { pi: [ [] ], src: '{{colours of the rainbow}}' } // artificially added src
					}
				}
			}
		};

		it( 'should error when revision not found (transform, pb2pb)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/pagebundle/Doesnotexist' )
				.send( {
					previous: previousRevHTML
				} )
				.expect( 404 )
				.end( done );
		} );

		// FIXME: Expansion reuse wasn't ported, see T98995
		it.skip( 'should accept the previous revision to reuse expansions', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/pagebundle/Reuse_Page/100' )
				.send( {
					previous: previousRevHTML
				} )
				.expect( validPageBundleResponse( ( doc ) => {
					doc.body.firstChild.textContent.should.match( /pink/ );
				} ) )
				.end( done );
		} );

		const origHTML = JSON.parse( JSON.stringify( previousRevHTML ) );
		origHTML.revid = 100;

		// FIXME: Expansion reuse wasn't ported, see T98995
		it.skip( 'should accept the original and reuse certain expansions', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/pagebundle/Reuse_Page/100' )
				.send( {
					updates: {
						transclusions: true
					},
					original: origHTML
				} )
				.expect( validPageBundleResponse( ( doc ) => {
					doc.body.firstChild.textContent.should.match( /purple/ );
				} ) )
				.end( done );
		} );

		it( 'should refuse an unknown conversion (2.x -> 999.x)', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/pagebundle/Reuse_Page/100' )
				.set( 'Accept', 'application/json; profile="https://www.mediawiki.org/wiki/Specs/pagebundle/999.0.0"' )
				.send( {
					previous: previousRevHTML
				} )
				.expect( 415 )
				.end( done );
		} );

		it( 'should downgrade 999.x content to 2.x', ( done ) => {
			const contentVersion = '2.4.0';
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/pagebundle/' )
				.set( 'Accept', 'application/json; profile="https://www.mediawiki.org/wiki/Specs/pagebundle/' + contentVersion + '"' )
				.send( {
					original: {
						title: 'Doesnotexist',
						'data-parsoid': {
							body: {
								ids: { mwAQ: { pi: [ [ { k: '1' } ] ] } }
							}
						},
						'data-mw': {
							body: {
								ids: {
									mwAQ: {
										parts: [ {
											template: {
												target: { wt: '1x', href: './Template:1x' },
												params: { 1: { wt: 'hi' } },
												i: 0
											}
										} ]
									}
								}
							}
						},
						html: {
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/999.0.0"'
							},
							body: '<!DOCTYPE html>\n<html><head><meta charset="utf-8"/><meta property="mw:htmlVersion" content="999.0.0"/></head><body><p about="#mwt1" typeof="mw:Transclusion" id="mwAQ">ho</p></body></html>'
						}
					}
				} )
				.expect( status200 )
				.expect( acceptablePageBundleResponse( contentVersion, ( html ) => {
					// In < 999.x, data-mw is still inline.
					html.should.match( /\s+data-mw\s*=\s*['"]/ );
					html.should.not.match( /\s+data-parsoid\s*=\s*['"]/ );
					const doc = domino.createDocument( html );
					const meta = doc.querySelector( 'meta[property="mw:html:version"], meta[property="mw:htmlVersion"]' );
					meta.getAttribute( 'content' ).should.satisfy(
						( version ) => semver.satisfies( version, `^${ contentVersion }` )
					);
				} ) )
				.end( done );
		} );

		it( 'should accept the original and update the redlinks', function ( done ) {
			if ( skipForNow ) {
				return this.skip();
			} // Create pages to fix redlinks count
			// NOTE: Keep this on an older version to show that it's preserved
			// through the transformation.
			const contentVersion = '2.0.0';
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/pagebundle/' )
				.send( {
					updates: {
						redlinks: true
					},
					original: {
						title: 'Doesnotexist',
						'data-parsoid': {
							body: {
								ids: {}
							}
						},
						html: {
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/' + contentVersion + '"'
							},
							body: '<p><a rel="mw:WikiLink" href="./Special:Version" title="Special:Version">Special:Version</a> <a rel="mw:WikiLink" href="./Doesnotexist" title="Doesnotexist">Doesnotexist</a> <a rel="mw:WikiLink" href="./Redirected" title="Redirected">Redirected</a></p>'
						}
					}
				} )
				.expect( acceptablePageBundleResponse( contentVersion, ( html ) => {
					const doc = domino.createDocument( html );
					doc.body.querySelectorAll( 'a' ).length.should.equal( 3 );
					const redLinks = doc.body.querySelectorAll( '.new' );
					redLinks.length.should.equal( 1 );
					redLinks[ 0 ].getAttribute( 'title' ).should.equal( 'Doesnotexist' );
					const redirects = doc.body.querySelectorAll( '.mw-redirect' );
					redirects.length.should.equal( 1 );
					redirects[ 0 ].getAttribute( 'title' ).should.equal( 'Redirected' );
				} ) )
				.end( done );
		} );

		( skipForNow ? describe.skip : describe )( 'Variant conversion', () => {

			it( 'should refuse variant conversion on en page', ( done ) => {
				client.req
					.post( endpointPrefix + '/v1/transform/pagebundle/to/pagebundle/' )
					.send( {
						updates: {
							variant: { target: 'sr-el' }
						},
						original: {
							revid: 1,
							html: {
								headers: {
									'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/' + defaultContentVersion + '"'
								},
								body: '<p>абвг abcd</p>'
							}
						}
					} )
					.expect( 400 )
					.end( done );
			} );

			it( 'should accept the original and do variant conversion (given oldid)', ( done ) => {
				client.req
					.post( endpointPrefix + '/v1/transform/pagebundle/to/pagebundle/' )
					.send( {
						updates: {
							variant: { target: 'sr-el' }
						},
						original: {
							revid: 104, /* sets the pagelanguage */
							html: {
								headers: {
									'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/' + defaultContentVersion + '"'
								},
								body: '<p>абвг abcd x</p>'
							}
						}
					} )
					.expect( status200 )
					.expect( ( res ) => {
						// We don't actually require the result to have data-parsoid
						// if the input didn't have data-parsoid; hack the result
						// in order to make validPageBundleResponse() pass.
						res.body[ 'data-parsoid' ].body = {};
					} )
					.expect( validPageBundleResponse( ( doc ) => {
						doc.body.textContent.should.equal( 'abvg abcd x' );
					} ) )
					.expect( ( res ) => {
						const headers = res.body.html.headers;
						headers.should.have.property( 'content-language' );
						headers[ 'content-language' ].should.equal( 'sr-el' );
						headers.should.have.property( 'vary' );
						headers.vary.should.match( /\bAccept-Language\b/i );
					} )
					.end( done );
			} );

			it( 'should accept the original and do variant conversion (given pagelanguage)', ( done ) => {
				client.req
					.post( endpointPrefix + '/v1/transform/pagebundle/to/pagebundle/' )
					.set( 'Content-Language', 'sr' )
					.set( 'Accept-Language', 'sr-el' )
					.send( {
						updates: {
							variant: { /* target implicit from accept-language */}
						},
						original: {
							html: {
								headers: {
									'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/' + defaultContentVersion + '"'
								},
								body: '<p>абвг abcd</p>'
							}
						}
					} )
					.expect( status200 )
					.expect( ( res ) => {
						// We don't actually require the result to have data-parsoid
						// if the input didn't have data-parsoid; hack the result
						// in order to make validPageBundleResponse() pass.
						res.body[ 'data-parsoid' ].body = {};
					} )
					.expect( validPageBundleResponse( ( doc ) => {
						doc.body.textContent.should.equal( 'abvg abcd' );
					} ) )
					.expect( ( res ) => {
						const headers = res.body.html.headers;
						headers.should.have.property( 'content-language' );
						headers[ 'content-language' ].should.equal( 'sr-el' );
						headers.should.have.property( 'vary' );
						headers.vary.should.match( /\bAccept-Language\b/i );
					} )
					.end( done );
			} );

			it( 'should not perform variant conversion w/ invalid variant (given pagelanguage)', ( done ) => {
				client.req
					.post( endpointPrefix + '/v1/transform/pagebundle/to/pagebundle/' )
					.set( 'Content-Language', 'sr' )
					.set( 'Accept-Language', 'sr-BOGUS' )
					.send( {
						updates: {
							variant: { /* target implicit from accept-language */}
						},
						original: {
							html: {
								headers: {
									'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/' + defaultContentVersion + '"'
								},
								body: '<p>абвг abcd</p>'
							}
						}
					} )
					.expect( status200 )
					.expect( ( res ) => {
						// We don't actually require the result to have data-parsoid
						// if the input didn't have data-parsoid; hack the result
						// in order to make validPageBundleResponse() pass.
						res.body[ 'data-parsoid' ].body = {};
					} )
					.expect( validPageBundleResponse( ( doc ) => {
						doc.body.textContent.should.equal( 'абвг abcd' );
					} ) )
					.expect( ( res ) => {
						const headers = res.body.html.headers;
						headers.should.have.property( 'content-language' );
						headers[ 'content-language' ].should.equal( 'sr' );
						headers.should.have.property( 'vary' );
						headers.vary.should.match( /\bAccept-Language\b/i );
					} )
					.end( done );
			} );

		} );

	} ); // end pb2pb

	// Since we're disabling the pagebundle transform, let's make sure that trying to
	// send a request to the endpoint returns a 404 error.
	describe( 'Pagebundle transform (from/format) disabled, should return 404 as response code', () => {
		it( '/transform/pagebundle/to/pagebundle/Reuse_Page/100', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/pagebundle/Reuse_Page/100' )
				.send( {} )
				.expect( 404 )
				.end( done );
		} );

		it( '/transform/pagebundle/to/wikitext/', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/pagebundle/to/wikitext/' )
				.send( {
					html: '<!DOCTYPE html>\n<html prefix="dc: http://purl.org/dc/terms/ mw: http://mediawiki.org/rdf/" about="http://localhost/index.php/Special:Redirect/revision/1"><head prefix="mwr: http://localhost/index.php/Special:Redirect/"><meta property="mw:articleNamespace" content="0"/><link rel="dc:replaces" resource="mwr:revision/0"/><meta property="dc:modified" content="2014-09-12T22:46:59.000Z"/><meta about="mwr:user/0" property="dc:title" content="MediaWiki default"/><link rel="dc:contributor" resource="mwr:user/0"/><meta property="mw:revisionSHA1" content="8e0aa2f2a7829587801db67d0424d9b447e09867"/><meta property="dc:description" content=""/><link rel="dc:isVersionOf" href="http://localhost/index.php/Main_Page"/><title>Main_Page</title><base href="http://localhost/index.php/"/><link rel="stylesheet" href="//localhost/load.php?modules=mediawiki.legacy.commonPrint,shared|mediawiki.skinning.elements|mediawiki.skinning.content|mediawiki.skinning.interface|skins.vector.styles|site|mediawiki.skinning.content.parsoid&amp;only=styles&amp;debug=true&amp;skin=vector"/></head><body data-parsoid=\'{"dsr":[0,592,0,0]}\' lang="en" class="mw-content-ltr sitedir-ltr ltr mw-body mw-body-content mediawiki" dir="ltr"><p data-parsoid=\'{"dsr":[0,59,0,0]}\'><strong data-parsoid=\'{"stx":"html","dsr":[0,59,8,9]}\'>MediaWiki has been successfully installed.</strong></p>\n\n<p data-parsoid=\'{"dsr":[61,171,0,0]}\'>Consult the <a rel="mw:ExtLink" href="//meta.wikimedia.org/wiki/Help:Contents" data-parsoid=\'{"dsr":[73,127,41,1]}\'>User\'s Guide</a> for information on using the wiki software.</p>\n\n<h2 data-parsoid=\'{"dsr":[173,194,2,2]}\'> Getting started </h2>\n<ul data-parsoid=\'{"dsr":[195,592,0,0]}\'><li data-parsoid=\'{"dsr":[195,300,1,0]}\'> <a rel="mw:ExtLink" href="//www.mediawiki.org/wiki/Special:MyLanguage/Manual:Configuration_settings" data-parsoid=\'{"dsr":[197,300,75,1]}\'>Configuration settings list</a></li>\n<li data-parsoid=\'{"dsr":[301,373,1,0]}\'> <a rel="mw:ExtLink" href="//www.mediawiki.org/wiki/Special:MyLanguage/Manual:FAQ" data-parsoid=\'{"dsr":[303,373,56,1]}\'>MediaWiki FAQ</a></li>\n<li data-parsoid=\'{"dsr":[374,472,1,0]}\'> <a rel="mw:ExtLink" href="https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce" data-parsoid=\'{"dsr":[376,472,65,1]}\'>MediaWiki release mailing list</a></li>\n<li data-parsoid=\'{"dsr":[473,592,1,0]}\'> <a rel="mw:ExtLink" href="//www.mediawiki.org/wiki/Special:MyLanguage/Localisation#Translation_resources" data-parsoid=\'{"dsr":[475,592,80,1]}\'>Localise MediaWiki for your language</a></li></ul></body></html>',
					original: {
						html: {
							headers: {
								'content-type': 'text/html;profile="https://www.mediawiki.org/wiki/Specs/HTML/' + defaultContentVersion + '"'
							},
							body: "<!DOCTYPE html>\n<html prefix=\"dc: http://purl.org/dc/terms/ mw: http://mediawiki.org/rdf/\" about=\"http://localhost/index.php/Special:Redirect/revision/1\"><head prefix=\"mwr: http://localhost/index.php/Special:Redirect/\"><meta property=\"mw:articleNamespace\" content=\"0\"/><link rel=\"dc:replaces\" resource=\"mwr:revision/0\"/><meta property=\"dc:modified\" content=\"2014-09-12T22:46:59.000Z\"/><meta about=\"mwr:user/0\" property=\"dc:title\" content=\"MediaWiki default\"/><link rel=\"dc:contributor\" resource=\"mwr:user/0\"/><meta property=\"mw:revisionSHA1\" content=\"8e0aa2f2a7829587801db67d0424d9b447e09867\"/><meta property=\"dc:description\" content=\"\"/><link rel=\"dc:isVersionOf\" href=\"http://localhost/index.php/Main_Page\"/><title>Main_Page</title><base href=\"http://localhost/index.php/\"/><link rel=\"stylesheet\" href=\"//localhost/load.php?modules=mediawiki.legacy.commonPrint,shared|mediawiki.skinning.elements|mediawiki.skinning.content|mediawiki.skinning.interface|skins.vector.styles|site|mediawiki.skinning.content.parsoid&amp;only=styles&amp;debug=true&amp;skin=vector\"/></head><body id=\"mwAA\" lang=\"en\" class=\"mw-content-ltr sitedir-ltr ltr mw-body mw-body-content mediawiki\" dir=\"ltr\"><p id=\"mwAQ\"><strong id=\"mwAg\">MediaWiki has been successfully installed.</strong></p>\n\n<p id=\"mwAw\">Consult the <a rel=\"mw:ExtLink\" href=\"//meta.wikimedia.org/wiki/Help:Contents\" id=\"mwBA\">User's Guide</a> for information on using the wiki software.</p>\n\n<h2 id=\"mwBQ\"> Getting started </h2>\n<ul id=\"mwBg\"><li id=\"mwBw\"> <a rel=\"mw:ExtLink\" href=\"//www.mediawiki.org/wiki/Special:MyLanguage/Manual:Configuration_settings\" id=\"mwCA\">Configuration settings list</a></li>\n<li id=\"mwCQ\"> <a rel=\"mw:ExtLink\" href=\"//www.mediawiki.org/wiki/Special:MyLanguage/Manual:FAQ\" id=\"mwCg\">MediaWiki FAQ</a></li>\n<li id=\"mwCw\"> <a rel=\"mw:ExtLink\" href=\"https://lists.wikimedia.org/mailman/listinfo/mediawiki-announce\" id=\"mwDA\">MediaWiki release mailing list</a></li>\n<li id=\"mwDQ\"> <a rel=\"mw:ExtLink\" href=\"//www.mediawiki.org/wiki/Special:MyLanguage/Localisation#Translation_resources\" id=\"mwDg\">Localise MediaWiki for your language</a></li></ul></body></html>"
						},
						'data-parsoid': {
							headers: {
								'content-type': 'application/json;profile="https://www.mediawiki.org/wiki/Specs/data-parsoid/' + defaultContentVersion + '"'
							},
							body: {
								counter: 14,
								ids: {
									mwAA: { dsr: [ 0, 592, 0, 0 ] },
									mwAQ: { dsr: [ 0, 59, 0, 0 ] },
									mwAg: { stx: 'html', dsr: [ 0, 59, 8, 9 ] },
									mwAw: { dsr: [ 61, 171, 0, 0 ] },
									mwBA: { dsr: [ 73, 127, 41, 1 ] },
									mwBQ: { dsr: [ 173, 194, 2, 2 ] },
									mwBg: { dsr: [ 195, 592, 0, 0 ] },
									mwBw: { dsr: [ 195, 300, 1, 0 ] },
									mwCA: { dsr: [ 197, 300, 75, 1 ] },
									mwCQ: { dsr: [ 301, 373, 1, 0 ] },
									mwCg: { dsr: [ 303, 373, 56, 1 ] },
									mwCw: { dsr: [ 374, 472, 1, 0 ] },
									mwDA: { dsr: [ 376, 472, 65, 1 ] },
									mwDQ: { dsr: [ 473, 592, 1, 0 ] },
									mwDg: { dsr: [ 475, 592, 80, 1 ] }
								}
							}
						}
					}
				} )
				.expect( 404 )
				.end( done );
		} );

		it( '/transform/wikitext/to/pagebundle/', ( done ) => {
			client.req
				.post( endpointPrefix + '/v1/transform/wikitext/to/pagebundle/' )
				.set( 'Accept', 'application/json; profile="https://www.mediawiki.org/wiki/Specs/HTML/0.0.0"' )
				.send( { wikitext: '== h2 ==' } )
				.expect( 404 )
				.expect( ( res ) => {
					JSON.parse( res.error.text ).errorKey.should.equal(
						'rest-no-match'
					);
				} )
				.end( done );
		} );
	} );

	describe( 'ETags', () => {
		it( '/transform/ should use ETag from If-Match header', async () => {
			const { statusCode: status1, headers: headers1, text: text1 } = await client.req
				.get( `${ endpointPrefix }/v1/revision/${ revid }/html` )
				.query( { stash: 'yes' } );

			assert.deepEqual( status1, 200, text1 );
			assert.ok( headers1.etag, 'ETag header' );

			// The request above should have stashed a rendering associated with the ETag it
			// returned. Pass the ETag in the If-Match header.
			const { statusCode: status2, text: text2 } = await client.req
				.post( endpointPrefix + `/v1/transform/html/to/wikitext/${ page }/${ revid }` )
				.set( 'If-Match', headers1.etag )
				.send( {
					html: text1
				} );

			assert.deepEqual( status2, 200, text2 );

			// pageContent is brittle against round trip conversion, we only get it back correctly
			// because the HTML is unmodified, and selser kicks in.
			assert.deepEqual( text2, pageContent );
		} );

		it( '/transform/ should use ETag from body', async () => {
			const { statusCode: status1, headers: headers1, text: text1 } = await client.req
				.get( `${ endpointPrefix }/v1/revision/${ revid }/html` )
				.query( { stash: 'yes' } );

			assert.deepEqual( status1, 200, text1 );
			assert.ok( headers1.etag, 'ETag header' );

			// The request above should have stashed a rendering associated with the ETag it
			// returned. Submit it in the request body.
			// Don't put the revision ID into the path, to test that the one from the ETag is used.
			const { statusCode: status2, text: text2 } = await client.req
				.post( endpointPrefix + `/v1/transform/html/to/wikitext/${ page }` )
				.send( {
					html: text1,
					original: { etag: headers1.etag }
				} );

			assert.deepEqual( status2, 200, text2 );

			// pageContent is brittle against round trip conversion, we only get it back correctly
			// because the HTML is unmodified, and selser kicks in.
			assert.deepEqual( text2, pageContent );
		} );

		it( '/transform/ should refuse non-matching ETags in header', async () => {
			const { status, text } = await client.req
				.post( endpointPrefix + `/v1/transform/html/to/wikitext/${ page }/${ revid }` )
				.set( 'If-Match', '"1219844647/deadbeef"' )
				.send( {
					html: '<p>hello</p>'
				} );

			assert.deepEqual( status, 412, text );
		} );

		it( '/transform/ should refuse non-matching ETags in the body', async () => {
			const { status, text } = await client.req
				.post( endpointPrefix + `/v1/transform/html/to/wikitext/${ page }` )
				.send( {
					html: '<p>hello</p>',
					original: { etag: '"1219844647/deadbeef"' }
				} );

			assert.deepEqual( status, 412, text );
		} );
	} );

	describe( 'stashing with If-Match header', () => {

		// TODO: The /transform/html endpoint should handle the If-Match header
		//       by checking whether it has a rendering with the correct key
		//       stashed or cached. If so, it should be used for selser.
		it.skip( 'should trigger on If-Match header', async () => {
			const pageResponse = await client.req
				.get( `${ endpointPrefix }/v1/page/${ pageEncoded }/html` )
				.query( { stash: 'yes' } );

			pageResponse.headers.should.have.property( 'etag' );
			const eTag = pageResponse.headers.etag;
			const html = pageResponse.text;

			const transformResponse = await client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/' )
				.set( 'If-Match', eTag )
				.send( {
					html
				} );

			transformResponse.status.should.equal( 200, transformResponse.text );

			// Since the HTML didn't change, we should get back the original wikitext unchanged.
			transformResponse.text.should.equal( pageContent );
		} );

		it( 'should fail if eTag in If-Match header is unknown', async () => {
			// request page HTML, but do not set 'stash' parameter!
			const transformResponse = await client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/' )
				.set( 'If-Match', '"1219844647/dummy"' )
				.send( {
					html: '<p>test</p>'
				} );

			transformResponse.status.should.equal( 412 );
		} );
	} );

	describe( 'stashing with renderid in body', () => {
		it( 'should trigger on renderid field in the body', async () => {
			const pageResponse = await client.req
				.get( `${ endpointPrefix }/v1/page/${ pageEncoded }/html` )
				.query( { stash: 'yes' } );

			pageResponse.headers.should.have.property( 'etag' );
			const eTag = pageResponse.headers.etag;
			const html = pageResponse.text;

			const transformResponse = await client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/' )
				.send( {
					html,
					original: {
						renderid: eTag
					}
				} );

			transformResponse.status.should.equal( 200, transformResponse.text );

			// Since the HTML didn't change, we should get back the original wikitext unchanged.
			transformResponse.text.should.equal( pageContent );
		} );

		it( 'should fail if stash key is unknown', async () => {
			// request page HTML, but do not set 'stash' parameter!
			const transformResponse = await client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/' )
				.send( {
					html: '<p>test</p>',
					original: {
						renderid: '"1219844647/dummy"'
					}
				} );

			transformResponse.status.should.equal( 412 );
		} );
	} );

	describe( 'selser using rendering based on revid', () => {
		it( 'should trigger on revid field in the body', async () => {
			const pageResponse = await client.req
				.get( `${ endpointPrefix }/v1/page/${ pageEncoded }/with_html` );

			const transformResponse = await client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/' )
				.send( {
					html: pageResponse.body.html,
					original: {
						revid: pageResponse.body.latest.id
					}
				} );

			transformResponse.status.should.equal( 200, transformResponse.text );

			// Since the HTML didn't change, we should get back the original wikitext unchanged.
			transformResponse.text.should.equal( pageContent );
		} );

		it( 'should fail if revid is unknown', async () => {
			// request page HTML, but do not set 'stash' parameter!
			const transformResponse = await client.req
				.post( endpointPrefix + '/v1/transform/html/to/wikitext/' )
				.send( {
					html: '<p>test</p>',
					original: {
						revid: 45452232
					}
				} );

			transformResponse.status.should.equal( 404 );
		} );
	} );

} );
