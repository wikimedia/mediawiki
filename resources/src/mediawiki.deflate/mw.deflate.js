/**
 * Convert a byte stream to base64 text.
 *
 * @deprecated Use mw.deflateAsync
 * @example
 * return mw.loader.using( 'mediawiki.deflate' ).then( () => mw.deflate( html ) );
 * @param {string} data
 * @return {string} Compressed data
 */
mw.deflate = function ( data ) {
	const pako = require( '../../lib/pako/pako_deflate.js' );
	return 'rawdeflate,' + bytesToBase64( pako.deflateRaw( data, { level: 5 } ) );
};

/**
 * Convert a byte stream to base64 text.
 *
 * Uses browser native CompressionStream if available.
 *
 * @example
 * return mw.loader.using( 'mediawiki.deflate' ).then( () => mw.deflateAsync( html ) );
 * @param {string} data
 * @return {Promise<string>} Compressed data
 */
mw.deflateAsync = function ( data ) {
	// Support: Chrome < 80, Firefox < 113, Safari < 16.4
	if ( window.CompressionStream ) {
		return compress( data ).then( ( buffer ) => 'rawdeflate,' + bytesToBase64( new Uint8Array( buffer ) ) );
	} else {
		return Promise.resolve( mw.deflate( data ) );
	}
};

function stripHeaderAndChecksum( buffer ) {
	// Header is 2 bytes, checksum is the last 4 bytes
	return buffer.slice( 2, buffer.byteLength - 4 );
}

function compress( string ) {
	const byteArray = new TextEncoder().encode( string );
	let cs, isRaw;
	// Support: Chrome < 103
	// Not all browsers with CompressionStream support 'deflate-raw'
	// so fall back to the universally-supported 'deflate' and
	// remove the header/checksum manually
	try {
		// eslint-disable-next-line compat/compat
		cs = new CompressionStream( 'deflate-raw' );
		isRaw = true;
	} catch ( e ) {
		// eslint-disable-next-line compat/compat
		cs = new CompressionStream( 'deflate' );
		isRaw = false;
	}
	const writer = cs.writable.getWriter();
	writer.write( byteArray );
	writer.close();

	const arrayBuffer = new Response( cs.readable ).arrayBuffer();
	if ( isRaw ) {
		return arrayBuffer;
	} else {
		return arrayBuffer.then( ( buffer ) => stripHeaderAndChecksum( new Uint8Array( buffer ) ) );
	}
}

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
const base64abc = [
	'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M',
	'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
	'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm',
	'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
	'0', '1', '2', '3', '4', '5', '6', '7', '8', '9', '+', '/'
];

function bytesToBase64( bytes ) {
	/* eslint-disable no-bitwise */
	let result = '', i;
	const l = bytes.length;
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
