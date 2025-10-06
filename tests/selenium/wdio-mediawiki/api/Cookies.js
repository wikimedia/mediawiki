/**
 * Collects cookies from HTTP responses and formats them into a Cookie header.
 */
export class Cookies {

	constructor() {
		this.pairs = {};
	}

	/**
	 * Add cookies from Set-Cookie header lines.
	 *
	 * @param {Iterable<string>} setCookies - Iterable of `Set-Cookie` header lines.
	 */
	getCookiesFromHeaders( setCookies ) {
		for ( const line of setCookies ) {
			// Take the first part of the cookie so we can pass it on the next time
			const semi = line.indexOf( ';' );
			const pair = semi === -1 ? line : line.slice( 0, semi );
			const equalSign = pair.indexOf( '=' );

			const name = pair.slice( 0, equalSign );
			const value = pair.slice( equalSign + 1 );

			this.pairs[ name ] = value;
		}
	}

	/**
	 * Build the Cookie request header value.
	 *
	 * @return {string} e.g. "a=1; b=2" or "" if no cookies
	 */
	toHeader() {
		const entries = Object.entries( this.pairs );
		if ( entries.length === 0 ) {
			return '';
		}

		return entries.map( ( [ name, value ] ) => `${ name }=${ value }` ).join( '; ' );
	}
}
