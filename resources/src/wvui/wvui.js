// For now, while only the wvui-search is made available, export
// its contents so the wvui module can still be used as package files.
module.exports = require( 'wvui-search' );

// Old contents, and eventually the new contents (uncomment line 7):
// Unwrap the .default in what WVUI returns.
// module.exports = require( '../../lib/wvui/wvui.commonjs2.js' ).default;
