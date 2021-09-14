'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( 'ExternalLinks', function testExternalLinks() {
	const page = utils.title( 'ExternalLinkTest_' );
	const links = [
		'http://example.org/search-me',
		'http://www.example.org/some-page',
		'ssh://example.org'
	];

	let alice;

	before( async () => {
		alice = await action.alice();

		const text = links.reduce( ( currText, url ) => `${currText} [${url}]`, '' );

		await alice.edit( page, { text } );
	} );

	it( 'can be listed', async () => {
		const result = await alice.prop( 'extlinks', page );
		const fetchedLinks = result[ page ].extlinks.map( ( p ) => p[ '*' ] );
		assert.sameMembers( fetchedLinks, links );
	} );

	it( 'can be limited', async () => {
		const result = await alice.prop( 'extlinks', page, { ellimit: 1 } );
		const fetchedLinks = result[ page ].extlinks.map( ( p ) => p[ '*' ] );
		assert.equal( fetchedLinks.length, 1 );
	} );

	it( 'can be filtered by protocol', async () => {
		const result = await alice.prop( 'extlinks', page, { elprotocol: 'ssh' } );
		const fetchedLinks = result[ page ].extlinks.map( ( p ) => p[ '*' ] );
		assert.sameMembers( fetchedLinks, [ 'ssh://example.org' ] );
	} );

	it( 'throws an error on invalid protocol', async () => {
		const result = await alice.actionError(
			'query',
			{
				prop: 'extlinks',
				titles: page,
				elprotocol: 'uwu'
			}
		);
		assert.deepNestedInclude( result, { code: 'badvalue' } );
	} );

	it( 'can be filtered by search string', async () => {
		const result = await alice.prop( 'extlinks', page, { elquery: 'example.org/search-me' } );
		const fetchedLinks = result[ page ].extlinks.map( ( p ) => p[ '*' ] );
		assert.sameMembers( fetchedLinks, [ 'http://example.org/search-me' ] );
	} );

	it( 'does not expand external urls', async () => {
		const result = await alice.prop( 'extlinks', page, { elexpandurl: true } );
		const fetchedLinks = result[ page ].extlinks.map( ( p ) => p[ '*' ] );
		assert.sameMembers( fetchedLinks, links );
	} );
} );
