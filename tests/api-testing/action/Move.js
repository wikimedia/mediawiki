'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( 'Move action', function () {
	const userPage = utils.title( 'MoveWith_' );
	const page1 = utils.title( 'MoveWithout_' );
	const page2 = `User:${userPage}`;
	const page1Subpage = utils.title();
	const page2Subpage = utils.title();
	const page1Talk = `Talk:${page1}`;
	const page2Talk = `User_talk:${userPage}`;
	let mindy;

	before( async () => {
		mindy = await action.mindy();

		// creating page1, a subpage, and talkpage
		await mindy.edit( page1, { text: 'Move without redirect, subpage and talkpage' } );
		await mindy.edit( `${page1}/${page1Subpage}`, { text: `Subpage of ${page1}` } );
		await mindy.edit( page1Talk, { text: `Talk page of ${page1}` } );

		// creating page2, a subpage, and talkpage
		await mindy.edit( page2, { text: 'Move with redirect, subpage and talkpage' } );
		await mindy.edit( `${page2}/${page2Subpage}`, { text: `Subpage of ${page2}` } );
		await mindy.edit( page2Talk, { text: `Talk page of ${page2}` } );
	} );

	it( 'should move a page without a redirect or its subpages and talkpages', async () => {
		const newPage1 = `${page1}_${utils.title()}`;
		const { move } = await mindy.action( 'move',
			{
				from: page1,
				to: newPage1,
				noredirect: true,
				reason: 'testing',
				token: mindy.tokens.csrftoken
			},
			'POST' );

		assert.sameTitle( move.from, page1 );
		assert.sameTitle( move.to, newPage1 );
		assert.equal( move.reason, 'testing' );
		assert.notExists( move.redirectcreated );
		assert.notExists( move.subpages );
		assert.notExists( move.talkto );
		assert.notExists( move.talkfrom );

		const newPage = await mindy.getHtml( newPage1 );
		const oldPage = await mindy.actionError( 'parse', { page: page1 } );

		assert.match( newPage, /Move without redirect, subpage and talkpage/ );
		assert.equal( oldPage.code, 'missingtitle' );
	} );

	it( 'should move a page with a redirect and its subpages and talkpages', async () => {
		const newTitle = utils.title( 'Move_' );
		const newPage2 = `User:${newTitle}`;
		const newPage2Talk = `User_talk:${newTitle}`;
		const { move } = await mindy.action( 'move',
			{
				from: page2,
				to: newPage2,
				reason: 'testing',
				movetalk: '',
				movesubpages: '',
				token: mindy.tokens.csrftoken
			},
			'POST' );

		assert.sameTitle( move.from, page2 );
		assert.sameTitle( move.to, newPage2 );
		assert.sameTitle( move.talkfrom, page2Talk );
		assert.sameTitle( move.talkto, newPage2Talk );
		assert.sameTitle( move.subpages[ 0 ].from, `${page2}/${page2Subpage}` );
		assert.sameTitle( move.subpages[ 0 ].to, `${newPage2}/${page2Subpage}` );
		assert.equal( move.reason, 'testing' );
		assert.exists( move.redirectcreated );
		assert.exists( move[ 'subpages-talk' ] );

		const newPageHtml = await mindy.getHtml( newPage2 );
		assert.match( newPageHtml, /Move with redirect, subpage and talkpage/ );

		const redirectInfo = await mindy.action( 'query', { titles: page2, redirects: true } );
		assert.isDefined( redirectInfo.query.redirects, page2 );
		assert.sameTitle( redirectInfo.query.redirects[ 0 ].from, page2 );
		assert.sameTitle( redirectInfo.query.redirects[ 0 ].to, newPage2 );
	} );
} );
