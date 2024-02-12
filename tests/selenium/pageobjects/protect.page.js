'use strict';

const Page = require( 'wdio-mediawiki/Page' );

class ProtectPage extends Page {
	get reason() {
		return $( '#mwProtect-reason input' );
	}

	get confirmProtectionEdit() {
		return $( 'span=Allow all users' );
	}

	get allowOnlyAdministrators() {
		return $( 'span=Allow only administrators' );
	}

	get editProtectSelect() {
		return $( '#mwProtect-level-edit select' );
	}

	get submit() {
		return $( '#mw-Protect-submit' );
	}

	async open( title ) {
		return super.openTitle( title, { action: 'protect' } );
	}

	async protect( title, reason ) {
		await this.open( title );
		await this.reason.setValue( reason );
		await this.confirmProtectionEdit.click();
		await this.allowOnlyAdministrators.click();
		await this.submit.click();
	}

}

module.exports = new ProtectPage();
