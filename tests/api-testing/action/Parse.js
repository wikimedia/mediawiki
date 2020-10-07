'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( 'The parse action', function () {
	let alice;
	const pageTitle = utils.title( 'Parsing_' );
	const edits = {};

	before( async () => {
		[ alice ] = await Promise.all( [
			action.alice()
		] );

		edits.pageCreation = await alice.edit( pageTitle, {
			text: 'This is a \'\'test\'\''
		} );
	} );

	it( 'supports parsing the current content of a page', async () => {
		const result = await alice.action( 'parse', {
			page: pageTitle
		} );

		assert.include( result.parse.text[ '*' ], 'This is a <i>test</i>' );
	} );

	it( 'supports parsing text supplied as a parameter', async () => {
		const result = await alice.action( 'parse', {
			title: pageTitle,
			text: 'This is another \'\'test\'\''
		} );

		assert.include( result.parse.text[ '*' ], 'another <i>test</i>' );
	} );

	describe( 'with magic words', () => {
		it( 'supports __FORCETOC__', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				text: '__FORCETOC__\n' +
                    '== One ==' +
                    '== Two =='
			} );

			assert.include( result.parse.text[ '*' ], 'id="toc"' );
		} );

		it( 'supports __NOTOC__', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				text: '__NOTOC__\n' +
                    '== One ==' +
                    '== Two ==' +
                    '== Three ==' +
                    '== Four =='
			} );

			assert.notInclude( result.parse.text[ '*' ], 'id="toc"' );
		} );
	} );

	describe( 'with variables', () => {
		it( 'supports {{PAGENAMEE}}', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				text: 'This is {{PAGENAMEE}}'
			} );

			assert.include( result.parse.text[ '*' ], `This is ${pageTitle}` );
		} );
		it( 'supports {{REVISIONID}} and {{REVISIONUSER}} via parameters', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				revid: edits.pageCreation.newrevid,
				text: 'This is {{REVISIONID}} by {{REVISIONUSER}}'
			} );

			assert.include(
				result.parse.text[ '*' ],
				`This is ${edits.pageCreation.newrevid} by ${edits.pageCreation.param_user}`
			);
		} );
		it( 'supports {{REVISIONID}} and {{REVISIONUSER}} of a saved revision', async () => {
			const anotherTitle = utils.title( 'Parser_saved_magic_' );

			const anotherEdit = await alice.edit( anotherTitle, {
				text: 'This is {{REVISIONID}} by {{REVISIONUSER}}'
			} );

			const result = await alice.action( 'parse', {
				page: anotherTitle
			} );

			assert.include(
				result.parse.text[ '*' ],
				`This is ${anotherEdit.newrevid} by ${anotherEdit.param_user}`
			);
		} );
	} );

	describe( 'with templates', () => {
		const templateTitle = utils.title( 'Template:Parsing_' );
		const templateText = '{{{greeting|Hello}}} {{{1|world}}}!';

		before( async () => {
			await alice.edit( templateTitle, { text: templateText } );
		} );

		it( 'supports optional parameters', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				text: `Say: {{${templateTitle}}}`
			} );

			assert.include( result.parse.text[ '*' ], 'Say: Hello world!' );
		} );

		it( 'supports positional parameters', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				text: `Say: {{${templateTitle}|you}}`
			} );

			assert.include( result.parse.text[ '*' ], 'Say: Hello you!' );
		} );

		it( 'supports named parameters', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				text: `Say: {{${templateTitle}|greeting=Ciao}}`
			} );

			assert.include( result.parse.text[ '*' ], 'Say: Ciao world!' );
		} );
	} );

	describe( 'with parser functions', () => {
		it( 'supports {{plural}}', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				text: '{{plural:1|one|many}} or {{plural:2|one|many}}'
			} );

			assert.include( result.parse.text[ '*' ], 'one or many' );
		} );
		it( 'supports {{ns}}', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				text: '{{ns:1}}, {{ns:2}}, {{ns:3}}'
			} );

			assert.include( result.parse.text[ '*' ], 'Talk, User, User talk' );
		} );
		it( 'supports {{uc}} and {{lc}}', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				text: '{{uc:Foo}} or {{lc:Foo}}'
			} );

			assert.include( result.parse.text[ '*' ], 'FOO or foo' );
		} );
	} );

} );
