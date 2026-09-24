QUnit.module( 'mediawiki.page.ready: share', QUnit.newMwEnvironment(), ( hooks ) => {
	const share = require( 'mediawiki.page.ready/share.js' );
	const url = 'https://example.org/wiki/Example';
	let copied;

	// Own properties shadow the browser's, which not every test browser has.
	function setNavigator( props ) {
		for ( const [ key, value ] of Object.entries( props ) ) {
			Object.defineProperty( navigator, key, { value, configurable: true } );
		}
	}

	hooks.beforeEach( () => {
		copied = [];
		setNavigator( {
			canShare: undefined,
			share: undefined,
			clipboard: {
				writeText: ( text ) => {
					copied.push( text );
					return Promise.resolve();
				}
			}
		} );
		sinon.stub( mw, 'notify' );
	} );

	hooks.afterEach( () => {
		delete navigator.canShare;
		delete navigator.share;
		delete navigator.clipboard;
	} );

	QUnit.test( 'URL without Web Share support', async ( assert ) => {
		assert.false( await share( { url } ), 'resolves false' );
		assert.deepEqual( copied, [ url ], 'copies the URL' );
		assert.true( mw.notify.calledOnce, 'notifies' );
	} );

	QUnit.test( 'URL with Web Share support', async ( assert ) => {
		const shareSpy = sinon.spy( () => Promise.resolve() );
		setNavigator( { canShare: () => true, share: shareSpy } );

		assert.true( await share( { url } ), 'resolves true' );
		assert.deepEqual( shareSpy.firstCall.args, [ { url } ], 'shares the URL' );
		assert.deepEqual( copied, [ url ], 'still copies the URL' );
	} );

	QUnit.test( 'Files without a URL', async ( assert ) => {
		const shareData = { files: [ new File( [ '' ], 'card.png', { type: 'image/png' } ) ], text: 'Example' };
		const shareSpy = sinon.spy( () => Promise.resolve() );
		setNavigator( { canShare: () => true, share: shareSpy } );

		assert.true( await share( shareData ), 'resolves true' );
		assert.strictEqual( shareSpy.firstCall.args[ 0 ], shareData, 'shares the files' );
		assert.deepEqual( copied, [], 'copies nothing' );
		assert.true( mw.notify.notCalled, 'does not notify' );
	} );

	QUnit.test( 'Data the browser cannot share', async ( assert ) => {
		const shareSpy = sinon.spy( () => Promise.resolve() );
		setNavigator( { canShare: () => false, share: shareSpy } );

		assert.false( await share( { files: [] } ), 'resolves false' );
		assert.true( shareSpy.notCalled, 'does not call navigator.share' );
	} );

	QUnit.test( 'Cancelled share', async ( assert ) => {
		setNavigator( {
			canShare: () => true,
			share: () => Promise.reject( new DOMException( 'Share canceled', 'AbortError' ) )
		} );

		assert.false( await share( { url } ), 'resolves false' );
	} );

	QUnit.test( 'Other share errors', async ( assert ) => {
		setNavigator( {
			canShare: () => true,
			share: () => Promise.reject( new DOMException( 'No user activation', 'NotAllowedError' ) )
		} );

		await assert.rejects( share( { url } ), /No user activation/, 'rejects' );
	} );
} );
