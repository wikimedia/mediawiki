// This file is used at Selenium/Explanation/Page object pattern
// https://www.mediawiki.org/wiki/Selenium/Explanation/Page_object_pattern

'use strict';

const assert = require( 'assert' );
const CreateAccountPage = require( 'wdio-mediawiki/CreateAccountPage' );
const EditPage = require( '../pageobjects/edit.page' );
const LoginPage = require( 'wdio-mediawiki/LoginPage' );
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
		await LoginPage.login( username, password );

		// check
		const actualUsername = await LoginPage.getActualUsername();
		assert.strictEqual( actualUsername, username );
	} );

	it( 'named user should see extra signup form fields when creating an account', async () => {
		await Api.createAccount( bot, username, password );
		await LoginPage.login( username, password );

		await CreateAccountPage.open();

		assert.ok( await CreateAccountPage.username.isExisting() );
		assert.ok( await CreateAccountPage.password.isExisting() );
		assert.ok(
			await CreateAccountPage.tempPasswordInput.isExisting(),
			'Named users should have the option to have a temporary password sent on signup (T328718)'
		);
		assert.ok(
			await CreateAccountPage.reasonInput.isExisting(),
			'Named users should have to provide a reason for their account creation (T328718)'
		);
	} );

	it( 'temporary user should not see signup form fields relevant to named users', async () => {
		const pageTitle = Util.getTestString( 'TempUserSignup-TestPage-' );
		const pageText = Util.getTestString();

		await EditPage.edit( pageTitle, pageText );
		await EditPage.openCreateAccountPageAsTempUser();

		assert.ok( await CreateAccountPage.username.isExisting() );
		assert.ok( await CreateAccountPage.password.isExisting() );
		assert.ok(
			!await CreateAccountPage.tempPasswordInput.isExisting(),
			'Temporary users should not have the option to have a temporary password sent on signup (T328718)'
		);
		assert.ok(
			!await CreateAccountPage.reasonInput.isExisting(),
			'Temporary users should not have to provide a reason for their account creation (T328718)'
		);
	} );

	// NOTE: This test can't run parallel with other account creation tests (T199393)
	it( 'temporary user should be able to create account', async () => {
		const pageTitle = Util.getTestString( 'TempUserSignup-TestPage-' );
		const pageText = Util.getTestString();

		await EditPage.edit( pageTitle, pageText );
		await EditPage.openCreateAccountPageAsTempUser();

		await CreateAccountPage.submitForm( username, password );

		const actualUsername = await LoginPage.getActualUsername();
		assert.strictEqual( actualUsername, username );
		assert.strictEqual( await CreateAccountPage.heading.getText(), `Welcome, ${ username }!` );
	} );
} );
