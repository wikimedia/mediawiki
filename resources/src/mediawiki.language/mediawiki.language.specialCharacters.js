( function () {
	var specialCharacters = require( './specialcharacters.json' );
	// Deprecated since 1.33
	mw.log.deprecate( mw.language, 'specialCharacters', specialCharacters,
		'Use require( \'mediawiki.language.specialCharacters\' ) instead',
		'mw.language.specialCharacters'
	);
	module.exports = specialCharacters;
}() );
