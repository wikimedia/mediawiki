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

test( 'Filename', function() {
	expect(4);
	_titleConfig();

	var title = new mw.Title( 'File:foo_bar.JPG' );

	equal( title.getMain(), 'Foo_bar.jpg' );
	equal( title.getMainText(), 'Foo bar.jpg' );
	equal( title.getNameText(), 'Foo bar' );
	equal( title.toString(), 'File:Foo_bar.jpg' );
});

test( 'Transform between Text to Db', function() {
	expect(6);
	_titleConfig();

	var title = new mw.Title( 'File:foo_bar.JPG' );
	title.setName( 'quux pif' );

	equal( title.getMain(), 'Quux_pif.jpg' );
	equal( title.getMainText(), 'Quux pif.jpg' );
	equal( title.getNameText(), 'Quux pif' );
	equal( title.toString(), 'File:Quux_pif.jpg' );

	title.setName( 'glarg_foo_glang' );

	equal( title.toString(), 'File:Glarg_foo_glang.jpg' );
	equal( title.getMainText(), 'Glarg foo glang.jpg' );
});

test( 'Initiate from name and set namespace', function() {
	expect(1);
	_titleConfig();

	var title = new mw.Title( 'catalonian_penguins.PNG' );
	title.setNamespace( 'file' );
	equal( title.toString(), 'File:Catalonian_penguins.png' );
});

test( 'Namespace detection and conversion', function() {
	expect(7);
	_titleConfig();

	var title;

	title = new mw.Title( 'something.PDF' );
	title.setNamespace( 'file' );
	equal( title.toString(), 'File:Something.pdf' );

	title = new mw.Title( 'NeilK' );
	title.setNamespace( 'user_talk' );
	equal( title.toString(), 'User_talk:NeilK' );
	equal( title.toText(), 'User talk:NeilK' );

	title = new mw.Title( 'Frobisher' );
	title.setNamespaceById( 100 );
	equal( title.toString(), 'Penguins:Frobisher' );

	title = new mw.Title( 'flightless_yet_cute.jpg' );
	title.setNamespace( 'antarctic_waterfowl' );
	equal( title.toString(), 'Penguins:Flightless_yet_cute.jpg' );

	title = new mw.Title( 'flightless_yet_cute.jpg' );
	title.setNamespace( 'Penguins' );
	equal( title.toString(), 'Penguins:Flightless_yet_cute.jpg' );

	title = new mw.Title( 'flightless_yet_cute.jpg' );
	raises( function() {
		title.setNamespace( 'Entirely Unknown' );
	});
});
