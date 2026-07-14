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

	async request( params, attempt = 1 ) {
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
			// We do not follow redirects because fetch will switch POST to GET and set
			// the response body to null
			// See https://fetch.spec.whatwg.org/#http-redirect-fetch
			// eslint-disable-next-line n/no-unsupported-features/node-builtins
			const response = await fetch( url, { method: 'POST', headers, body, signal, redirect: 'error' } );
			const text = await response.text();

			if ( this.verbose ) {
				console.log( '[API] API Response:', text );
			}

			if ( !response.ok ) {
				console.error( `[API] HTTP ${ response.status }: ${ response.statusText }`, text );
				throw new Error( `HTTP ${ response.status }` );
			}

			// getSetCookie in NodeJS since 18.16.0
			this.cookies.getCookiesFromHeaders( response.headers.getSetCookie() );

			let data;
			try {
				data = text ? JSON.parse( text ) : {};
			} catch ( error ) {
				console.error( '[API] Failed getting response as JSON:', error.message, text );
				throw error;
			}

			if ( data.error ) {
				const code = data.error.code || 'unknown';
				const info = data.error.info || JSON.stringify( data.error );
				console.error( `[API] API Error: ${ code }: ${ info }` );
				// Retry transient server errors, e.g. DB deadlocks.
				if ( attempt < 3 && code.startsWith( 'internal_api_error_' ) ) {
					console.warn( `[API] Retrying request (attempt ${ attempt + 1 } of 3)` );
					await new Promise( ( resolve ) => {
						setTimeout( resolve, 1000 );
					} );
					return this.request( params, attempt + 1 );
				}
				throw new Error( `${ code }: ${ info }` );
			}

			return data;
		} catch ( error ) {
			if ( error.name === 'AbortError' ) {
				console.error( '[API] Network timeout:', error.message, { url: this.apiUrl } );
			}
			throw error;
		}
	}

}
