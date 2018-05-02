const Page = require( './page' ),
	// https://github.com/Fannon/mwbot
	MWBot = require( 'mwbot' );

class EditPage extends Page {
	get content() { return browser.element( '#wpTextbox1' ); }
	get displayedContent() { return browser.element( '#mw-content-text' ); }
	get heading() { return browser.element( '#firstHeading' ); }
	get save() { return browser.element( '#wpSave' ); }

	openForEditing( name ) {
		super.open( name + '&action=edit' );
	}

	edit( name, content ) {
		this.openForEditing( name );
		this.content.setValue( content );
		this.save.click();
	}

	apiEdit( name, content ) {
		let bot = new MWBot();

		return bot.loginGetEditToken( {
			apiUrl: `${browser.options.baseUrl}/api.php`,
			username: browser.options.username,
			password: browser.options.password
		} ).then( function () {
			return bot.edit( name, content, `Created page with "${content}"` );
		} );
	}
}

module.exports = new EditPage();
