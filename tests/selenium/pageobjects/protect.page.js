'use strict';

const Page = require( 'wdio-mediawiki/Page' );

class ProtectPage extends Page {
	get reason() { return $( '#mwProtect-reason input' ); }
	get editProtectSelect() { return $( '#mwProtect-level-edit select' ); }
	get submit() { return $( '#mw-Protect-submit' ); }

	open( title ) {
		super.openTitle( title, { action: 'protect' } );
	}

	async protect( title, reason, editProtect ) {
		await this.open( title );
		await this.reason.setValue( reason );
		await this.editProtectSelect.selectByVisibleText( editProtect );
		await this.submit.click();
	}

}

module.exports = new ProtectPage();
