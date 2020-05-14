'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( 'Prefix Search', function () {
	const prefix = `R${utils.title( utils.uniq() )}`;
	const firstPage = `${prefix}_first`;
	const secondPage = `${prefix}_second`;
	const thirdPage = `${prefix}_third`;

	let alice;

	before( async () => {
		alice = await action.alice();

		const text = 'Random Text';

		await alice.edit( firstPage, { text } );
		await alice.edit( secondPage, { text } );
		await alice.edit( thirdPage, { text } );
	} );

	it( 'should search for pages with the `${prefix}` prefix', async () => {
		const result = await alice.list( 'prefixsearch', { pssearch: `${prefix}`, pslimit: 100 } );
		const pageTitles = result.map( ( p ) => utils.dbkey( p.title ) );
		assert.sameMembers( pageTitles, [ firstPage, secondPage, thirdPage ] );
	} );
} );
