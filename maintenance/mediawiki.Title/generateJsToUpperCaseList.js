/* eslint-env node, es6 */
var i, chars = [];

for ( i = 0; i <= 0x10ffff; i++ ) {
	// eslint-disable-next-line no-restricted-properties
	chars.push( String.fromCodePoint( i ).toUpperCase() );
}
// eslint-disable-next-line no-console
console.log( JSON.stringify( chars ) );
