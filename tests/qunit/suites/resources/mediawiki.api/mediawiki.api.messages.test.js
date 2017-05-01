( function ( mw ) {
	QUnit.module( 'mediawiki.api.messages', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
		}
	} ) );

	QUnit.test( '.getMessages()', function ( assert ) {
		QUnit.expect( 1 );

		var api = new mw.Api();
		api.getMessages( [ 'foo', 'baz' ] ).then( function ( messages ) {
			assert.deepEqual(
				messages,
				{
					foo: 'Foo bar',
					baz: 'Baz Quux'
				}
			);
		} );

		this.server.respond( /ammessages=foo%7Cbaz/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{ "query": { "allmessages": [' +
				'{ "name": "foo", "content": "Foo bar" },' +
				'{ "name": "baz", "content": "Baz Quux" }' +
				'] } }'
		] );
	} );
}( mediaWiki ) );
