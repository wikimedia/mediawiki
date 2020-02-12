( function () {
	var names = require( './names.json' );
	mw.language.setData( mw.config.get( 'wgUserLanguage' ), 'languageNames', names );
}() );
