module( 'mediawiki.Title.js' );

// mw.Title relies on these three config vars
// Restore them after each test run
var _titleConfig = function() {

	mw.config.set({
		"wgFormattedNamespaces": {
			"-2": "Media",
			"-1": "Special",
			"0": "",
			"1": "Talk",
			"2": "User",
			"3": "User talk",
			"4": "Wikipedia",
			"5": "Wikipedia talk",
			"6": "File",
			"7": "File talk",
			"8": "MediaWiki",
			"9": "MediaWiki talk",
			"10": "Template",
			"11": "Template talk",
			"12": "Help",
			"13": "Help talk",
			"14": "Category",
			"15": "Category talk",
			/* testing custom / localized */
			"100": "Penguins"
		},
		"wgNamespaceIds": {
			"media": -2,
			"special": -1,
			"": 0,
			"talk": 1,
			"user": 2,
			"user_talk": 3,
			"wikipedia": 4,
			"wikipedia_talk": 5,
			"file": 6,
			"file_talk": 7,
			"mediawiki": 8,
			"mediawiki_talk": 9,
			"template": 10,
			"template_talk": 11,
			"help": 12,
			"help_talk": 13,
			"category": 14,
			"category_talk": 15,
			"image": 6,
			"image_talk": 7,
			"project": 4,
			"project_talk": 5,
			/* testing custom / alias */
			"penguins": 100,
			"antarctic_waterfowl": 100
		},
		"wgCaseSensitiveNamespaces": []
	});
};

test( '-- Initial check', function() {
	expect(1);
	ok( mw.Title, 'mw.Title defined' );
});

test( 'Transform between Text and Db', function() {
	expect(2);
	_titleConfig();

	var title;

	title = new mw.Title( 'File:quux pif.jpg' );
	equal( title.getName(), 'Quux_pif' );

	title = new mw.Title( 'File:Glarg_foo_glang.jpg' );
	equal( title.getNameText(), 'Glarg foo glang' );
});

test( 'Main text for filename', function() {
	expect(8);
	_titleConfig();

	var title = new mw.Title( 'File:foo_bar.JPG' );

	equal( title.getNamespaceId(), 6 );
	equal( title.getNamespacePrefix(), 'File:' );
	equal( title.getName(), 'Foo_bar' );
	equal( title.getNameText(), 'Foo bar' );
	equal( title.getMain(), 'Foo_bar.jpg' );
	equal( title.getMainText(), 'Foo bar.jpg' );
	equal( title.getExtension(), 'jpg' );
	equal( title.getDotExtension(), '.jpg' );
});

test( 'Namespace detection and conversion', function() {
	expect(6);
	_titleConfig();

	var title;

	title = new mw.Title( 'something.PDF', 6 );
	equal( title.toString(), 'File:Something.pdf' );

	title = new mw.Title( 'NeilK', 3 );
	equal( title.toString(), 'User_talk:NeilK' );
	equal( title.toText(), 'User talk:NeilK' );

	title = new mw.Title( 'Frobisher', 100 );
	equal( title.toString(), 'Penguins:Frobisher' );

	title = new mw.Title( 'antarctic_waterfowl:flightless_yet_cute.jpg' );
	equal( title.toString(), 'Penguins:Flightless_yet_cute.jpg' );

	title = new mw.Title( 'Penguins:flightless_yet_cute.jpg' );
	equal( title.toString(), 'Penguins:Flightless_yet_cute.jpg' );
});

test( 'Throw error on invalid title', function() {
	expect(1);
	_titleConfig();

	raises(function() {
	
		var title = new mw.Title( '' );
	
	}, new RegExp( $.escapeRE( 'Could not parse title' ) ), 'Throw error "Could not parse title" on empty string' );
});

test( 'Case-sensivity', function() {
	expect(3);
	_titleConfig();

	var title;

	// Default config
	mw.config.set( 'wgCaseSensitiveNamespaces', [] );

	title = new mw.Title( 'article' );
	equal( title.toString(), 'Article', 'Default config: No sensitive namespaces by default. First-letter becomes uppercase' );

	// $wgCapitalLinks = false;
	mw.config.set( 'wgCaseSensitiveNamespaces', [0, -2, 1, 4, 5, 6, 7, 10, 11, 12, 13, 14, 15] );

	title = new mw.Title( 'article' );
	equal( title.toString(), 'article', '$wgCapitalLinks=false: Article namespace is sensitive, first-letter case stays lowercase' );

	title = new mw.Title( 'john', 2 );
	equal( title.toString(), 'User:John', '$wgCapitalLinks=false: User namespace is insensitive, first-letter becomes uppercase' );
});

test( 'toString / toText', function() {
	expect(2);
	_titleConfig();

	var title = new mw.Title( 'Some random page' );

	equal( title.toString(), title.getPrefixedDb() );
	equal( title.toText(), title.getPrefixedText() );
});

test( 'Exists', function() {
	expect(3);
	_titleConfig();

	var title;

	// Empty registry, checks default to null

	title = new mw.Title( 'Some random page', 4 );
	strictEqual( title.exists(), null, 'Return null with empty existance registry' );

	// Basic registry, checks default to boolean
	mw.Title.exist.set( ['Does_exist', 'User_talk:NeilK', 'Wikipedia:Sandbox_rules'], true );
	mw.Title.exist.set( ['Does_not_exist', 'User:John', 'Foobar'], false );

	title = new mw.Title( 'Project:Sandbox rules' );
	assertTrue( title.exists(), 'Return true for page titles marked as existing' );
	title = new mw.Title( 'Foobar' );
	assertFalse( title.exists(), 'Return false for page titles marked as inexisting' );

});

test( 'Url', function() {
	expect(2);
	_titleConfig();

	var title;

	// Config
	mw.config.set( 'wgArticlePath', '/wiki/$1' );

	title = new mw.Title( 'Foobar' );
	equal( title.getUrl(), '/wiki/Foobar', 'Basic functionally, toString passing to wikiGetlink' );

	title = new mw.Title( 'John Doe', 3 );
	equal( title.getUrl(), '/wiki/User_talk:John_Doe', 'Escaping in title and namespace for urls' );
});