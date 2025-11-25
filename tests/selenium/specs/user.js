// This file is used at Selenium/Explanation/Page object pattern
// https://www.mediawiki.org/wiki/Selenium/Explanation/Page_object_pattern

import CreateAccountPage from 'wdio-mediawiki/CreateAccountPage.js';
import EditPage from '../pageobjects/edit.page.js';
import LoginPage from 'wdio-mediawiki/LoginPage.js';
import BlockPage from '../pageobjects/block.page.js';
import { createApiClient } from 'wdio-mediawiki/Api.js';
import { getTestString } from 'wdio-mediawiki/Util.js';

describe( 'User', () => {
	let password, username, apiClient;

	before( async () => {
		apiClient = await createApiClient();
	} );

	beforeEach( async () => {
		await browser.deleteAllCookies();
		username = getTestString( 'User-' );
		password = getTestString();
	} );

	it( 'should be able to create account', async () => {
		// create
		await CreateAccountPage.createAccount( username, password );

		// check
		await expect( CreateAccountPage.heading ).toHaveText( `Welcome, ${ username }!` );
	} );

	it( 'should be able to log in', async () => {
		// create
		await apiClient.createAccount( username, password );

		// log in
		await LoginPage.login( username, password );

		// check
		await expect( await LoginPage.getActualUsername() ).toBe( username );
	} );

	it( 'named user should see extra signup form fields when creating an account', async () => {
		await apiClient.createAccount( username, password );
		await LoginPage.login( username, password );

		await CreateAccountPage.open();

		await expect( CreateAccountPage.username ).toExist();
		await expect( CreateAccountPage.password ).toExist();
		await expect( CreateAccountPage.tempPasswordInput ).toExist(
			{ message: 'Named users should have the option to have a temporary password sent on signup (T328718)' }
		);
		await expect( CreateAccountPage.reasonInput ).toExist(
			{ message: 'Named users should have to provide a reason for their account creation (T328718)' }
		);
	} );

	it( 'temporary user should not see signup form fields relevant to named users', async () => {
		const pageTitle = getTestString( 'TempUserSignup-TestPage-' );
		const pageText = getTestString();

		await EditPage.edit( pageTitle, pageText );

		// Wait for the edit to succeed, which when it has the
		// temporary account should have been created
		await expect( EditPage.heading ).toHaveText( pageTitle );

		await CreateAccountPage.open();

		await expect( CreateAccountPage.username ).toExist();
		await expect( CreateAccountPage.password ).toExist();
		await expect( CreateAccountPage.tempPasswordInput ).not.toExist(
			{ message: 'Temporary users should not have the option to have a temporary password sent on signup (T328718)' }
		);
		await expect( CreateAccountPage.reasonInput ).not.toExist(
			{ message: 'Temporary users should not have to provide a reason for their account creation (T328718)' }
		);
	} );

	it( 'temporary user should be able to create account', async () => {
		const pageTitle = getTestString( 'TempUserSignup-TestPage-' );
		const pageText = getTestString();

		await EditPage.edit( pageTitle, pageText );

		// Wait for the edit to succeed, which when it has the
		// temporary account should have been created
		await expect( EditPage.heading ).toHaveText( pageTitle );

		await CreateAccountPage.open();

		await CreateAccountPage.submitForm( username, password );
		await expect( await LoginPage.getActualUsername() ).toBe( username );
		await expect( CreateAccountPage.heading ).toHaveText( `Welcome, ${ username }!` );
	} );

	it( 'should be able to block a user', async () => {
		await apiClient.createAccount( username, password );

		await LoginPage.loginAdmin();

		const expiry = '31 hours';
		const reason = getTestString();
		await BlockPage.block( username, expiry, reason );

		await expect( BlockPage.messages ).toHaveText( expect.stringContaining( 'Block added' ) );
	} );
} );
