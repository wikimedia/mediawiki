'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( 'The delete/undelete action', function testDeleteAction() {
	const title = utils.title( 'Delete_' );
	let alice, mindy;

	before( async () => {
		[ alice, mindy ] = await Promise.all( [
			action.alice(),
			action.mindy()
		] );
	} );

	it( 'allows an admin to delete a page', async () => {
		await alice.edit( title, {
			text: 'Testing the testy test',
			summary: 'first',
			createonly: true
		} );

		const result = await mindy.action( 'delete', {
			title,
			summary: 'testing',
			token: await mindy.token( 'csrf' )
		}, 'POST' );

		assert.sameTitle( result.delete.title, title );

		const error = await mindy.actionError( 'parse', { page: title } );
		assert.equal( error.code, 'missingtitle' );
	} );

	it( 'logs the deletion', async () => {
		const log = await alice.getLogEntry( {
			letype: 'delete',
			letitle: title
		} );
		assert.equal( log.user, mindy.username );
		assert.equal( log.action, 'delete' );
	} );

	it( 'allows an admin to undelete a page', async () => {
		const result = await mindy.action( 'undelete', {
			title,
			summary: 'testing',
			token: await mindy.token( 'csrf' )
		}, 'POST' );

		assert.sameTitle( result.undelete.title, title );

		const html = await alice.getHtml( title );
		assert.match( html, /Testing the testy test/ );
	} );

	it( 'logs the undeletion', async () => {
		const log = await alice.getLogEntry( {
			letype: 'delete',
			letitle: title
		} );
		assert.equal( log.user, mindy.username );
		assert.equal( log.action, 'restore' );
	} );
} );
