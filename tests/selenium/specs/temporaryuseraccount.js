// This file is used at Selenium/Explanation/Page object pattern
// https://www.mediawiki.org/wiki/Selenium/Explanation/Page_object_pattern

import CreateAccountPage from 'wdio-mediawiki/CreateAccountPage.js';
import EditPage from '../pageobjects/edit.page.js';
import { getTestString } from 'wdio-mediawiki/Util.js';

describe( 'Temporary user account creation', () => {

	it( 'should be able to create account', async () => {
		const username = getTestString( 'User-' );
		const password = getTestString();
		const pageTitle = getTestString( 'TempUserSignup-TestPage-' );
		const pageText = getTestString();

		await EditPage.edit( pageTitle, pageText );

		// Wait for the edit to succeed, which when it has the
		// temporary account should have been created
		await expect( EditPage.heading ).toHaveText( pageTitle );

		await CreateAccountPage.open();

		await CreateAccountPage.submitForm( username, password );
		await expect( CreateAccountPage.heading ).toHaveText( `Welcome, ${ username }!` );
	} );
} );
