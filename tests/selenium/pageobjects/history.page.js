const Page = require( 'wdio-mediawiki/Page' );
const Api = require( 'wdio-mediawiki/Api' );
const Util = require( 'wdio-mediawiki/Util' );

class HistoryPage extends Page {
	get heading() { return $( '#firstHeading' ); }
	get comment() { return $( '#pagehistory .comment' ); }
	get rollback() { return $( '.mw-rollback-link' ); }
	get rollbackLink() { return $( '.mw-rollback-link a' ); }
	get rollbackConfirmable() { return $( '.mw-rollback-link .jquery-confirmable-text' ); }
	get rollbackConfirmableYes() { return $( '.mw-rollback-link .jquery-confirmable-button-yes' ); }
	get rollbackConfirmableNo() { return $( '.mw-rollback-link .jquery-confirmable-button-no' ); }
	get rollbackNonJsConfirmable() { return $( '.mw-htmlform .oo-ui-fieldsetLayout-header .oo-ui-labelElement-label' ); }
	get rollbackNonJsConfirmableYes() { return $( '.mw-htmlform .mw-htmlform-submit-buttons button' ); }

	open( title ) {
		super.openTitle( title, { action: 'history' } );
	}

	toggleRollbackConfirmationSetting( enable ) {
		Util.waitForModuleState( 'mediawiki.api', 'ready', 5000 );
		return browser.execute( function ( enable ) {
			return new mw.Api().saveOption(
				'showrollbackconfirmation',
				enable ? '1' : '0'
			);
		}, enable );
	}

	vandalizePage( name, content ) {
		const vandalUsername = 'Evil_' + browser.config.mwUser;

		browser.call( function () {
			return Api.edit( name, content );
		} );

		browser.call( function () {
			return Api.createAccount(
				vandalUsername, browser.config.mwPwd
			);
		} );

		browser.call( function () {
			return Api.edit(
				name,
				'Vandalized: ' + content,
				vandalUsername
			);
		} );
	}
}

module.exports = new HistoryPage();
