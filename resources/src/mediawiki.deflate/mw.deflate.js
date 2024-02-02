var pako = require( '../../lib/pako/pako_deflate.js' );

/**
 * Convert a byte stream to base64 text.
 * Before using load the mediawiki.deflate ResourceLoader module.
 *
 * @example
 * return mw.loader.using( 'mediawiki.deflate' ).then( function () {
 *    return mw.deflate( html );
 * } );
 * @param {string} data
 * @return {string}
 */
mw.deflate = function ( data ) {
	return 'rawdeflate,' + bytesToBase64( pako.deflateRaw( data, { level: 5 } ) );
};

/*
 * Convert a byte stream to base64 text.
 *
 * As suggested in https://github.com/nodeca/pako/issues/206#issuecomment-744264726
 *
 * Code from https://gist.github.com/enepomnyaschih/72c423f727d395eeaa09697058238727.
 * MIT License
 * Copyright (c) 2020 Egor Nepomnyaschih
 *
 * @type {Array}
 */
var base64abc = [
	'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
	'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
	'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
	'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
	'0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '+', '/'
];

function bytesToBase64( bytes ) {
	/* eslint-disable no-bitwise */
	var result = '', i, l = bytes.length;
	for ( i = 2; i < l; i += 3 ) {
		result += base64abc[ bytes[ i - 2 ] >> 2 ];
		result += base64abc[ ( ( bytes[ i - 2 ] & 0x03 ) << 4 ) | ( bytes[ i - 1 ] >> 4 ) ];
		result += base64abc[ ( ( bytes[ i - 1 ] & 0x0F ) << 2 ) | ( bytes[ i ] >> 6 ) ];
		result += base64abc[ bytes[ i ] & 0x3F ];
	}
	if ( i === l + 1 ) { // 1 octet yet to write
		result += base64abc[ bytes[ i - 2 ] >> 2 ];
		result += base64abc[ ( bytes[ i - 2 ] & 0x03 ) << 4 ];
		result += '==';
	}
	if ( i === l ) { // 2 octets yet to write
		result += base64abc[ bytes[ i - 2 ] >> 2 ];
		result += base64abc[ ( ( bytes[ i - 2 ] & 0x03 ) << 4 ) | ( bytes[ i - 1 ] >> 4 ) ];
		result += base64abc[ ( bytes[ i - 1 ] & 0x0F ) << 2 ];
		result += '=';
	}
	return result;
	/* eslint-enable no-bitwise */
}
