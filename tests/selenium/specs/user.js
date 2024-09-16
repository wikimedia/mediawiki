// This file is used at Selenium/Explanation/Page object pattern
// https://www.mediawiki.org/wiki/Selenium/Explanation/Page_object_pattern

'use strict';

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
		await expect( await CreateAccountPage.heading ).toHaveText( `Welcome, ${ username }!` );
	} );

	it( 'should be able to log in', async () => {
		// create
		await Api.createAccount( bot, username, password );

		// log in
		await LoginPage.login( username, password );

		// check
		const actualUsername = await LoginPage.getActualUsername();
		expect( actualUsername ).toBe( username );
	} );

	it( 'named user should see extra signup form fields when creating an account', async () => {
		await Api.createAccount( bot, username, password );
		await LoginPage.login( username, password );

		await CreateAccountPage.open();

		await expect( await CreateAccountPage.username ).toExist();
		await expect( await CreateAccountPage.password ).toExist();
		await expect( await CreateAccountPage.tempPasswordInput ).toExist(
			{ message: 'Named users should have the option to have a temporary password sent on signup (T328718)' }
		);
		await expect( await CreateAccountPage.reasonInput ).toExist(
			{ message: 'Named users should have to provide a reason for their account creation (T328718)' }
		);
	} );

	it( 'temporary user should not see signup form fields relevant to named users', async () => {
		const pageTitle = Util.getTestString( 'TempUserSignup-TestPage-' );
		const pageText = Util.getTestString();

		await EditPage.edit( pageTitle, pageText );
		await EditPage.openCreateAccountPageAsTempUser();

		await expect( await CreateAccountPage.username ).toExist();
		await expect( await CreateAccountPage.password ).toExist();
		await expect( await CreateAccountPage.tempPasswordInput ).not.toExist(
			{ message: 'Temporary users should not have the option to have a temporary password sent on signup (T328718)' }
		);
		await expect( await CreateAccountPage.reasonInput ).not.toExist(
			{ message: 'Temporary users should not have to provide a reason for their account creation (T328718)' }
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
		expect( actualUsername ).toBe( username );
		await expect( await CreateAccountPage.heading ).toHaveText( `Welcome, ${ username }!` );
	} );
} );
