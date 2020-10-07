'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( "Testing site statistics' edits value", function () {
	const siteStatsParams = {
		meta: 'siteinfo',
		siprop: 'statistics'
	};

	const variables = {};
	let wikiuser;

	before( async () => {
		wikiuser = await action.alice();
	} );

	it( 'should GET site statistics', async () => {
		const stats = await wikiuser.action( 'query', siteStatsParams );
		variables.editsStats = parseInt( stats.query.statistics.edits, 10 );
		assert.isNumber( variables.editsStats );
	} );

	it( 'should edit a page', async () => {
		const title = utils.title( 'TestingSiteStats_' );
		await wikiuser.edit( title, { text: 'testing site stats ...' } );
	} );

	it( 'should GET an increased site edits stat', async () => {
		const stats = await wikiuser.action( 'query', siteStatsParams );
		const edits = parseInt( stats.query.statistics.edits, 10 );

		assert.isNumber( edits );
		assert.isAbove( edits, variables.editsStats );
	} );
} );
