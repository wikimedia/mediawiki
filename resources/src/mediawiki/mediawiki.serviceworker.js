self.addEventListener( 'install', function( event ) {
	console.log( 'Service worker install' );
} );

self.addEventListener( 'activate', function( event ) {
	console.log( 'Service worker activate' );
} );

self.addEventListener( 'fetch', function( event ) {
	console.log( 'Service worker fetch' );
	event.respondWith( fetch( event.request ) );
} );
// Because RL tacks an mw.loader.state call onto the end of the script
mw = { loader : { state: function() { } } };