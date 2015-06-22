self.addEventListener( 'install', function() {
	console.log( 'Service worker install' );
} );

self.addEventListener( 'activate', function() {
	console.log( 'Service worker activate' );
} );

self.addEventListener( 'fetch', function( event ) {
	console.log( 'Service worker fetch' );
	event.respondWith( fetch( event.request ) );
} );