/* eslint-env node, es6 */
var i, chars = [];

for ( i = 0; i < 65536; i++ ) {
	chars.push( String.fromCharCode( i ).toUpperCase() );
}
// eslint-disable-next-line no-console
console.log( JSON.stringify( chars ) );
