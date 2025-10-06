/**
 * Minimal MediaWiki API HTTP client.
 *
 */
export class MwApiHttpClient {
	constructor( { cookies, options } ) {
		this.cookies = cookies;
		this.apiUrl = options.apiUrl;
		this.verbose = options.verbose;
	}

	async request( params ) {
		const url = new URL( this.apiUrl );
		url.searchParams.set( 'format', 'json' );

		const body = new URLSearchParams();
		for ( const [ key, value ] of Object.entries( params ) ) {
			body.append( key, value );
		}

		// TODO we probably want to take the version from the package.json
		// and use it in the User Agent.
		// Headers since NodeJS 18 and stable in 21.
		// eslint-disable-next-line n/no-unsupported-features/node-builtins
		const headers = new Headers( {
			'Content-Type': 'application/x-www-form-urlencoded',
			'User-Agent': 'wdio-core-api-client/1.0 (tests)'
		} );

		const cookieHeader = this.cookies.toHeader();
		if ( cookieHeader ) {
			headers.set( 'Cookie', cookieHeader );
		}

		try {
			// AbortSignal since NodeJS 16.14.0
			// eslint-disable-next-line n/no-unsupported-features/node-builtins
			const signal = AbortSignal.timeout( 60000 );
			// fetch was introduced in 18 and stable in 21.
			// eslint-disable-next-line n/no-unsupported-features/node-builtins
			const response = await fetch( url, { method: 'POST', headers, body, signal } );
			const text = await response.text();

			if ( this.verbose ) {
				console.log( '[API] API Response:', text );
			}

			if ( !response.ok ) {
				console.error( `[API] HTTP ${ response.status }: ${ response.statusText }` );
				throw new Error( `HTTP ${ response.status }` );
			}

			// getSetCookie in NodeJS since 18.16.0
			this.cookies.getCookiesFromHeaders( response.headers.getSetCookie() );

			try {
				return text ? JSON.parse( text ) : {};
			} catch ( error ) {
				console.error( '[API] Failed getting response as JSON:', error.message, text );
				throw error;
			}
		} catch ( error ) {
			if ( error.name === 'AbortError' ) {
				console.error( '[API] Network timeout:', error.message, { url: this.apiUrl } );
			}
			throw error;
		}
	}

}
