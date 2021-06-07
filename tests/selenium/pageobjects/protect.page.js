'use strict';

const Page = require( 'wdio-mediawiki/Page' );

class ProtectPage extends Page {
	get reason() { return $( '#mwProtect-reason input' ); }
	get editProtectSelect() { return $( '#mwProtect-level-edit select' ); }
	get submit() { return $( '#mw-Protect-submit' ); }

	open( title ) {
		super.openTitle( title, { action: 'protect' } );
	}

	protect( title, reason, editProtect ) {
		this.open( title );
		this.reason.setValue( reason );
		this.editProtectSelect.selectByVisibleText( editProtect );
		this.submit.click();
	}

}

module.exports = new ProtectPage();
