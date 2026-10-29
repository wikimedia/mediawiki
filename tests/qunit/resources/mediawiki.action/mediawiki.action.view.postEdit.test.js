const postEdit = require( 'mediawiki.action.view.postEdit' );

// The mediawiki.action.view.postEdit module defines the storage key at the module level
const STORAGE_KEY = 'mw-PostEditSpecial:Badtitle/JavaScriptTest';

QUnit.module( 'mediawiki.action.view.postEdit#fireHook', QUnit.newMwEnvironment( {
	config: {
		wgRevisionId: 1,
		wgCurRevisionId: 2
	},
	beforeEach() {
		const handler = this.handler = this.sandbox.spy();

		this.sandbox.stub( mw, 'hook' ).returns( {
			fire() {
				handler.apply( handler, arguments );
			}
		} );
	}
} ) );

QUnit.test( 'it handles no parameters', function ( assert ) {
	postEdit.fireHook();

	const expectedMessage = mw.message(
		'postedit-confirmation-saved',
		mw.user,
		mw.config.get( 'wgRevisionId' )
	);

	assert.true( this.handler.calledOnce );
	assert.deepEqual( this.handler.args[ 0 ][ 0 ], {
		message: expectedMessage,
		tempUserCreated: undefined,
		action: 'saved',
		revisionId: mw.config.get( 'wgRevisionId' ),
		curRevisionId: mw.config.get( 'wgCurRevisionId' ),
		isRollback: false,
		editorName: 'unknown'
	} );
} );

QUnit.test( 'it handles invalid action parameter', function ( assert ) {
	postEdit.fireHook( 'Hello, World!' );

	assert.false( this.handler.called );
} );

QUnit.test.each(
	'it handles valid action parameter',
	[
		postEdit.SAVED,
		postEdit.CREATED,
		postEdit.RESTORED,
		postEdit.PUBLISHED
	],
	function ( assert, parameter ) {
		postEdit.fireHook( parameter );

		assert.true( this.handler.called );
	}
);

QUnit.test( 'it handles action and tempUserCreated parameters', function ( assert ) {
	postEdit.fireHook( postEdit.CREATED, true );

	const expectedMessage = mw.message(
		'postedit-confirmation-created',
		mw.user,
		mw.config.get( 'wgRevisionId' )
	);

	assert.true( this.handler.calledOnce );
	assert.deepEqual( this.handler.args[ 0 ][ 0 ], {
		message: expectedMessage,
		tempUserCreated: true,
		action: 'created',
		revisionId: mw.config.get( 'wgRevisionId' ),
		curRevisionId: mw.config.get( 'wgCurRevisionId' ),
		isRollback: false,
		editorName: 'unknown'
	} );
} );

QUnit.test( 'it handles action and custom data', function ( assert ) {
	postEdit.fireHook( postEdit.SAVED, {
		revisionId: 10,
		curRevisionId: 11
	} );

	const expectedMessage = mw.message(
		'postedit-confirmation-saved',
		mw.user,
		10 // It uses revisionId not curRevisionId
	);

	assert.true( this.handler.calledOnce );
	assert.deepEqual( this.handler.args[ 0 ][ 0 ], {
		message: expectedMessage,
		tempUserCreated: undefined,
		action: postEdit.SAVED,
		revisionId: 10,
		curRevisionId: 11,
		isRollback: false,
		editorName: 'unknown'
	} );
} );

QUnit.test( 'it doesn\'t override a custom message', function ( assert ) {
	postEdit.fireHook( postEdit.SAVED, {
		message: 'Hello, World!'
	} );

	assert.true( this.handler.calledOnce );
	assert.strictEqual( this.handler.args[ 0 ][ 0 ].message, 'Hello, World!' );
} );

QUnit.test( 'it doesn\'t override action', function ( assert ) {
	postEdit.fireHook( postEdit.SAVED, {
		action: 'foo'
	} );

	assert.true( this.handler.calledOnce );
	assert.strictEqual( this.handler.args[ 0 ][ 0 ].action, postEdit.SAVED );
} );

QUnit.test( 'it overrides action when EditSubmitButtonLabelPublish is truthy', function ( assert ) {
	postEdit.setConfig( {
		EditSubmitButtonLabelPublish: true
	} );

	postEdit.fireHook( postEdit.SAVED );

	assert.true( this.handler.calledOnce );
	assert.strictEqual( this.handler.args[ 0 ][ 0 ].action, postEdit.PUBLISHED );

	// ---

	postEdit.fireHook( postEdit.SAVED, {
		action: postEdit.SAVED
	} );

	assert.true( this.handler.calledTwice );
	assert.strictEqual( this.handler.args[ 1 ][ 0 ].action, postEdit.PUBLISHED );

	postEdit.resetConfig();
} );

QUnit.module( 'mediawiki.action.view.postEdit#fireHookOnPageReload', QUnit.newMwEnvironment( {
	config: {
		wgRevisionId: 1,
		wgCurRevisionId: 2
	},
	beforeEach() {
		this.sandbox.stub( mw.storage.session, 'set' );
	}
} ) );

