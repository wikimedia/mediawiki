'use strict';

const { action, assert, utils, wiki } = require( 'api-testing' );

describe( "Testing site statistics' edits value", () => {
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

	it.skip( 'should GET an increased site edits stat after editing a page', async () => {
		const title = utils.title( 'TestingSiteStats_' );
		await wikiuser.edit( title, { text: 'testing site stats ...' } );
		await wiki.runAllJobs();
		const stats = await wikiuser.action( 'query', siteStatsParams );
		const edits = parseInt( stats.query.statistics.edits, 10 );

		assert.isNumber( edits );
		assert.isAbove( edits, variables.editsStats );
	} );
} );
