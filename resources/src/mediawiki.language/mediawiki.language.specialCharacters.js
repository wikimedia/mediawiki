( function () {
	var specialCharacters = require( './specialcharacters.json' );
	mw.language.setSpecialCharacters( specialCharacters );
	module.exports = specialCharacters;
}() );
