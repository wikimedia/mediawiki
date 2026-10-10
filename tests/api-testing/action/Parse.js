'use strict';

const { action, assert, utils } = require( 'api-testing' );

describe( 'The parse action', () => {
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

		assert.match( result.parse.text[ '*' ], /This is a <i[^>]*?>test<\/i>/ );
		// The parsed HTML is a body fragment, not a full <html> document.
		assert.notMatch( result.parse.text[ '*' ], /<html[\s/>]/i );
	} );

	it( 'returns a body fragment (not a full document) when parsing with Parsoid', async () => {
		const result = await alice.action( 'parse', {
			page: pageTitle,
			parser: 'parsoid'
		} );

		assert.include( result.parse.text[ '*' ], 'test' );
		// Parsoid output via action=parse is a body fragment, not a full document.
		assert.notMatch( result.parse.text[ '*' ], /<html[\s/>]/i );
	} );

	it( 'supports parsing text supplied as a parameter', async () => {
		const result = await alice.action( 'parse', {
			title: pageTitle,
			text: 'This is another \'\'test\'\''
		} );

		assert.match( result.parse.text[ '*' ], /another <i[^>]*>test<\/i>/ );
	} );

	describe( 'with magic words', () => {
		it( 'supports __FORCETOC__', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				text: '__FORCETOC__\n' +
                    '== One ==' +
                    '== Two =='
			} );

			assert.match( result.parse.text[ '*' ], /id="toc"|property="mw:PageProp\/toc"/ );
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

			assert.notMatch( result.parse.text[ '*' ], /id="toc"|property="mw:PageProp\/toc"/ );
		} );
	} );

	describe( 'with variables', () => {
		it( 'supports {{PAGENAMEE}}', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				text: 'This is {{PAGENAMEE}}'
			} );

			assert.match( result.parse.text[ '*' ], new RegExp( `This is (<span[^>]*>)?${ pageTitle }(</span>)?` ) );
		} );
		it( 'supports {{REVISIONID}} and {{REVISIONUSER}} via parameters', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				revid: edits.pageCreation.newrevid,
				text: 'This is {{REVISIONID}} by {{REVISIONUSER}}'
			} );

			assert.match(
				result.parse.text[ '*' ],
				new RegExp( `This is (<span[^>]*>)?${ edits.pageCreation.newrevid }(</span>)?` +
					` by (<span[^>]*>)?${ edits.pageCreation.param_user }(</span>)?` )
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

			assert.match(
				result.parse.text[ '*' ],
				new RegExp( `This is (<span[^>]*>)?${ anotherEdit.newrevid }(</span>)?` +
					` by (<span[^>]*>)?${ anotherEdit.param_user }(</span>)?` )
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
				text: `Say: {{${ templateTitle }}}`
			} );

			assert.match( result.parse.text[ '*' ], /Say: (<span[^>]*>)?Hello world!(<\/span>)?/ );
		} );

		it( 'supports positional parameters', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				text: `Say: {{${ templateTitle }|you}}`
			} );

			assert.match( result.parse.text[ '*' ], /Say: (<span[^>]*>)?Hello you!(<\/span>)?/ );
		} );

		it( 'supports named parameters', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				text: `Say: {{${ templateTitle }|greeting=Ciao}}`
			} );

			assert.match( result.parse.text[ '*' ], /Say: (<span[^>]*>)?Ciao world!(<\/span>)?/ );
		} );
	} );

	describe( 'with parser functions', () => {
		it( 'supports {{plural}}', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				text: '{{plural:1|one|many}} or {{plural:2|one|many}}'
			} );

			assert.match(
				result.parse.text[ '*' ],
				/(<span[^>]*>)?one(<\/span>)? or (<span[^>]*>)?many(<\/span>)?/
			);
		} );
		it( 'supports {{ns}}', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				text: '{{ns:1}}, {{ns:2}}, {{ns:3}}'
			} );

			assert.match(
				result.parse.text[ '*' ],
				/(<span[^>]*>)?Talk(<\/span>)?, (<span[^>]*>)?User(<\/span>)?, (<span[^>]*>)?User talk(<\/span>)?/
			);
		} );
		it( 'supports {{uc}} and {{lc}}', async () => {
			const result = await alice.action( 'parse', {
				title: pageTitle,
				text: '{{uc:Foo}} or {{lc:Foo}}'
			} );

			assert.match(
				result.parse.text[ '*' ],
				/(<span[^>]*>)?FOO(<\/span>)? or (<span[^>]*>)?foo(<\/span>)?/
			);
		} );
	} );

} );
