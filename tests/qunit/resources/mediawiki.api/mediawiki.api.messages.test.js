QUnit.module( 'mediawiki.api.messages', ( hooks ) => {
	let server;
	hooks.beforeEach( function () {
		server = this.sandbox.useFakeServer();
		server.respondImmediately = true;
	} );

	QUnit.test( '.getMessages()', async ( assert ) => {
		server.respondWith( /ammessages=foo%7Cbaz/, [
			200,
			{ 'Content-Type': 'application/json' },
			'{ "query": { "allmessages": [' +
				'{ "name": "foo", "content": "Foo bar" },' +
				'{ "name": "baz", "content": "Baz Quux" }' +
				'] } }'
		] );

		assert.deepEqual(
			await new mw.Api().getMessages( [ 'foo', 'baz' ] ),
			{
				foo: 'Foo bar',
				baz: 'Baz Quux'
			}
		);
	} );

	// Make sure we properly handle a string and don't try to slice it in the handling for
	// multiple messages. The alphabet has 26 letters, so twice that is 52, if we don't
	// switch the string to an array of one the slice() call would remove the last two letters
	const LONG_KEY = 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';

	QUnit.test( '.getMessages() with a long string', async ( assert ) => {
		server.respondWith( new RegExp( 'ammessages=' + LONG_KEY ), [
			200,
			{ 'Content-Type': 'application/json' },
			'{ "query": { "allmessages": [' +
				'{ "name": "' + LONG_KEY + '", "content": "SomeRandomValue" }' +
				'] } }'
		] );

		assert.deepEqual(
			await new mw.Api().getMessages( LONG_KEY ),
			{
				[ LONG_KEY ]: 'SomeRandomValue'
			}
		);
	} );
} );
