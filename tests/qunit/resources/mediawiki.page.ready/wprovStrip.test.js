QUnit.module( 'mediawiki.page.ready: wprovStrip', QUnit.newMwEnvironment(), ( hooks ) => {
	const { stripWprov } = require( 'mediawiki.page.ready/wprovStrip.js' );
	let loc = null;

	hooks.beforeEach( () => {
		loc = null;
		sinon.replace( window.history, 'replaceState', ( state, unused, url ) => {
			loc = url;
		} );
		mw.config.set( {
			wgArticlePath: '/wiki/$1',
			wgScript: '/w/index.php'
		} );
	} );

	QUnit.test.each( 'stripWprov()', {
		Canonical: [ 'https://example/wiki/Banana', null, null ],
		'Index view': [ 'https://example/w/index.php?title=Banana', null, null ],
		'API help': [ 'https://en.wikipedia.org/w/api.php?action=help&modules=edit', null, null ],
		'Index edit': [ 'https://example/w/index.php?title=Banana&action=edit', null, null ],
		'Canonical + wprov': [
			'https://example/wiki/Banana?wprov=test1',
			'https://example/wiki/Banana',
			'test1'
		],
		'Canonical + wprov + other': [
			'https://example/wiki/Banana?veaction=edit&wprov=test1',
			'https://example/wiki/Banana?veaction=edit',
			'test1'
		],
		'Index view + wprov last': [
			'https://example/w/index.php?title=Banana&wprov=test1',
			'https://example/wiki/Banana',
			'test1'
		],
		'Index view + wprov first': [
			'https://example/w/index.php?wprov=test1&title=Banana',
			'https://example/wiki/Banana',
			'test1'
		],
		'API help + wprov': [
			'https://en.wikipedia.org/w/api.php?action=help&wprov=test1&modules=edit',
			'https://en.wikipedia.org/w/api.php?action=help&modules=edit',
			'test1'
		],
		'Index edit + wprov': [
			'https://example/w/index.php?title=Banana&wprov=test1&action=edit',
			'https://example/w/index.php?title=Banana&action=edit',
			'test1'
		]
	}, ( assert, [ input, expectedLoc, expectedRet ] ) => {
		assert.strictEqual( stripWprov( input ), expectedRet, 'return' );
		assert.strictEqual( loc, expectedLoc, 'location' );
	} );
} );
