import Page from 'wdio-mediawiki/Page.js';
import { waitForModuleState } from 'wdio-mediawiki/Util.js';

class EditPage extends Page {
	get content() {
		return $( '#wpTextbox1' );
	}

	get conflictingContent() {
		return $( '#wpTextbox2' );
	}

	get displayedContent() {
		return $( '#mw-content-text .mw-parser-output' );
	}

	get heading() {
		return $( '#firstHeading' );
	}

	get save() {
		return $( '#wpSave' );
	}

	get previewButton() {
		return $( '#wpPreview' );
	}

	get tempUserSignUpButton() {
		return $( '.mw-temp-user-banner-buttons > #pt-createaccount' );
	}

	async openForEditing( title ) {
		await super.openTitle( title, { action: 'submit', vehidebetadialog: 1, hidewelcomedialog: 1 } );
		// Compatibility with CodeMirror extension (T324879)
		await waitForModuleState( 'mediawiki.base' );
		const hasToolbar = await this.save.isExisting() && await browser.execute( () => mw.loader.getState( 'ext.wikiEditor' ) !== null );
		if ( !hasToolbar ) {
			return;
		}
		await $( '#wikiEditor-ui-toolbar' ).waitForDisplayed();
		const cmButton = $( '.mw-editbutton-codemirror-active' );
		if ( await cmButton.isExisting() ) {
			await cmButton.click();
			await browser.waitUntil( async () => !( await cmButton.getAttribute( 'class' ) ).includes( 'mw-editbutton-codemirror-active' ) );
		}
	}

	async preview( name, content ) {
		await this.openForEditing( name );
		await this.content.setValue( content );
		await this.previewButton.click();
	}

	async edit( name, content ) {
		await this.openForEditing( name );
		await this.content.setValue( content );
		await this.save.click();
	}

	/**
	 * Navigate to Special:CreateAccount via the banner links if logged in as a temporary user.
	 *
	 * @return {Promise<void>}
	 */
	async openCreateAccountPageAsTempUser() {
		await this.tempUserSignUpButton.waitForDisplayed();
		await this.tempUserSignUpButton.click();
	}
}

export default new EditPage();
