var pako = require( '../../lib/pako/pako_deflate.js' );

mw.deflate = function ( data ) {
	return 'rawdeflate,' + btoa(
		pako.deflateRaw( data, { to: 'string', level: 5 } )
	);
};
