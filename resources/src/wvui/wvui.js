// Unwrap the .default in what WVUI returns.

// Once T281527 is resolved, change this back to wvui.commonjs2.js, and add
// a wvui-search module
module.exports = require( '../../lib/wvui/wvui-search.commonjs2.js' ).default;
