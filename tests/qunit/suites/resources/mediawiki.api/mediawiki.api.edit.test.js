( function ( mw ) {
	QUnit.module( 'mediawiki.api.edit', QUnit.newMwEnvironment() );

	QUnit.asyncTest( 'postWithEditToken', 5, function ( assert ) {
		var api = new mw.Api();

		function testNoThrowOmittedCallbacks() {
			QUnit.stop();
			api.postWithEditToken( {
				action: 'upload',
				checkstatus: true,
				filekey: 'somefile1234.jpg'
			} ).fail( function ( code ) {
				QUnit.start();
				// The api call is expected to fail due to the invalid file key.  However,
				// the omitted optional callbacks should not cause it to throw.
				assert.strictEqual( code, 'missingresult', 'Returns correct code, and does not throw, when ok and err are omitted and token was previously cached' );
			} );
		}

		function testErrParameter() {
			QUnit.stop();
			api.postWithEditToken( {
				action: 'edit',
				title: 'Talk:Lorem_ipsum',
				sectiontitle: 'New section'
			}, null, function ( code ) {
				QUnit.start();
				assert.strictEqual( code, 'notext', 'err parameter is called for succesful edit with token previously in cache' );
				testNoThrowOmittedCallbacks();
			} );
		}

		// This makes an actual edit, adding a section to talk.
		function testOkAndDone() {
			QUnit.stop();
			api.postWithEditToken( {
				action: 'edit',
				title: 'Talk:Lorem_ipsum',
				section: 'new',
				sectiontitle: 'New section',
				text: 'Section text'
			}, function ( response ) {
				QUnit.start();
				assert.strictEqual( response.edit.result, 'Success', 'ok parameter is called for succesful edit with token previously in cache' );
			} ).done( function ( response ) {
				assert.strictEqual( response.edit.result, 'Success', 'done called after ok for succesful edit with token previously in cache' );
				testErrParameter();
			} );
		}

		// This accomplishes two things.  It first tests that fail is called when
		// the token is fetched, but the main POST fails.  In the process,
		// this causes mediawiki.api.edit to cache a token (which is needed
		// to test the testNoThrowOmittedCallbacks path).
		api.postWithEditToken( {
			action: 'edit',
			title: 'Lorem ipsum',
			summary: 'Make a change'
		} ).fail( function ( code ) {
			QUnit.start();
			assert.strictEqual( code, 'notext', 'Calls fail (with correct code) when token is fetched, but main request fails' );
			testOkAndDone();
		} );
} );
}( mediaWiki ) );