function assertStoredUntilPageReload( assert, data ) {
	assert.true( mw.storage.session.set.calledOnce );
	assert.deepEqual( mw.storage.session.set.args[ 0 ], [
		STORAGE_KEY,
		JSON.stringify( data ),
		1200
	] );
}

QUnit.test( 'it handles no parameters', ( assert ) => {
	postEdit.fireHookOnPageReload();

	assertStoredUntilPageReload( assert, {
		action: postEdit.SAVED,
		isRollback: false
	} );
} );

QUnit.test( 'it handles action and tempUserCreated parameters', ( assert ) => {
	postEdit.fireHookOnPageReload( postEdit.SAVED, true );

	assertStoredUntilPageReload( assert, {
		action: postEdit.SAVED,
		isRollback: false,
		tempUserCreated: true
	} );
} );

QUnit.test( 'it handles invalid action parameter', ( assert ) => {
	postEdit.fireHookOnPageReload( 'Hello, World!' );

	assert.false( mw.storage.session.set.called );
} );

QUnit.test( 'it handles action and custom data', ( assert ) => {
	postEdit.fireHookOnPageReload( postEdit.CREATED, {
		tempUserCreated: true,
		editorName: 'my_awesome_editor'
	} );

	assertStoredUntilPageReload( assert, {
		action: postEdit.CREATED,
		isRollback: false,
		tempUserCreated: true,
		editorName: 'my_awesome_editor'
	} );
} );

QUnit.test( 'it removes a custom message', ( assert ) => {
	postEdit.fireHookOnPageReload( postEdit.CREATED, {
		message: 'Hello, World!'
	} );

	assertStoredUntilPageReload( assert, {
		action: postEdit.CREATED,
		isRollback: false
	} );
} );

QUnit.module( 'mediawiki.action.view.postEdit#init', QUnit.newMwEnvironment( {
	config: {
		wgRevisionId: 1,
		wgCurRevisionId: 2,

		// Disable the post-edit confirmation
		wgPostEditConfirmationDisabled: true
	},
	beforeEach() {
		const handler = this.handler = this.sandbox.spy();

		this.sandbox.stub( mw, 'hook' ).returns( {
			fire() {
				handler.apply( handler, arguments );
			}
		} );
	},
	afterEach() {
		mw.storage.session.remove( STORAGE_KEY );
	}
} ) );

QUnit.test( 'it handles empty storage', function ( assert ) {
	postEdit.init();

	assert.false( this.handler.called );
} );

QUnit.test( 'it handles old-style action value', function ( assert ) {
	mw.storage.session.set( STORAGE_KEY, postEdit.SAVED );

	postEdit.init();

	const expectedMessage = mw.message(
		'postedit-confirmation-saved',
		mw.user,
		mw.config.get( 'wgRevisionId' )
	);

	assert.strictEqual( mw.config.get( 'wgPostEdit' ), postEdit.SAVED );

	assert.true( this.handler.calledOnce );
	assert.deepEqual( this.handler.args[ 0 ][ 0 ], {
		message: expectedMessage,
		tempUserCreated: false,
		action: postEdit.SAVED,
		revisionId: mw.config.get( 'wgRevisionId' ),
		curRevisionId: mw.config.get( 'wgCurRevisionId' ),
		isRollback: false,
		editorName: 'unknown'
	} );
} );

QUnit.test( 'it handles old-style action and tempUserCreated value', function ( assert ) {
	mw.storage.session.set( STORAGE_KEY, 'created+tempuser' );

	postEdit.init();

	const expectedMessage = mw.message(
		'postedit-confirmation-created',
		mw.user,
		mw.config.get( 'wgRevisionId' )
	);

	assert.strictEqual( mw.config.get( 'wgPostEdit' ), postEdit.CREATED );

	assert.true( this.handler.calledOnce );
	assert.deepEqual( this.handler.args[ 0 ][ 0 ], {
		message: expectedMessage,
		tempUserCreated: true,
		action: postEdit.CREATED,
		revisionId: mw.config.get( 'wgRevisionId' ),
		curRevisionId: mw.config.get( 'wgCurRevisionId' ),
		isRollback: false,
		editorName: 'unknown'
	} );
} );

QUnit.test( 'it handles new-style value', function ( assert ) {
	postEdit.fireHookOnPageReload( postEdit.SAVED, {
		tempUserCreated: true,
		editorName: 'my_awesome_editor'
	} );

	postEdit.init();

	const expectedMessage = mw.message(
		'postedit-confirmation-saved',
		mw.user,
		mw.config.get( 'wgRevisionId' )
	);

	assert.strictEqual( mw.config.get( 'wgPostEdit' ), postEdit.SAVED );

	assert.true( this.handler.calledOnce );
	assert.deepEqual( this.handler.args[ 0 ][ 0 ], {
		message: expectedMessage,
		tempUserCreated: true,
		editorName: 'my_awesome_editor',
		action: postEdit.SAVED,
		revisionId: mw.config.get( 'wgRevisionId' ),
		curRevisionId: mw.config.get( 'wgCurRevisionId' ),
		isRollback: false
	} );
} );
