import Page from 'wdio-mediawiki/Page.js';

class DeletePage extends Page {
	get reason() {
		return $( '#wpReason input' );
	}

	get watch() {
		return $( '#wpWatch' );
	}

	get submit() {
		return $( '#wpConfirmB' );
	}

	get displayedContent() {
		return $( '#mw-content-text' );
	}

	async open( title ) {
		return super.openTitle( title, { action: 'delete' } );
	}

	async delete( title, reason ) {
		await this.open( title );
		await this.reason.setValue( reason );
		await this.submit.click();
	}
}

export default new DeletePage();
