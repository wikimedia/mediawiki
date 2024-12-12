'use strict';

const Page = require( 'wdio-mediawiki/Page' );

class BlockPage extends Page {
	get target() {
		return $( '.mw-block-target input[name=wpTarget]' );
	}

	get messages() {
		return $( '.mw-block-messages' );
	}

	get activeBlocksHeader() {
		return $( '.mw-block-log__type-active .cdx-accordion__header' );
	}

	get addBlockButton() {
		return $( '.mw-block-log__create-button' );
	}

	get otherReasonInput() {
		return $( 'input[name=wpReason-other]' );
	}

	get submitButton() {
		return $( '.mw-block-submit' );
	}

	async open( expiry ) {
		return super.openTitle( 'Special:Block', {
			// Pass only the expiry and not also the target;
			// This effectively asserts wpExpiry gets set correctly in SpecialBlock.vue
			wpExpiry: expiry,
			usecodex: 1
		} );
	}

	async block( target, expiry, reason ) {
		await this.open( expiry );
		await browser.waitUntil(
			async () => ( await this.target.isDisplayed() ),
			{ timeout: 5000 }
		);
		await this.target.setValue( target );
		// Remove focus from input. Temporary workaround until T382093 is resolved.
		await $( 'body' ).click();
		await browser.waitUntil(
			async () => ( await this.activeBlocksHeader.isClickable() ),
			{ timeout: 5000 }
		);
		await this.activeBlocksHeader.click();
		await this.addBlockButton.click();
		await this.otherReasonInput.setValue( reason );
		await this.submitButton.click();
	}
}

module.exports = new BlockPage();
