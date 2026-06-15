const { renderWarnings } = require( 'mediawiki.special.upload/warnings.js' );

QUnit.module( 'mediawiki.special.upload.warnings', QUnit.newMwEnvironment( {
	config: {
		wgFormattedNamespaces: { 6: 'File' },
		wgNamespaceIds: { file: 6 }
	}
} ) );

// Each entry is a warning key that action=upload may emit, paired with a
// representative value. The tests assert that renderWarnings produces a
// rendered message for every key the front-end is expected to handle, and not
// the api-error-unknown-warning fallback.
//
// 'filetype-unwanted-type' and 'large-file' are intentionally excluded because
// Special:Upload blocks submissions that would result in those warnings.
const warningCases = [
	[ 'exists', 'Foo.png' ],
	[ 'page-exists', 'Foo.png' ],
	[ 'exists-normalized', 'Foo.png' ],
	[ 'thumb', 'Foo.png' ],
	[ 'thumb-name', 'Foo.png' ],
	[ 'bad-prefix', 'Tmp_Foo.png' ],
	[ 'badfilename', 'Foo!.png' ],
	[ 'empty-file', true ],
	[ 'duplicate', [ 'A.png', 'B.png' ] ],
	[ 'duplicateversions', [
		{ timestamp: '2025-01-01T12:00:00Z' },
		{ timestamp: '2025-02-01T12:00:00Z' }
	] ],
	[ 'duplicate-archive', 'Foo.png' ],
	[ 'nochange', true ],
	[ 'was-deleted', 'Foo.png' ]
];

QUnit.test.each(
	'every reachable warning key has a mapped message',
	warningCases,
	( assert, [ key, value ] ) => {
		const list = renderWarnings( { [ key ]: value }, 'Dest.png' );

		assert.false(
			list.textContent.includes( 'api-error-unknown-warning' ),
			'key has a mapped message: ' + key
		);
	}
);

QUnit.test( 'unknown warning keys fall back to api-error-unknown-warning', ( assert ) => {
	const list = renderWarnings( { 'something-new': 'x' }, 'Dest.png' );

	assert.true(
		list.textContent.includes( 'api-error-unknown-warning' ),
		'fallback message is rendered'
	);
} );

QUnit.test( 'duplicate warning renders a sub-list of file titles', ( assert ) => {
	const list = renderWarnings( { duplicate: [ 'A.png', 'B.png' ] }, 'Dest.png' );

	const links = list.querySelectorAll( 'ul ul a' );
	assert.strictEqual( links.length, 2, 'one link per duplicate' );
	assert.strictEqual( links[ 0 ].textContent, 'File:A.png' );
	assert.strictEqual( links[ 1 ].textContent, 'File:B.png' );
} );

QUnit.test( 'renderWarnings produces a <ul> with one <li> per warning', ( assert ) => {
	const list = renderWarnings( { 'empty-file': true, badfilename: 'Foo!.png' }, 'Dest.png' );

	assert.strictEqual( list.tagName, 'UL' );
	assert.strictEqual( list.children.length, 2 );
} );
