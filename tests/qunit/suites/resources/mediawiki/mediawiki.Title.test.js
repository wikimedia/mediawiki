( function () {
	/* eslint-disable camelcase */
	var repeat = function ( input, multiplier ) {
			return new Array( multiplier + 1 ).join( input );
		},
		// See also TitleTest.php#testSecureAndSplit
		sharedCases = {
			valid: [
				'Sandbox',
				'A "B"',
				'A \'B\'',
				'.com',
				'~',
				'"',
				'\'',
				'Talk:Sandbox',
				'Talk:Foo:Sandbox',
				'File:Example.svg',
				'File_talk:Example.svg',
				'Foo/.../Sandbox',
				'Sandbox/...',
				'A~~',
				':A',
				// Length is 256 total, but only title part matters
				'Category:' + repeat( 'x', 248 ),
				repeat( 'x', 252 )
			],
			invalid: [
				'',
				':',
				'__  __',
				'  __  ',
				// Bad characters forbidden regardless of wgLegalTitleChars
				'A [ B',
				'A ] B',
				'A { B',
				'A } B',
				'A < B',
				'A > B',
				'A | B',
				'A \t B',
				'A \n B',
				// URL encoding
				'A%20B',
				'A%23B',
				'A%2523B',
				// XML/HTML character entity references
				// Note: The ones with # are commented out as those are interpreted as fragment and
				// as such end up being valid.
				'A &eacute; B',
				// 'A &#233; B',
				// 'A &#x00E9; B',
				// Subject of NS_TALK does not roundtrip to NS_MAIN
				'Talk:File:Example.svg',
				// Directory navigation
				'.',
				'..',
				'./Sandbox',
				'../Sandbox',
				'Foo/./Sandbox',
				'Foo/../Sandbox',
				'Sandbox/.',
				'Sandbox/..',
				// Tilde
				'A ~~~ Name',
				'A ~~~~ Signature',
				'A ~~~~~ Timestamp',
				repeat( 'x', 256 ),
				// Extension separation is a js invention, for length
				// purposes it is part of the title
				repeat( 'x', 252 ) + '.json',
				// Namespace prefix without actual title
				'Talk:',
				'Category: ',
				'Category: #bar'
			]
		};

	QUnit.module( 'mediawiki.Title', QUnit.newMwEnvironment( {
		// mw.Title relies on these three config vars
		// Restore them after each test run
		config: {
			wgFormattedNamespaces: {
				'-2': 'Media',
				'-1': 'Special',
				0: '',
				1: 'Talk',
				2: 'User',
				3: 'User talk',
				4: 'Wikipedia',
				5: 'Wikipedia talk',
				6: 'File',
				7: 'File talk',
				8: 'MediaWiki',
				9: 'MediaWiki talk',
				10: 'Template',
				11: 'Template talk',
				12: 'Help',
				13: 'Help talk',
				14: 'Category',
				15: 'Category talk',
				// testing custom / localized namespace
				100: 'Penguins'
			},
			wgNamespaceIds: {
				media: -2,
				special: -1,
				'': 0,
				talk: 1,
				user: 2,
				user_talk: 3,
				wikipedia: 4,
				wikipedia_talk: 5,
				file: 6,
				file_talk: 7,
				mediawiki: 8,
				mediawiki_talk: 9,
				template: 10,
				template_talk: 11,
				help: 12,
				help_talk: 13,
				category: 14,
				category_talk: 15,
				image: 6,
				image_talk: 7,
				project: 4,
				project_talk: 5,
				// Testing custom namespaces and aliases
				penguins: 100,
				antarctic_waterfowl: 100
			},
			wgCaseSensitiveNamespaces: []
		}
	} ) );

	QUnit.test( 'constructor', function ( assert ) {
		sharedCases.valid.forEach( function ( title ) {
			// Check no exception is thrown
			return new mw.Title( title );
		} );
		sharedCases.invalid.forEach( function ( title ) {
			assert.throws( function () {
				return new mw.Title( title );
			}, title );
		} );
	} );

	QUnit.test( 'newFromText', function ( assert ) {
		sharedCases.valid.forEach( function ( title ) {
			assert.strictEqual(
				typeof mw.Title.newFromText( title ),
				'object',
				title
			);
		} );
		sharedCases.invalid.forEach( function ( title ) {
			assert.strictEqual(
				mw.Title.newFromText( title ),
				null,
				title
			);
		} );
	} );

	QUnit.test( 'makeTitle', function ( assert ) {
		var cases,
			NS_MAIN = 0,
			NS_TALK = 1,
			NS_TEMPLATE = 10;

		cases = [
			{
				namespace: NS_TEMPLATE,
				text: 'Foo',
				expected: 'Template:Foo'
			},
			{
				namespace: NS_TEMPLATE,
				text: 'Category:Foo',
				expected: 'Template:Category:Foo'
			},
			{
				namespace: NS_TEMPLATE,
				text: 'Template:Foo',
				expected: 'Template:Template:Foo'
			},
			{
				namespace: NS_TALK,
				text: 'Help:Foo',
				expected: null
			},
			{
				namespace: NS_TEMPLATE,
				text: '<',
				expected: null
			},
			{
				namespace: NS_MAIN,
				text: 'Help:Foo',
				expected: 'Help:Foo'
			}
		];

		cases.forEach( function ( caseItem ) {
			var title = mw.Title.makeTitle( caseItem.namespace, caseItem.text );
			assert.strictEqual( title && title.getPrefixedText(), caseItem.expected );
		} );
	} );

	QUnit.test( 'Basic parsing', function ( assert ) {
		var title;
		title = new mw.Title( 'File:Foo_bar.JPG' );

		assert.strictEqual( title.getNamespaceId(), 6 );
		assert.strictEqual( title.getNamespacePrefix(), 'File:' );
		assert.strictEqual( title.getName(), 'Foo_bar' );
		assert.strictEqual( title.getNameText(), 'Foo bar' );
		assert.strictEqual( title.getExtension(), 'JPG' );
		assert.strictEqual( title.getDotExtension(), '.JPG' );
		assert.strictEqual( title.getMain(), 'Foo_bar.JPG' );
		assert.strictEqual( title.getMainText(), 'Foo bar.JPG' );
		assert.strictEqual( title.getPrefixedDb(), 'File:Foo_bar.JPG' );
		assert.strictEqual( title.getPrefixedText(), 'File:Foo bar.JPG' );

		title = new mw.Title( 'Foo#bar' );
		assert.strictEqual( title.getPrefixedText(), 'Foo' );
		assert.strictEqual( title.getFragment(), 'bar' );

		title = new mw.Title( '.foo' );
		assert.strictEqual( title.getPrefixedText(), '.foo' );
		assert.strictEqual( title.getName(), '' );
		assert.strictEqual( title.getNameText(), '' );
		assert.strictEqual( title.getExtension(), 'foo' );
		assert.strictEqual( title.getDotExtension(), '.foo' );
		assert.strictEqual( title.getMain(), '.foo' );
		assert.strictEqual( title.getMainText(), '.foo' );
		assert.strictEqual( title.getPrefixedDb(), '.foo' );
		assert.strictEqual( title.getPrefixedText(), '.foo' );
	} );

	QUnit.test( 'Transformation', function ( assert ) {
		var title;

		title = new mw.Title( 'File:quux pif.jpg' );
		assert.strictEqual( title.getNameText(), 'Quux pif', 'First character of title' );

		title = new mw.Title( 'File:Glarg_foo_glang.jpg' );
		assert.strictEqual( title.getNameText(), 'Glarg foo glang', 'Underscores' );

		title = new mw.Title( 'User:ABC.DEF' );
		assert.strictEqual( title.toText(), 'User:ABC.DEF', 'Round trip text' );
		assert.strictEqual( title.getNamespaceId(), 2, 'Parse canonical namespace prefix' );

		title = new mw.Title( 'Image:quux pix.jpg' );
		assert.strictEqual( title.getNamespacePrefix(), 'File:', 'Transform alias to canonical namespace' );

		title = new mw.Title( 'uSEr:hAshAr' );
		assert.strictEqual( title.toText(), 'User:HAshAr' );
		assert.strictEqual( title.getNamespaceId(), 2, 'Case-insensitive namespace prefix' );

		title = new mw.Title( 'Foo \u00A0\u1680\u180E\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200A\u2028\u2029\u202F\u205F\u3000 bar' );
		assert.strictEqual( title.getMain(), 'Foo_bar', 'Merge multiple types of whitespace/underscores into a single underscore' );

		title = new mw.Title( 'Foo\u200E\u200F\u202A\u202B\u202C\u202D\u202Ebar' );
		assert.strictEqual( title.getMain(), 'Foobar', 'Strip Unicode bidi override characters' );

		// Regression test: Previously it would only detect an extension if there is no space after it
		title = new mw.Title( 'Example.js  ' );
		assert.strictEqual( title.getExtension(), 'js', 'Space after an extension is stripped' );

		title = new mw.Title( 'Example#foo' );
		assert.strictEqual( title.getFragment(), 'foo', 'Fragment' );

		title = new mw.Title( 'Example#_foo_bar baz_' );
		assert.strictEqual( title.getFragment(), ' foo bar baz', 'Fragment' );
	} );

	QUnit.test( 'Namespace detection and conversion', function ( assert ) {
		var title;

		title = new mw.Title( 'File:User:Example' );
		assert.strictEqual( title.getNamespaceId(), 6, 'Titles can contain namespace prefixes, which are otherwise ignored' );

		title = new mw.Title( 'Example', 6 );
		assert.strictEqual( title.getNamespaceId(), 6, 'Default namespace passed is used' );

		title = new mw.Title( 'User:Example', 6 );
		assert.strictEqual( title.getNamespaceId(), 2, 'Included namespace prefix overrides the given default' );

		title = new mw.Title( ':Example', 6 );
		assert.strictEqual( title.getNamespaceId(), 0, 'Colon forces main namespace' );

		title = new mw.Title( 'something.PDF', 6 );
		assert.strictEqual( title.toString(), 'File:Something.PDF' );

		title = new mw.Title( 'NeilK', 3 );
		assert.strictEqual( title.toString(), 'User_talk:NeilK' );
		assert.strictEqual( title.toText(), 'User talk:NeilK' );

		title = new mw.Title( 'Frobisher', 100 );
		assert.strictEqual( title.toString(), 'Penguins:Frobisher' );

		title = new mw.Title( 'antarctic_waterfowl:flightless_yet_cute.jpg' );
		assert.strictEqual( title.toString(), 'Penguins:Flightless_yet_cute.jpg' );

		title = new mw.Title( 'Penguins:flightless_yet_cute.jpg' );
		assert.strictEqual( title.toString(), 'Penguins:Flightless_yet_cute.jpg' );
	} );

	QUnit.test( 'isTalkPage/getTalkPage/getSubjectPage', function ( assert ) {
		var title;

		title = new mw.Title( 'User:Foo' );
		assert.strictEqual( title.isTalkPage(), false, 'Non-talk page detected as such' );
		assert.strictEqual( title.getSubjectPage().getPrefixedText(), 'User:Foo', 'getSubjectPage on a subject page is a no-op' );

		title = title.getTalkPage();
		assert.strictEqual( title.getPrefixedText(), 'User talk:Foo', 'getTalkPage creates correct title' );
		assert.strictEqual( title.getTalkPage().getPrefixedText(), 'User talk:Foo', 'getTalkPage on a talk page is a no-op' );
		assert.strictEqual( title.isTalkPage(), true, 'Talk page is detected as such' );

		title = title.getSubjectPage();
		assert.strictEqual( title.getPrefixedText(), 'User:Foo', 'getSubjectPage creates correct title' );

		title = new mw.Title( 'Special:AllPages' );
		assert.strictEqual( title.isTalkPage(), false, 'Special page is not a talk page' );
		assert.strictEqual( title.getTalkPage(), null, 'getTalkPage not valid for this namespace' );
		assert.strictEqual( title.getSubjectPage().getPrefixedText(), 'Special:AllPages', 'getSubjectPage is self for special pages' );

		title = new mw.Title( 'Category:Project:Maintenance' );
		assert.strictEqual( title.getTalkPage().getPrefixedText(), 'Category talk:Project:Maintenance', 'getTalkPage is not confused by colon in main text' );
		title = new mw.Title( 'Category talk:Project:Maintenance' );
		assert.strictEqual( title.getSubjectPage().getPrefixedText(), 'Category:Project:Maintenance', 'getSubjectPage is not confused by colon in main text' );

		title = new mw.Title( 'Foo#Caption' );
		assert.strictEqual( title.getFragment(), 'Caption', 'Subject page has a fragment' );
		title = title.getTalkPage();
		assert.strictEqual( title.getPrefixedText(), 'Talk:Foo', 'getTalkPage creates correct title' );
		assert.strictEqual( title.getFragment(), null, 'getTalkPage does not copy the fragment' );
	} );

	QUnit.test( 'wantSignaturesNamespace', function ( assert ) {
		mw.config.set( 'wgExtraSignatureNamespaces', [] );
		assert.strictEqual( mw.Title.wantSignaturesNamespace( 0 ), false, 'Main namespace has no signatures' );
		assert.strictEqual( mw.Title.wantSignaturesNamespace( 1 ), true, 'Talk namespace has signatures' );
		assert.strictEqual( mw.Title.wantSignaturesNamespace( 2 ), false, 'NS2 has no signatures' );
		assert.strictEqual( mw.Title.wantSignaturesNamespace( 3 ), true, 'NS3 has signatures' );

		mw.config.set( 'wgExtraSignatureNamespaces', [ 0 ] );
		assert.strictEqual( mw.Title.wantSignaturesNamespace( 0 ), true, 'Main namespace has signatures when explicitly defined' );
	} );

	QUnit.test( 'Throw error on invalid title', function ( assert ) {
		assert.throws( function () {
			return new mw.Title( '' );
		}, 'Throw error on empty string' );
	} );

	QUnit.test( 'Case-sensivity', function ( assert ) {
		var title;

		// Default config
		mw.config.set( 'wgCaseSensitiveNamespaces', [] );

		title = new mw.Title( 'article' );
		assert.strictEqual( title.toString(), 'Article', 'Default config: No sensitive namespaces by default. First-letter becomes uppercase' );

		title = new mw.Title( 'ß' );
		assert.strictEqual( title.toString(), 'ß', 'Uppercasing matches PHP behaviour (ß -> ß, not SS)' );

		title = new mw.Title( 'ǆ (digraph)' );
		assert.strictEqual( title.toString(), 'ǅ_(digraph)', 'Uppercasing matches PHP behaviour (ǆ -> ǅ, not Ǆ)' );

		// $wgCapitalLinks = false;
		mw.config.set( 'wgCaseSensitiveNamespaces', [ 0, -2, 1, 4, 5, 6, 7, 10, 11, 12, 13, 14, 15 ] );

		title = new mw.Title( 'article' );
		assert.strictEqual( title.toString(), 'article', '$wgCapitalLinks=false: Article namespace is sensitive, first-letter case stays lowercase' );

		title = new mw.Title( 'john', 2 );
		assert.strictEqual( title.toString(), 'User:John', '$wgCapitalLinks=false: User namespace is insensitive, first-letter becomes uppercase' );
	} );

	QUnit.test( 'toString / toText', function ( assert ) {
		var title = new mw.Title( 'Some random page' );

		assert.strictEqual( title.toString(), title.getPrefixedDb() );
		assert.strictEqual( title.toText(), title.getPrefixedText() );
	} );

	QUnit.test( 'getExtension', function ( assert ) {
		function extTest( pagename, ext, description ) {
			var title = new mw.Title( pagename );
			assert.strictEqual( title.getExtension(), ext, description || pagename );
		}

		extTest( 'MediaWiki:Vector.js', 'js' );
		extTest( 'User:Example/common.css', 'css' );
		extTest( 'File:Example.longextension', 'longextension', 'Extension parsing not limited (T38151)' );
		extTest( 'Example/information.json', 'json', 'Extension parsing not restricted from any namespace' );
		extTest( 'Foo.', null, 'Trailing dot is not an extension' );
		extTest( 'Foo..', null, 'Trailing dots are not an extension' );
		extTest( 'Foo.a.', null, 'Page name with dots and ending in a dot does not have an extension' );

		// @broken: Throws an exception
		// extTest( '.NET', null, 'Leading dot is (or is not?) an extension' );
	} );

	QUnit.test( 'exists', function ( assert ) {
		var title;

		// Empty registry, checks default to null

		title = new mw.Title( 'Some random page', 4 );
		assert.strictEqual( title.exists(), null, 'Return null with empty existance registry' );

		// Basic registry, checks default to boolean
		mw.Title.exist.set( [ 'Does_exist', 'User_talk:NeilK', 'Wikipedia:Sandbox_rules' ], true );
		mw.Title.exist.set( [ 'Does_not_exist', 'User:John', 'Foobar' ], false );

		title = new mw.Title( 'Project:Sandbox rules' );
		assert.assertTrue( title.exists(), 'Return true for page titles marked as existing' );
		title = new mw.Title( 'Foobar' );
		assert.assertFalse( title.exists(), 'Return false for page titles marked as nonexistent' );

	} );

	QUnit.test( 'getUrl', function ( assert ) {
		var title;
		mw.config.set( {
			wgScript: '/w/index.php',
			wgArticlePath: '/wiki/$1'
		} );

		title = new mw.Title( 'Foobar' );
		assert.strictEqual( title.getUrl(), '/wiki/Foobar', 'Basic functionality, getUrl uses mw.util.getUrl' );
		assert.strictEqual( title.getUrl( { action: 'edit' } ), '/w/index.php?title=Foobar&action=edit', 'Basic functionality, \'params\' parameter' );

		title = new mw.Title( 'John Doe', 3 );
		assert.strictEqual( title.getUrl(), '/wiki/User_talk:John_Doe', 'Escaping in title and namespace for urls' );

		title = new mw.Title( 'John Cena#And_His_Name_Is', 3 );
		assert.strictEqual( title.getUrl( { meme: true } ), '/w/index.php?title=User_talk:John_Cena&meme=true#And_His_Name_Is', 'title with fragment and query parameter' );
	} );

	QUnit.test( 'newFromImg', function ( assert ) {
		var cases = [
			{
				url: '/wiki/images/9/91/Anticlockwise_heliotrope%27s.jpg',
				typeOfUrl: 'Full image',
				nameText: 'Anticlockwise heliotrope\'s',
				prefixedText: 'File:Anticlockwise heliotrope\'s.jpg'
			},

			{
				url: 'http://localhost/thumb.php?f=Stuffless_Figaro%27s.jpg&width=180',
				typeOfUrl: 'thumb.php-based thumbnail',
				nameText: 'Stuffless Figaro\'s',
				prefixedText: 'File:Stuffless Figaro\'s.jpg'
			},

			{
				url: 'foo',
				typeOfUrl: 'String with only alphabet characters'
			}

		];

		cases.forEach( function ( caseItem ) {
			var prefix,
				title = mw.Title.newFromImg( { src: caseItem.url } );

			if ( caseItem.nameText !== undefined ) {
				prefix = '[' + caseItem.typeOfUrl + ' URL] ';

				assert.notStrictEqual( title, null, prefix + 'Parses successfully' );
				assert.strictEqual( title.getNameText(), caseItem.nameText, prefix + 'Filename matches original' );
				assert.strictEqual( title.getPrefixedText(), caseItem.prefixedText, prefix + 'File page title matches original' );
				assert.strictEqual( title.getNamespaceId(), 6, prefix + 'Namespace ID matches File namespace' );
			} else {
				assert.strictEqual( title, null, caseItem.typeOfUrl + ', should not produce an mw.Title object' );
			}
		} );
	} );

	QUnit.test( 'getRelativeText', function ( assert ) {
		var cases = [
			{
				text: 'asd',
				relativeTo: 123,
				expectedResult: ':Asd'
			},
			{
				text: 'dfg',
				relativeTo: 0,
				expectedResult: 'Dfg'
			},
			{
				text: 'Template:Ghj',
				relativeTo: 0,
				expectedResult: 'Template:Ghj'
			},
			{
				text: 'Template:1',
				relativeTo: 10,
				expectedResult: '1'
			},
			{
				text: 'User:Hi',
				relativeTo: 10,
				expectedResult: 'User:Hi'
			}
		];

		cases.forEach( function ( caseItem ) {
			var title = mw.Title.newFromText( caseItem.text );
			assert.strictEqual( title.getRelativeText( caseItem.relativeTo ), caseItem.expectedResult );
		} );
	} );

	QUnit.test( 'normalizeExtension', function ( assert ) {
		var cases = [
			{
				extension: 'png',
				expected: 'png',
				description: 'Extension already in canonical form'
			},
			{
				extension: 'PNG',
				expected: 'png',
				description: 'Extension lowercased in canonical form'
			},
			{
				extension: 'jpeg',
				expected: 'jpg',
				description: 'Extension changed in canonical form'
			},
			{
				extension: 'JPEG',
				expected: 'jpg',
				description: 'Extension lowercased and changed in canonical form'
			},
			{
				extension: '~~~',
				expected: '',
				description: 'Extension invalid and discarded'
			}
		];

		cases.forEach( function ( caseItem ) {
			var extension = mw.Title.normalizeExtension( caseItem.extension ),
				prefix = '[' + caseItem.description + '] ';
			assert.strictEqual( extension, caseItem.expected, prefix + 'Extension as expected' );
		} );
	} );

	QUnit.test( 'newFromUserInput', function ( assert ) {
		var cases = [
			{
				title: 'DCS0001557854455.JPG',
				expected: 'DCS0001557854455.JPG',
				description: 'Title in normal namespace without anything invalid but with "file extension"'
			},
			{
				title: 'MediaWiki:Msg-awesome',
				expected: 'MediaWiki:Msg-awesome',
				description: 'Full title (page in MediaWiki namespace) supplied as string'
			},
			{
				title: 'The/Mw/Sound.flac',
				defaultNamespace: -2,
				expected: 'Media:The-Mw-Sound.flac',
				description: 'Page in Media-namespace without explicit options'
			},
			{
				title: 'File:The/Mw/Sound.kml',
				defaultNamespace: 6,
				options: {
					forUploading: false
				},
				expected: 'File:The/Mw/Sound.kml',
				description: 'Page in File-namespace without explicit options'
			},
			{
				title: 'File:Foo.JPEG',
				expected: 'File:Foo.JPEG',
				description: 'Page in File-namespace with non-canonical extension'
			},
			{
				title: 'File:Foo.JPEG  ',
				expected: 'File:Foo.JPEG',
				description: 'Page in File-namespace with trailing whitespace'
			},
			{
				title: 'File:Foo',
				description: 'File name without file extension'
			},
			{
				title: 'File:Foo.',
				description: 'File name with empty file extension'
			}
		];

		cases.forEach( function ( caseItem ) {
			var prefix,
				title = mw.Title.newFromUserInput( caseItem.title, caseItem.defaultNamespace, caseItem.options );

			if ( caseItem.expected !== undefined ) {
				prefix = '[' + caseItem.description + '] ';

				assert.notStrictEqual( title, null, prefix + 'Parses successfully' );
				assert.strictEqual( title.toText(), caseItem.expected, prefix + 'Title as expected' );
				if ( caseItem.defaultNamespace === undefined ) {
					title = mw.Title.newFromUserInput( caseItem.title, 0, caseItem.options );
					assert.strictEqual( title.toText(), caseItem.expected, prefix + 'Skipping namespace argument' );
				}
			} else {
				assert.strictEqual( title, null, caseItem.description + ', should not produce an mw.Title object' );
			}
		} );
	} );

	QUnit.test( 'newFromUserInput with invalid file name for upload', function ( assert ) {
		var title = mw.Title.newFromUserInput( 'File:No_dot' );
		// Invalid file name is rejected by default
		assert.strictEqual( title, null, 'file name is not accepted for upload' );
	} );

	QUnit.test( 'newFromUserInput with misplaced parameter', function ( assert ) {
		var title = mw.Title.newFromUserInput( 'File:No_dot', { forUploading: false } );
		// Misplaces options parameter (pseudo-compat with MW 1.33 and earlier),
		// behaves as if it wasn't passed - rejected the same as the default would.
		assert.strictEqual( title, null, 'misplaced options parameter is ignored' );
	} );

	QUnit.test( 'newFromUserInput with invalid file name, but not for upload', function ( assert ) {
		var title = mw.Title.newFromUserInput( 'File:No_dot', 0, { forUploading: false } );
		// Invalid file name is tolerated with this option
		assert.strictEqual( title.getPrefixedText(), 'File:No dot', 'file name is accepted' );
	} );

	QUnit.test( 'newFromFileName', function ( assert ) {
		var cases = [
			{
				fileName: 'DCS0001557854455.JPG',
				typeOfName: 'Standard camera output',
				nameText: 'DCS0001557854455',
				prefixedText: 'File:DCS0001557854455.JPG'
			},
			{
				fileName: 'Treppe 2222 Test upload.jpg',
				typeOfName: 'File name with spaces in it and lower case file extension',
				nameText: 'Treppe 2222 Test upload',
				prefixedText: 'File:Treppe 2222 Test upload.jpg'
			},
			{
				fileName: 'I contain a \ttab.jpg',
				typeOfName: 'Name containing a tab character',
				nameText: 'I contain a tab',
				prefixedText: 'File:I contain a tab.jpg'
			},
			{
				fileName: 'I_contain multiple__ ___ _underscores.jpg',
				typeOfName: 'Name containing multiple underscores',
				nameText: 'I contain multiple underscores',
				prefixedText: 'File:I contain multiple underscores.jpg'
			},
			{
				fileName: 'I like ~~~~~~~~es.jpg',
				typeOfName: 'Name containing more than three consecutive tilde characters',
				nameText: 'I like ~~es',
				prefixedText: 'File:I like ~~es.jpg'
			},
			{
				fileName: 'BI\u200EDI.jpg',
				typeOfName: 'Name containing BIDI overrides',
				nameText: 'BIDI',
				prefixedText: 'File:BIDI.jpg'
			},
			{
				fileName: '100%ab progress.jpg',
				typeOfName: 'File name with URL encoding',
				nameText: '100% ab progress',
				prefixedText: 'File:100% ab progress.jpg'
			},
			{
				fileName: '<([>])/#.jpg',
				typeOfName: 'File name with characters not permitted in titles that are replaced',
				nameText: '((()))--',
				prefixedText: 'File:((()))--.jpg'
			},
			{
				fileName: 'spaces\u0009\u2000\u200A\u200Bx.djvu',
				typeOfName: 'File name with different kind of spaces',
				nameText: 'Spaces \u200Bx',
				prefixedText: 'File:Spaces \u200Bx.djvu'
			},
			{
				fileName: 'dot.dot.dot.dot.dotdot',
				typeOfName: 'File name with a lot of dots',
				nameText: 'Dot.dot.dot.dot',
				prefixedText: 'File:Dot.dot.dot.dot.dotdot'
			},
			{
				fileName: 'dot. dot ._dot',
				typeOfName: 'File name with multiple dots and spaces',
				nameText: 'Dot. dot',
				prefixedText: 'File:Dot. dot. dot'
			},
			{
				fileName: '𠜎𠜱𠝹𠱓𠱸𠲖𠳏𠳕𠴕𠵼𠵿𠸎𠸏𠹷𠺝𠺢𠻗𠻹𠻺𠼭𠼮𠽌𠾴𠾼𠿪𡁜𡁯𡁵𡁶𡁻𡃁𡃉𡇙𢃇𢞵𢫕𢭃𢯊𢱑𢱕𢳂𠻹𠻺𠼭𠼮𠽌𠾴𠾼𠿪𡁜𡁯𡁵𡁶𡁻𡃁𡃉𡇙𢃇𢞵𢫕𢭃𢯊𢱑𢱕𢳂.png',
				typeOfName: 'File name longer than 240 bytes',
				nameText: '𠜎𠜱𠝹𠱓𠱸𠲖𠳏𠳕𠴕𠵼𠵿𠸎𠸏𠹷𠺝𠺢𠻗𠻹𠻺𠼭𠼮𠽌𠾴𠾼𠿪𡁜𡁯𡁵𡁶𡁻𡃁𡃉𡇙𢃇𢞵𢫕𢭃𢯊𢱑𢱕𢳂𠻹𠻺𠼭𠼮𠽌𠾴𠾼𠿪𡁜𡁯𡁵𡁶𡁻𡃁𡃉𡇙𢃇𢞵',
				prefixedText: 'File:𠜎𠜱𠝹𠱓𠱸𠲖𠳏𠳕𠴕𠵼𠵿𠸎𠸏𠹷𠺝𠺢𠻗𠻹𠻺𠼭𠼮𠽌𠾴𠾼𠿪𡁜𡁯𡁵𡁶𡁻𡃁𡃉𡇙𢃇𢞵𢫕𢭃𢯊𢱑𢱕𢳂𠻹𠻺𠼭𠼮𠽌𠾴𠾼𠿪𡁜𡁯𡁵𡁶𡁻𡃁𡃉𡇙𢃇𢞵.png'
			},
			{
				fileName: '',
				typeOfName: 'Empty string'
			},
			{
				fileName: 'foo',
				typeOfName: 'String with only alphabet characters'
			}
		];

		if ( mw.config.get( 'wgIllegalFileChars', '' ).indexOf( ':' ) > -1 ) {
			// ":" is automatically replaced with "-". Only test this if it is present
			// in wgIllegalFileChars. Bug: T196480
			cases.push( {
				fileName: 'File:Sample.png',
				typeOfName: 'Carrying namespace',
				nameText: 'File-Sample',
				prefixedText: 'File:File-Sample.png'
			} );
			cases.push( {
				fileName: 'Illegal:Char.png',
				typeOfName: 'File name with : character not permitted in titles that are replaced',
				nameText: 'Illegal-Char',
				prefixedText: 'File:Illegal-Char.png'
			} );
		}

		cases.forEach( function ( caseItem ) {
			var prefix,
				title = mw.Title.newFromFileName( caseItem.fileName );

			if ( caseItem.nameText !== undefined ) {
				prefix = '[' + caseItem.typeOfName + '] ';

				assert.notStrictEqual( title, null, prefix + 'Parses successfully' );
				assert.strictEqual( title.getNameText(), caseItem.nameText, prefix + 'Filename matches original' );
				assert.strictEqual( title.getPrefixedText(), caseItem.prefixedText, prefix + 'File page title matches original' );
				assert.strictEqual( title.getNamespaceId(), 6, prefix + 'Namespace ID matches File namespace' );
			} else {
				assert.strictEqual( title, null, caseItem.typeOfName + ', should not produce an mw.Title object' );
			}
		} );
	} );

}() );
