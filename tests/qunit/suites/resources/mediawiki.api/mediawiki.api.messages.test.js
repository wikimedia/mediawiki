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

	// Make sure we properly handle a string and don't try to slice it in the handling for
	// multiple messages. The alphabet has 26 letters, so twice that is 52, if we don't
	// switch the string to an array of one the slice() call would remove the last two letters
	var longStringKey = 'abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyz';

	QUnit.test( '.getMessages() with a long string', function ( assert ) {
		var requestRegex = new RegExp( 'ammessages=' + longStringKey );
		this.server.respondWith( requestRegex, [
			200,
			{ 'Content-Type': 'application/json' },
			'{ "query": { "allmessages": [' +
				'{ "name": "' + longStringKey + '", "content": "SomeRandomValue" }' +
				'] } }'
		] );

		return new mw.Api().getMessages( longStringKey ).then( function ( messages ) {
			var expected = {};
			expected[ longStringKey ] = 'SomeRandomValue';
			assert.deepEqual( messages, expected );
		} );
	} );
}() );
