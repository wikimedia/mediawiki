'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( 'Reparse of dependent pages', () => {
	const title = utils.title( 'Reparse_' );
	const template = utils.title( 'Template' );
	const link = utils.title( 'Link' );

	let alice;

	before( async () => {
		alice = await action.alice();
	} );

	it( 'should create a page with missing template and link', async () => {
		await alice.edit( title, { text: `{{${ template }}} [[${ link }]]`, createonly: true } );

		// The legacy parser links to $wgScript?title=…, but Parsoid adds the query
		// to the article path, which may be /index.php?title=$1 or /wiki/$1
		const redlink = ( page ) => new RegExp(
			`(title=${ page }&amp;|/${ page }\\?)action=edit&amp;redlink=1`
		);

		const html = await alice.getHtml( title );
		assert.match( html, redlink( `Template(:|%3A)${ template }` ) );
		assert.match( html, redlink( link ) );
	} );

	it( 'should create missing template and link', async () => {
		await alice.edit( `Template:${ template }`, { text: 'Howdy', createonly: true } );
		await alice.edit( link, { text: 'Test link', createonly: true } );
	} );

	// FIXME: T230211
	it.skip( 'should get page with updated template and link', async () => {
		const { parse } = await alice.action( 'parse', { page: title } );

		assert.notInclude( parse.text[ '*' ], 'redlink=1' );
		assert.exists( parse.links[ 0 ].exists );
		assert.exists( parse.templates[ 0 ].exists );
	} );
} );
