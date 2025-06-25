import Page from 'wdio-mediawiki/Page.js';

class RestorePage extends Page {
	get reason() {
		return $( '#wpComment' );
	}

	get submit() {
		return $( '#mw-undelete-submit' );
	}

	get displayedContent() {
		return $( '#mw-content-text' );
	}

	async open( subject ) {
		return super.openTitle( 'Special:Undelete/' + subject );
	}

	async restore( subject, reason ) {
		await this.open( subject );
		await this.reason.setValue( reason );
		await this.submit.click();
	}
}

export default new RestorePage();
