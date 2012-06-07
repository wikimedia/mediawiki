QUnit.module( 'mediawiki.api.parse', QUnit.newMwEnvironment() );

QUnit.asyncTest( 'Simple', function ( assert ) {
	var api;
	QUnit.expect( 1 );

	api = new mw.Api();

	api.parse( "'''Hello world'''" )
		.done( function ( html ) {
			// Html also contains "NewPP report", so only check the first part
			assert.equal( html.substr( 0, 26 ), '<p><b>Hello world</b>\n</p>',
				'Wikitext to html parsing works.'
			);

			QUnit.start();
		});
});
