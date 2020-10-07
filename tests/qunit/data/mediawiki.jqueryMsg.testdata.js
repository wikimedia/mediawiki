module.exports.phpParserData = require( './mediawiki.jqueryMsg.data.json' );
module.exports.initLang = function ( langCode ) {
	try {
		require( './' + langCode + '.js' );
	} catch ( e ) {
		// No custom JavaScript needed for this language
	}
};
