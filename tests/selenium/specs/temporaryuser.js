// This file is used at Selenium/Explanation/Page object pattern
// https://www.mediawiki.org/wiki/Selenium/Explanation/Page_object_pattern

import CreateAccountPage from 'wdio-mediawiki/CreateAccountPage.js';
import EditPage from '../pageobjects/edit.page.js';
import { getTestString } from 'wdio-mediawiki/Util.js';

describe( 'Temporary user', () => {
	it( 'should not see signup form fields relevant to named users', async () => {
		const pageTitle = getTestString( 'TempUserSignup-TestPage-' );
		const pageText = getTestString();

		await EditPage.edit( pageTitle, pageText );

		// Wait for the edit to succeed, which when it has the
		// temporary account should have been created
		await expect( EditPage.heading ).toHaveText( pageTitle );

		await CreateAccountPage.open();

		await expect( CreateAccountPage.username ).toExist();
		await expect( CreateAccountPage.password ).toExist();
		expect( await CreateAccountPage.tempPasswordInput.isExisting() ).toBe( false,
			{ message: 'Temporary users should not have the option to have a temporary password sent on signup (T328718)' }
		);
		expect( await CreateAccountPage.reasonInput.isExisting() ).toBe( false,
			{ message: 'Temporary users should not have to provide a reason for their account creation (T328718)' }
		);
	} );
} );
