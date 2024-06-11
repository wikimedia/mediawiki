'use strict';

const assert = require( 'assert' );
const CreateAccountPage = require( 'wdio-mediawiki/CreateAccountPage' );
const UserLoginPage = require( 'wdio-mediawiki/LoginPage' );
const Api = require( 'wdio-mediawiki/Api' );
const Util = require( 'wdio-mediawiki/Util' );

describe( 'User', () => {
	let password, username, bot;

	before( async () => {
		bot = await Api.bot();
	} );

	beforeEach( async () => {
		await browser.deleteAllCookies();
		username = Util.getTestString( 'User-' );
		password = Util.getTestString();
	} );

	it( 'should be able to create account', async () => {
		// create
		await CreateAccountPage.createAccount( username, password );

		// check
		assert.strictEqual( await CreateAccountPage.heading.getText(), `Welcome, ${ username }!` );
	} );

	it( 'should be able to log in', async () => {
		// create
		await Api.createAccount( bot, username, password );

		// log in
		await UserLoginPage.login( username, password );

		// check
		const actualUsername = await browser.execute( () => mw.config.get( 'wgUserName' ) );
		assert.strictEqual( await actualUsername, username );
	} );
} );
