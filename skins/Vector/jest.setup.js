/* global global */
const mockMediaWiki = require( '@wikimedia/mw-node-qunit/src/mockMediaWiki.js' );
global.mw = mockMediaWiki();
global.$ = require( 'jquery' );
global.mw.util.showPortlet = function () {};
global.mw.Api.prototype.saveOption = function () {};
global.OO = require( 'oojs' );
require( 'oojs-ui' );
require( 'oojs-ui/dist/oojs-ui-wikimediaui.js' );
