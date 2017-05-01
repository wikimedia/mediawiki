/*jshint -W024 */
( function ( mw, $ ) {
	var repeat = function ( input, multiplier ) {
		return new Array( multiplier + 1 ).join( input );
	},
	cases = {
		// See also TitleTest.php#testSecureAndSplit
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
			// jscs: disable requireCamelCaseOrUpperCaseIdentifiers
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
			// jscs: enable requireCamelCaseOrUpperCaseIdentifiers
			wgCaseSensitiveNamespaces: []
		}
	} ) );

	QUnit.test( 'constructor', cases.invalid.length, function ( assert ) {
		var i, title;
		for ( i = 0; i < cases.valid.length; i++ ) {
			title = new mw.Title( cases.valid[ i ] );
		}
		for ( i = 0; i < cases.invalid.length; i++ ) {
			/*jshint loopfunc:true */
			title = cases.invalid[ i ];
			assert.throws( function () {
				return new mw.Title( title );
			}, cases.invalid[ i ] );
		}
	} );

	QUnit.test( 'newFromText', cases.valid.length + cases.invalid.length, function ( assert ) {
		var i;
		for ( i = 0; i < cases.valid.length; i++ ) {
			assert.equal(
				$.type( mw.Title.newFromText( cases.valid[ i ] ) ),
				'object',
				cases.valid[ i ]
			);
		}
		for ( i = 0; i < cases.invalid.length; i++ ) {
			assert.equal(
				$.type( mw.Title.newFromText( cases.invalid[ i ] ) ),
				'null',
				cases.invalid[ i ]
			);
		}
	} );

	QUnit.test( 'makeTitle', 6, function ( assert ) {
		var cases, i, title, expected,
			NS_MAIN = 0,
			NS_TALK = 1,
			NS_TEMPLATE = 10;

		cases = [
			[ NS_TEMPLATE, 'Foo', 'Template:Foo' ],
			[ NS_TEMPLATE, 'Category:Foo', 'Template:Category:Foo' ],
			[ NS_TEMPLATE, 'Template:Foo', 'Template:Template:Foo' ],
			[ NS_TALK, 'Help:Foo', null ],
			[ NS_TEMPLATE, '<', null ],
			[ NS_MAIN, 'Help:Foo', 'Help:Foo' ]
		];

		for ( i = 0; i < cases.length; i++ ) {
			title = mw.Title.makeTitle( cases[ i ][ 0 ], cases[ i ][ 1 ] );
			expected = cases[ i ][ 2 ];
			if ( expected === null ) {
				assert.strictEqual( title, expected );
			} else {
				assert.strictEqual( title.getPrefixedText(), expected );
			}
		}
	} );

	QUnit.test( 'Basic parsing', 21, function ( assert ) {
		var title;
		title = new mw.Title( 'File:Foo_bar.JPG' );

		assert.equal( title.getNamespaceId(), 6 );
		assert.equal( title.getNamespacePrefix(), 'File:' );
		assert.equal( title.getName(), 'Foo_bar' );
		assert.equal( title.getNameText(), 'Foo bar' );
		assert.equal( title.getExtension(), 'JPG' );
		assert.equal( title.getDotExtension(), '.JPG' );
		assert.equal( title.getMain(), 'Foo_bar.JPG' );
		assert.equal( title.getMainText(), 'Foo bar.JPG' );
		assert.equal( title.getPrefixedDb(), 'File:Foo_bar.JPG' );
		assert.equal( title.getPrefixedText(), 'File:Foo bar.JPG' );

		title = new mw.Title( 'Foo#bar' );
		assert.equal( title.getPrefixedText(), 'Foo' );
		assert.equal( title.getFragment(), 'bar' );

		title = new mw.Title( '.foo' );
		assert.equal( title.getPrefixedText(), '.foo' );
		assert.equal( title.getName(), '' );
		assert.equal( title.getNameText(), '' );
		assert.equal( title.getExtension(), 'foo' );
		assert.equal( title.getDotExtension(), '.foo' );
		assert.equal( title.getMain(), '.foo' );
		assert.equal( title.getMainText(), '.foo' );
		assert.equal( title.getPrefixedDb(), '.foo' );
		assert.equal( title.getPrefixedText(), '.foo' );
	} );

	QUnit.test( 'Transformation', 12, function ( assert ) {
		var title;

		title = new mw.Title( 'File:quux pif.jpg' );
		assert.equal( title.getNameText(), 'Quux pif', 'First character of title' );

		title = new mw.Title( 'File:Glarg_foo_glang.jpg' );
		assert.equal( title.getNameText(), 'Glarg foo glang', 'Underscores' );

		title = new mw.Title( 'User:ABC.DEF' );
		assert.equal( title.toText(), 'User:ABC.DEF', 'Round trip text' );
		assert.equal( title.getNamespaceId(), 2, 'Parse canonical namespace prefix' );

		title = new mw.Title( 'Image:quux pix.jpg' );
		assert.equal( title.getNamespacePrefix(), 'File:', 'Transform alias to canonical namespace' );

		title = new mw.Title( 'uSEr:hAshAr' );
		assert.equal( title.toText(), 'User:HAshAr' );
		assert.equal( title.getNamespaceId(), 2, 'Case-insensitive namespace prefix' );

		title = new mw.Title( 'Foo \u00A0\u1680\u180E\u2000\u2001\u2002\u2003\u2004\u2005\u2006\u2007\u2008\u2009\u200A\u2028\u2029\u202F\u205F\u3000 bar' );
		assert.equal( title.getMain(), 'Foo_bar', 'Merge multiple types of whitespace/underscores into a single underscore' );

		title = new mw.Title( 'Foo\u200E\u200F\u202A\u202B\u202C\u202D\u202Ebar' );
		assert.equal( title.getMain(), 'Foobar', 'Strip Unicode bidi override characters' );

		// Regression test: Previously it would only detect an extension if there is no space after it
		title = new mw.Title( 'Example.js  ' );
		assert.equal( title.getExtension(), 'js', 'Space after an extension is stripped' );

		title = new mw.Title( 'Example#foo' );
		assert.equal( title.getFragment(), 'foo', 'Fragment' );

		title = new mw.Title( 'Example#_foo_bar baz_' );
		assert.equal( title.getFragment(), ' foo bar baz', 'Fragment' );
	} );

	QUnit.test( 'Namespace detection and conversion', 10, function ( assert ) {
		var title;

		title = new mw.Title( 'File:User:Example' );
		assert.equal( title.getNamespaceId(), 6, 'Titles can contain namespace prefixes, which are otherwise ignored' );

		title = new mw.Title( 'Example', 6 );
		assert.equal( title.getNamespaceId(), 6, 'Default namespace passed is used' );

		title = new mw.Title( 'User:Example', 6 );
		assert.equal( title.getNamespaceId(), 2, 'Included namespace prefix overrides the given default' );

		title = new mw.Title( ':Example', 6 );
		assert.equal( title.getNamespaceId(), 0, 'Colon forces main namespace' );

		title = new mw.Title( 'something.PDF', 6 );
		assert.equal( title.toString(), 'File:Something.PDF' );

		title = new mw.Title( 'NeilK', 3 );
		assert.equal( title.toString(), 'User_talk:NeilK' );
		assert.equal( title.toText(), 'User talk:NeilK' );

		title = new mw.Title( 'Frobisher', 100 );
		assert.equal( title.toString(), 'Penguins:Frobisher' );

		title = new mw.Title( 'antarctic_waterfowl:flightless_yet_cute.jpg' );
		assert.equal( title.toString(), 'Penguins:Flightless_yet_cute.jpg' );

		title = new mw.Title( 'Penguins:flightless_yet_cute.jpg' );
		assert.equal( title.toString(), 'Penguins:Flightless_yet_cute.jpg' );
	} );

	QUnit.test( 'Throw error on invalid title', 1, function ( assert ) {
		assert.throws( function () {
			return new mw.Title( '' );
		}, 'Throw error on empty string' );
	} );

	QUnit.test( 'Case-sensivity', 3, function ( assert ) {
		var title;

		// Default config
		mw.config.set( 'wgCaseSensitiveNamespaces', [] );

		title = new mw.Title( 'article' );
		assert.equal( title.toString(), 'Article', 'Default config: No sensitive namespaces by default. First-letter becomes uppercase' );

		// $wgCapitalLinks = false;
		mw.config.set( 'wgCaseSensitiveNamespaces', [ 0, -2, 1, 4, 5, 6, 7, 10, 11, 12, 13, 14, 15 ] );

		title = new mw.Title( 'article' );
		assert.equal( title.toString(), 'article', '$wgCapitalLinks=false: Article namespace is sensitive, first-letter case stays lowercase' );

		title = new mw.Title( 'john', 2 );
		assert.equal( title.toString(), 'User:John', '$wgCapitalLinks=false: User namespace is insensitive, first-letter becomes uppercase' );
	} );

	QUnit.test( 'toString / toText', 2, function ( assert ) {
		var title = new mw.Title( 'Some random page' );

		assert.equal( title.toString(), title.getPrefixedDb() );
		assert.equal( title.toText(), title.getPrefixedText() );
	} );

	QUnit.test( 'getExtension', 7, function ( assert ) {
		function extTest( pagename, ext, description ) {
			var title = new mw.Title( pagename );
			assert.equal( title.getExtension(), ext, description || pagename );
		}

		extTest( 'MediaWiki:Vector.js', 'js' );
		extTest( 'User:Example/common.css', 'css' );
		extTest( 'File:Example.longextension', 'longextension', 'Extension parsing not limited (bug 36151)' );
		extTest( 'Example/information.json', 'json', 'Extension parsing not restricted from any namespace' );
		extTest( 'Foo.', null, 'Trailing dot is not an extension' );
		extTest( 'Foo..', null, 'Trailing dots are not an extension' );
		extTest( 'Foo.a.', null, 'Page name with dots and ending in a dot does not have an extension' );

		// @broken: Throws an exception
		// extTest( '.NET', null, 'Leading dot is (or is not?) an extension' );
	} );

	QUnit.test( 'exists', 3, function ( assert ) {
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

	QUnit.test( 'getUrl', 4, function ( assert ) {
		var title;
		mw.config.set( {
			wgScript: '/w/index.php',
			wgArticlePath: '/wiki/$1'
		} );

		title = new mw.Title( 'Foobar' );
		assert.equal( title.getUrl(), '/wiki/Foobar', 'Basic functionality, getUrl uses mw.util.getUrl' );
		assert.equal( title.getUrl( { action: 'edit' } ), '/w/index.php?title=Foobar&action=edit', 'Basic functionality, \'params\' parameter' );

		title = new mw.Title( 'John Doe', 3 );
		assert.equal( title.getUrl(), '/wiki/User_talk:John_Doe', 'Escaping in title and namespace for urls' );

		title = new mw.Title( 'John Cena#And_His_Name_Is', 3 );
		assert.equal( title.getUrl( { meme: true } ), '/w/index.php?title=User_talk:John_Cena&meme=true#And_His_Name_Is', 'title with fragment and query parameter' );
	} );

	QUnit.test( 'newFromImg', 44, function ( assert ) {
		var title, i, thisCase, prefix,
			cases = [
				{
					url: '//upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Princess_Alexandra_of_Denmark_%28later_Queen_Alexandra%2C_wife_of_Edward_VII%29_with_her_two_eldest_sons%2C_Prince_Albert_Victor_%28Eddy%29_and_George_Frederick_Ernest_Albert_%28later_George_V%29.jpg/939px-thumbnail.jpg',
					typeOfUrl: 'Hashed thumb with shortened path',
					nameText: 'Princess Alexandra of Denmark (later Queen Alexandra, wife of Edward VII) with her two eldest sons, Prince Albert Victor (Eddy) and George Frederick Ernest Albert (later George V)',
					prefixedText: 'File:Princess Alexandra of Denmark (later Queen Alexandra, wife of Edward VII) with her two eldest sons, Prince Albert Victor (Eddy) and George Frederick Ernest Albert (later George V).jpg'
				},

				{
					url: '//upload.wikimedia.org/wikipedia/commons/thumb/b/bf/Princess_Alexandra_of_Denmark_%28later_Queen_Alexandra%2C_wife_of_Edward_VII%29_with_her_two_eldest_sons%2C_Prince_Albert_Victor_%28Eddy%29_and_George_Frederick_Ernest_Albert_%28later_George_V%29.jpg/939px-ki708pr1r6g2dl5lbhvwdqxenhait13.jpg',
					typeOfUrl: 'Hashed thumb with sha1-ed path',
					nameText: 'Princess Alexandra of Denmark (later Queen Alexandra, wife of Edward VII) with her two eldest sons, Prince Albert Victor (Eddy) and George Frederick Ernest Albert (later George V)',
					prefixedText: 'File:Princess Alexandra of Denmark (later Queen Alexandra, wife of Edward VII) with her two eldest sons, Prince Albert Victor (Eddy) and George Frederick Ernest Albert (later George V).jpg'
				},

				{
					url: '/wiki/images/thumb/9/91/Anticlockwise_heliotrope%27s.jpg/99px-Anticlockwise_heliotrope%27s.jpg',
					typeOfUrl: 'Normal hashed directory thumbnail',
					nameText: 'Anticlockwise heliotrope\'s',
					prefixedText: 'File:Anticlockwise heliotrope\'s.jpg'
				},

				{
					url: '/wiki/images/thumb/8/80/Wikipedia-logo-v2.svg/langde-150px-Wikipedia-logo-v2.svg.png',
					typeOfUrl: 'Normal hashed directory thumbnail with complex thumbnail parameters',
					nameText: 'Wikipedia-logo-v2',
					prefixedText: 'File:Wikipedia-logo-v2.svg'
				},

				{
					url: '//upload.wikimedia.org/wikipedia/commons/thumb/8/80/Wikipedia-logo-v2.svg/150px-Wikipedia-logo-v2.svg.png',
					typeOfUrl: 'Commons thumbnail',
					nameText: 'Wikipedia-logo-v2',
					prefixedText: 'File:Wikipedia-logo-v2.svg'
				},

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
					url: '/wikipedia/commons/thumb/Wikipedia-logo-v2.svg/150px-Wikipedia-logo-v2.svg.png',
					typeOfUrl: 'Commons unhashed thumbnail',
					nameText: 'Wikipedia-logo-v2',
					prefixedText: 'File:Wikipedia-logo-v2.svg'
				},

				{
					url: '/wikipedia/commons/thumb/Wikipedia-logo-v2.svg/langde-150px-Wikipedia-logo-v2.svg.png',
					typeOfUrl: 'Commons unhashed thumbnail with complex thumbnail parameters',
					nameText: 'Wikipedia-logo-v2',
					prefixedText: 'File:Wikipedia-logo-v2.svg'
				},

				{
					url: '/wiki/images/Anticlockwise_heliotrope%27s.jpg',
					typeOfUrl: 'Unhashed local file',
					nameText: 'Anticlockwise heliotrope\'s',
					prefixedText: 'File:Anticlockwise heliotrope\'s.jpg'
				},

				{
					url: '',
					typeOfUrl: 'Empty string'
				},

				{
					url: 'foo',
					typeOfUrl: 'String with only alphabet characters'
				},

				{
					url: 'foobar.foobar',
					typeOfUrl: 'Not a file path'
				},

				{
					url: '/a/a0/blah blah blah',
					typeOfUrl: 'Space characters'
				}
			];

		for ( i = 0; i < cases.length; i++ ) {
			thisCase = cases[ i ];
			title = mw.Title.newFromImg( { src: thisCase.url } );

			if ( thisCase.nameText !== undefined ) {
				prefix = '[' + thisCase.typeOfUrl + ' URL' + '] ';

				assert.notStrictEqual( title, null, prefix + 'Parses successfully' );
				assert.equal( title.getNameText(), thisCase.nameText, prefix + 'Filename matches original' );
				assert.equal( title.getPrefixedText(), thisCase.prefixedText, prefix + 'File page title matches original' );
				assert.equal( title.getNamespaceId(), 6, prefix + 'Namespace ID matches File namespace' );
			} else {
				assert.strictEqual( title, null, thisCase.typeOfUrl + ', should not produce an mw.Title object' );
			}
		}
	} );

	QUnit.test( 'getRelativeText', 5, function ( assert ) {
		var i, thisCase, title,
			cases = [
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

		for ( i = 0; i < cases.length; i++ ) {
			thisCase = cases[ i ];

			title = mw.Title.newFromText( thisCase.text );
			assert.equal( title.getRelativeText( thisCase.relativeTo ), thisCase.expectedResult );
		}
	} );

	QUnit.test( 'normalizeExtension', 5, function ( assert ) {
		var extension, i, thisCase, prefix,
			cases = [
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

		for ( i = 0; i < cases.length; i++ ) {
			thisCase = cases[ i ];
			extension = mw.Title.normalizeExtension( thisCase.extension );

			prefix = '[' + thisCase.description + '] ';
			assert.equal( extension, thisCase.expected, prefix + 'Extension as expected' );
		}
	} );

	QUnit.test( 'newFromUserInput', 12, function ( assert ) {
		var title, i, thisCase, prefix,
			cases = [
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
				}
			];

		for ( i = 0; i < cases.length; i++ ) {
			thisCase = cases[ i ];
			title = mw.Title.newFromUserInput( thisCase.title, thisCase.defaultNamespace, thisCase.options );

			if ( thisCase.expected !== undefined ) {
				prefix = '[' + thisCase.description + '] ';

				assert.notStrictEqual( title, null, prefix + 'Parses successfully' );
				assert.equal( title.toText(), thisCase.expected, prefix + 'Title as expected' );
			} else {
				assert.strictEqual( title, null, thisCase.description + ', should not produce an mw.Title object' );
			}
		}
	} );

	QUnit.test( 'newFromFileName', 54, function ( assert ) {
		var title, i, thisCase, prefix,
			cases = [
				{
					fileName: 'DCS0001557854455.JPG',
					typeOfName: 'Standard camera output',
					nameText: 'DCS0001557854455',
					prefixedText: 'File:DCS0001557854455.JPG'
				},
				{
					fileName: 'File:Sample.png',
					typeOfName: 'Carrying namespace',
					nameText: 'File-Sample',
					prefixedText: 'File:File-Sample.png'
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
					fileName: '<([>]):/#.jpg',
					typeOfName: 'File name with characters not permitted in titles that are replaced',
					nameText: '((()))---',
					prefixedText: 'File:((()))---.jpg'
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

		for ( i = 0; i < cases.length; i++ ) {
			thisCase = cases[ i ];
			title = mw.Title.newFromFileName( thisCase.fileName );

			if ( thisCase.nameText !== undefined ) {
				prefix = '[' + thisCase.typeOfName + '] ';

				assert.notStrictEqual( title, null, prefix + 'Parses successfully' );
				assert.equal( title.getNameText(), thisCase.nameText, prefix + 'Filename matches original' );
				assert.equal( title.getPrefixedText(), thisCase.prefixedText, prefix + 'File page title matches original' );
				assert.equal( title.getNamespaceId(), 6, prefix + 'Namespace ID matches File namespace' );
			} else {
				assert.strictEqual( title, null, thisCase.typeOfName + ', should not produce an mw.Title object' );
			}
		}
	} );

}( mediaWiki, jQuery ) );
