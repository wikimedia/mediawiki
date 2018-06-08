const Page = require( 'wdio-mediawiki/Page' );

class RestorePage extends Page {
	get reason() { return browser.element( '#wpComment' ); }
	get submit() { return browser.element( '#mw-undelete-submit' ); }
	get displayedContent() { return browser.element( '#mw-content-text' ); }

	getRevisionCheckbox( revisionIndex ) {
		return browser.element( '#undelete > ul > li:nth-child(' + revisionIndex + ') > input' );
	}

	open( subject ) {
		super.openTitle( 'Special:Undelete/' + subject );
	}

	restore( subject, reason, revisionCheckboxIndexesToCheck = [] ) {
		this.open( subject );
		for ( let index = 0; index < revisionCheckboxIndexesToCheck.length; ++index ) {
			this.checkRevisionCheckboxAtIndex( revisionCheckboxIndexesToCheck[ index ] );
		}
		revisionCheckboxIndexesToCheck.forEach( this.checkRevisionCheckboxAtIndex );
		this.reason.setValue( reason );
		this.submit.click();
	}

	checkRevisionCheckboxAtIndex( revisionIndex ) {
		this.getRevisionCheckbox( revisionIndex ).click();
	}
}

module.exports = new RestorePage();
