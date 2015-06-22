/* global self, caches */

self.addEventListener( 'fetch', function ( event ) {
	if ( event && event.request && event.request.url ) {
		// If the fetched URL displays the versioned RL pattern, cache it
		if ( event.request.url.match( /\/w\/load\.php\?.+version=[^\/]+$/ ) ) {
			event.respondWith(
				caches.match( event.request ).then( function ( response ) {
					if ( response ) {
						// Found in ServiceWorker cache
						return response;
					}

					var fetchRequest = event.request.clone();

					return fetch( fetchRequest ).then(
						function ( response ) {
							if ( !response || response.status !== 200 || response.type !== 'basic' ) {
								return;
							}

							var responseToCache = response.clone();

							caches.open( 'MediawikiResourceLoaderCache' ).then( function ( cache ) {
								cache.put( event.request, responseToCache );
							} );

							return response;
						}
					);
				} )
			);

			return;
		}
	}

	// Passthrough, fetch this request as it should have been
	event.respondWith( fetch( event.request ) );
} );
