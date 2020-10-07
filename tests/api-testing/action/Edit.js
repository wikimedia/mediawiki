'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( 'The edit action', function testEditAction() {
	let alice;
	let Clark = action.getAnon();

	before( async () => {
		alice = await action.alice();
		Clark = await Clark.account( 'Clark_' );
	} );

	const pageA = utils.title( 'Edit_A_' );
	const edits = {};

	const testEditAndLog = async ( page, user ) => {
		const edit = await user.edit( page, {
			text: utils.uniq(),
			summary: utils.uniq()
		} );

		const rev1 = await user.getRevision( page );
		assert.equal( rev1.revid, edit.newrevid );
		assert.equal( rev1.user, edit.param_user );
		assert.equal( rev1.comment, edit.param_summary );
		assert.equal( rev1.timestamp, edit.newtimestamp );
		assert.equal( rev1.slots.main[ '*' ], edit.param_text );

		const rc1 = await user.getChangeEntry( { rctitle: page } );
		assert.equal( rc1.type, edit.oldrevid ? 'edit' : 'new' );
		assert.equal( rc1.revid, edit.newrevid );
		assert.equal( rc1.user, edit.param_user );
		assert.equal( rc1.comment, edit.param_summary );
		assert.equal( rc1.timestamp, edit.newtimestamp );

		const html1 = await user.getHtml( page );
		assert.include( html1, edit.param_text );

		return edit;
	};

	it( 'allows a page to be created and modified by a logged in user', async () => {
		edits.pageA1 = await testEditAndLog( pageA, alice );
		edits.pageA2 = await testEditAndLog( pageA, alice );
	} );

	it( 'allows a page to be created and modified by an anonymous user', async () => {
		const title = utils.title();
		const anon = action.getAnon();
		const { name } = await anon.meta( 'userinfo', {} );
		anon.username = name;

		await testEditAndLog( title, anon );
	} );

	it( 'skips redundant edit', async () => {
		const result = await alice.edit( pageA, {
			text: edits.pageA2.param_text,
			summary: 'some new fancy comment'
		} );

		assert.notExists( result.newrevid );
		assert.exists( result.nochange );
	} );

	it( 'logs page creation', async () => {
		const log = await alice.getLogEntry( { letitle: pageA, letype: 'create' } );

		assert.equal( log.action, 'create' );
		assert.equal( log.user, edits.pageA1.param_user );
		assert.equal( log.comment, edits.pageA1.param_summary );
		assert.equal( log.timestamp, edits.pageA1.newtimestamp );
	} );

	it( 'applies pre-save transform', async () => {
		await alice.edit( pageA, {
			text: 'Hi -- ~~~~',
			summary: 'test signature'
		} );

		const rev = await alice.getRevision( pageA );
		assert.notMatch( rev.slots.main[ '*' ], /~~~~/ );
		assert.match( rev.slots.main[ '*' ], /Alice/ );
	} );

	it( 'enforces the createonly and nocreate flags', async () => {
		const pageB = utils.title( 'Edit_B_' );

		const error1 = await alice.actionError( 'edit', {
			title: pageB,
			token: await alice.token( 'csrf' ),
			text: 'Updated content',
			summary: 'update',
			nocreate: true
		}, 'POST' );

		assert.equal( error1.code, 'missingtitle' );

		await alice.edit( pageB, {
			text: 'Initial content',
			summary: 'create',
			createonly: true
		} );

		const error2 = await alice.actionError( 'edit', {
			title: pageB,
			token: await alice.token( 'csrf' ),
			text: 'Initial content again',
			summary: 'create again',
			createonly: true
		}, 'POST' );

		assert.equal( error2.code, 'articleexists' );

		await alice.edit( pageB, {
			text: 'Updated content',
			summary: 'update',
			nocreate: true
		} );
	} );

	it( 'allows bots to set the bot flag (or not)', async () => {
		const robby = await action.robby();

		await robby.edit( pageA, {
			text: 'Bot content',
			summary: 'botting',
			bot: true
		} );

		const rc1 = await alice.getChangeEntry( { rctitle: pageA } );
		assert.exists( rc1.bot );

		await robby.edit( pageA, {
			text: 'Not bot content',
			summary: 'not botting'
		} );

		const rc2 = await alice.getChangeEntry( { rctitle: pageA } );
		assert.notExists( rc2.bot );
	} );

	it( 'does not allow regular users to set the bot flag', async () => {
		await alice.edit( pageA, {
			text: 'Fake bot content',
			summary: 'fake botting',
			bot: true
		} );

		const rc1 = await alice.getChangeEntry( { rctitle: pageA } );
		assert.notExists( rc1.bot );
	} );

	it( 'allows users to set the minor flag', async () => {
		await alice.edit( pageA, {
			text: 'Minor content',
			summary: 'minor change',
			minor: true
		} );

		const rev1 = await alice.getRevision( pageA );
		assert.exists( rev1.minor );

		const rc1 = await alice.getChangeEntry( { rctitle: pageA } );
		assert.exists( rc1.minor );
	} );

	it( 'does not allow anon users to set the minor flag', async () => {
		const anon = action.getAnon();
		await anon.edit( pageA, {
			text: 'Anon minor content',
			summary: 'anon minor change',
			minor: true
		} );

		const rev1 = await alice.getRevision( pageA );
		assert.notExists( rev1.minor );

		const rc1 = await alice.getChangeEntry( { rctitle: pageA } );
		assert.notExists( rc1.minor );
	} );

	it( 'allows a user to create a page and add sections', async () => {
		const pageB = utils.title( 'Page_B' );
		const editText = 'Some random text';
		const sectionText1 = 'Some text for the first section';
		const sectionText2 = 'Some text for the second section';

		await Clark.edit( pageB, {
			text: editText
		} );

		await Clark.edit( pageB, {
			section: 'new',
			sectiontitle: 'First',
			text: sectionText1
		} );

		await Clark.edit( pageB, {
			section: 'new',
			sectiontitle: 'Second',
			text: sectionText2
		} );

		const firstModification = await Clark.getHtml( pageB );
		assert.include( firstModification, editText );
		assert.include( firstModification, sectionText1 );
		assert.include( firstModification, sectionText2 );

		await Clark.edit( pageB, {
			section: 0,
			text: `${editText} some text`
		} );

		const secondModification = await Clark.getHtml( pageB );
		assert.include( secondModification, `${editText} some text` );
		assert.include( secondModification, sectionText1 );
		assert.include( secondModification, sectionText2 );

		await Clark.edit( pageB, {
			section: 1,
			text: `== First ==\n${sectionText1} some text`
		} );

		const thirdModification = await Clark.getHtml( pageB );
		assert.include( thirdModification, `${editText} some text` );
		assert.include( thirdModification, `${sectionText1} some text` );
		assert.include( thirdModification, sectionText2 );

		await Clark.edit( pageB, {
			section: 1,
			text: ''
		} );

		const fourthModification = await Clark.getHtml( pageB );
		assert.include( fourthModification, `${editText} some text` );
		assert.notInclude( fourthModification, '== First ==' );
		assert.include( fourthModification, sectionText2 );
	} );

	it( 'allows a user to append text to the end of the page and prepend text to the beginning of the page', async () => {
		const page = utils.title( 'Page_' );
		const text = 'text';
		const bottom = 'bottom';
		const top = 'top';

		await Clark.edit( page, {
			text: text
		} );
		await Clark.edit( page, {
			appendtext: bottom
		} );
		await Clark.edit( page, {
			prependtext: top
		} );

		const pageContent = await Clark.getHtml( page );
		assert.include( pageContent, `${top}${text}${bottom}` );
	} );

	it( 'should allow a user make edits on a page passing the timestamp from the previous edit on the same page the basetimetamp', async () => {
		const page = utils.title( 'Page_' );
		const editContent = 'An edit from Clark';
		const rev1 = await alice.edit( page, {
			text: 'An edit from Alice'
		} );
		await Clark.edit( page, {
			text: editContent,
			basetimestamp: rev1.timestamp
		} );
		const pageContent = await Clark.getHtml( page );
		assert.include( pageContent, editContent );
	} );

	it( 'should throw an error when a user tries to set the basetimestamp to a date/time different from the timestamp of the previous edit', async () => {
		const page = utils.title( 'Page_' );
		const rev1 = await alice.edit( page, {
			text: 'An edit from Alice'
		} );
		await Clark.edit( page, {
			text: 'An edit from Clark',
			basetimestamp: rev1.timestamp
		} );
		const error = await alice.actionError( 'edit', {
			title: page,
			text: 'Another edit by Alice',
			token: await alice.token( 'csrf' ),
			basetimestamp: '2001-01-01T00:00:00Z'
		}, 'POST' );
		assert.equal( error.code, 'editconflict' );
	} );

	it( 'should throw an error when a wrong value is passed to md5', async () => {
		const page = utils.title( 'Page' );
		const error = await Clark.actionError( 'edit', {
			title: page,
			token: await Clark.token( 'csrf' ),
			text: 'Some Text',
			summary: 'Bad md5',
			md5: 'sometext'
		}, 'POST' );
		assert.equal( error.code, 'badmd5' );
	} );

	it( 'should edit the page when the correct md5 hash of the text parameter is passed to it', async () => {
		const page = utils.title( 'Page_' );
		const text = 'An edit with the correct md5';
		await Clark.edit( page, {
			text: text,
			md5: 'd19dc30a12f31ec3684743bc010ef148'
		} );

		const pageContent = await Clark.getHtml( page );
		assert.include( pageContent, text );
	} );

	it( 'should edit the page when the correct md5 hash of the appendtext is passed to the md5 parameter as the appendtext parameter should override the text parameter', async () => {
		const page = utils.title( 'Page_' );
		const text = 'An edit containing text and appendtext parameters with the correct md5';
		const bottom = 'An edit just for the bottom';
		await Clark.edit( page, {
			text: text,
			appendtext: bottom,
			md5: '83edcea6d422ba921d0442ca29abce1d'
		} );

		const pageContent = await Clark.getHtml( page );
		assert.notInclude( pageContent, text );
		assert.include( pageContent, bottom );
	} );

	it( 'should edit the page when the correct md5 hash of the prependtext is passed to the md5 parameter as the prependtext parameter should override the text parameter', async () => {
		const page = utils.title( 'Page_' );
		const text = 'An edit containing text and appendtext parameters with the correct md5';
		const top = 'An edit just for the top';
		await Clark.edit( page, {
			text: text,
			prependtext: top,
			md5: '0f5f08a431409bc0c504c090fe4f2f11'
		} );

		const pageContent = await Clark.getHtml( page );
		assert.notInclude( pageContent, text );
		assert.include( pageContent, top );
	} );

	it( 'should edit the page when the correct md5 hash of the prependtext and appendtext concatenated is passed to the md5 parameter', async () => {
		const page = utils.title( 'Page_' );
		const text = 'An edit containing text and appendtext parameters with the correct md5';
		const top = 'An edit just for the top';
		const bottom = 'An edit just for the bottom';
		await Clark.edit( page, {
			text: text,
			prependtext: top,
			appendtext: bottom,
			md5: 'b1df72139fc1202b6d817d9b1459e2d5'
		} );

		const pageContent = await Clark.getHtml( page );
		assert.notInclude( pageContent, text );
		assert.include( pageContent, top + bottom );
	} );

	it( 'should throw an error when the wrong md5 hash of the prependtext and appendtext concatenated is passed to the md5 parameter', async () => {
		const page = utils.title( 'Page_' );
		const text = 'An edit containing text and appendtext parameters with the correct md5';
		const top = 'An edit just for the top';
		const bottom = 'An edit just for the bottom';
		const error = await Clark.actionError( 'edit', {
			title: page,
			token: await Clark.token( 'csrf' ),
			text: text,
			prependtext: top,
			appendtext: bottom,
			summary: 'Bad md5',
			md5: 'badmd5139fc1202b6d817d9b1459e2d5'
		}, 'POST' );
		assert.equal( error.code, 'badmd5' );
	} );
	it( 'should override the text parameter with the appendtext parameter', async () => {
		const page = utils.title( 'Page_' );
		const text = 'A text that is to be overriden';
		const bottom = 'This text should override the text parameter';

		await Clark.edit( page, {
			text: text,
			appendtext: bottom
		} );

		const pageContent = await Clark.getHtml( page );
		assert.notInclude( pageContent, text );
		assert.include( pageContent, bottom );
	} );

	it( 'should override the text parameter with the prependtext parameter', async () => {
		const page = utils.title( 'Page_' );
		const text = 'A text that is to be overriden';
		const top = 'This text should override the text parameter';

		await Clark.edit( page, {
			text: text,
			prependtext: top
		} );

		const pageContent = await Clark.getHtml( page );
		assert.notInclude( pageContent, text );
		assert.include( pageContent, top );
	} );

	it( 'should override the text parameter with the appendtext and prependtext parameters', async () => {
		const page = utils.title( 'Page_' );
		const text = 'A text that is to be overriden';
		const top = 'This text should override the text parameter on top';
		const bottom = 'This text should override the text parameter at the end';

		await Clark.edit( page, {
			text: text,
			prependtext: top,
			appendtext: bottom
		} );

		const pageContent = await Clark.getHtml( page );
		assert.notInclude( pageContent, text );
		assert.include( pageContent, top + bottom );
	} );
} );
