( function () {
	QUnit.module( 'mediawiki.api.messages', QUnit.newMwEnvironment( {
		setup: function () {
			this.server = this.sandbox.useFakeServer();
			this.server.respondImmediately = true;
		}
	} ) );

	QUnit.test( '.getMessages()', function ( assert ) {
		this.server.respondWith( /ammessages=foo%7Cbaz/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{ "query": { "allmessages": [' +
				'{ "name": "foo", "content": "Foo bar" },' +
				'{ "name": "baz", "content": "Baz Quux" }' +
				'] } }'
		] );

		return new mw.Api().getMessages( [ 'foo', 'baz' ] ).then( function ( messages ) {
			assert.deepEqual(
				messages,
				{
					foo: 'Foo bar',
					baz: 'Baz Quux'
				}
			);
		} );
	} );
}() );
