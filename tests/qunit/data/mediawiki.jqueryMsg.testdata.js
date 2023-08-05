module.exports.phpParserData = require( './mediawiki.jqueryMsg.data.json' );
module.exports.initLang = function ( langCode ) {
	try {
		// eslint-disable-next-line security/detect-non-literal-require
		require( './' + langCode + '.js' );
	} catch ( e ) {
		// No custom JavaScript needed for this language
	}
};
