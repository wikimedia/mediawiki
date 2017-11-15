/*jshint esversion: 6, node:true */
/*global console */

const fs = require( 'fs' ),
	net = require( 'net' );

// Somewhat generic unix socket server. Is there perhaps a package
// we could source this from instead?
class Server {
	constructor( path ) {
		if ( !this.dispatch ) {
			this.ready = Promise.reject( 'Dispatch not implemented' );
			return;
		}

		console.log( `Starting server to listen on ${path}` );
		this.ready = new Promise( ( resolve, reject ) => {
			this.unixServer = net.createServer( ( c ) => this.onClient( c ) );
			this.unixServer.on( 'error', this.onInitializationError( reject ) );
			this.unixServer.on( 'listening', this.onListening( resolve ) );
			this.unixServer.listen( path );
		} );
	}

	onInitializationError( reject ) {
		return ( e ) => {
			console.log( 'Server initialization failed' );
			if ( e.code === 'EADDRINUSE' ) {
				// prevent unlinking the file we never took ownership of
				this.unixServer.unref();
				this.unixServer = undefined;
			}
			reject( e.code );
		};
	}

	onListening( resolve ) {
		return () => {
			console.log( 'Server initialized' );
			this.unixServer.on( 'close', this.shutdown );
			// TODO: Are these needed? Or is the 'close' listener enough?
			process.on( 'SIGTERM', this.shutdown );
			process.on( 'SIGINT', this.shutdown );
			// TODO: Do we need another handler for this?
			this.unixServer.removeListener( 'error', this.onInitializationError );
			resolve();
		};
	}

	onClient( socket ) {
		socket.on('data', ( data ) => {
			var parsed;
			try {
				parsed = JSON.parse( data );
			} catch ( e ) {
				// Invalid JSON. Ignore? May lead to
				// timeouts on the other end...
				return;
			}
			this.dispatch( parsed ).then( ( response ) => {
				if ( response ) {
					response.requestId = parsed.requestId;
					socket.write( JSON.stringify( response ) );
				}
			} );
		} );
	}

	shutdown() {
		console.log( 'Running server shutdown' );
		if ( this.unixServer ) {
			console.log( 'cleaning up' );
			this.unixServer.unref();
			fs.unlinkSync( this.unixServer.address() );
			delete this.unixServer;
		}
	}
}

// Communication protocol with clients
class TagTrackerServer extends Server {
	constructor( path ) {
		super( path );
		this.tags = {};
		this.pending = {};
		this.resolvers = {};
	}

	dispatch( data ) {
		return new Promise( ( resolve ) => {
			if ( data.complete ) {
				// tag completed, resolve pending
				if ( this.pending[data.complete] ) {
					this.resolvers[data.complete]( {
						tag: data.complete,
						status: 'complete'
					} );
				} else {
					// Happens if things are called out of order. Not sure
					// why that would happen ... but whatever.
					console.log( `Resolving tag ${data.complete} before it was requested` );
					this.resolvers[data.complete] = () => {};
					this.pending[data.complete] = Promise.resolve( {
						tag: data.complete,
						status: 'complete'
					} );
				}
				// Just echo it back. Not used for anything.
				resolve( data );
			} else if ( this.pending[data.check] ) {
				// Another process is initializing this tag. Wait for it
				// to signal completion
				this.pending[data.check].then( resolve );
			} else {
				// New tag
				this.pending[data.check] = new Promise( ( resolve ) => {
					this.resolvers[data.check] = resolve;
				} );
				resolve( {
					tag: data.check,
					status: 'new'
				} );
			}
		} );
	}
}

// Communication protocol with host process
(() => {
	var server;
	process.on( 'message', ( msg ) => {
		if ( msg.config ) {
			if ( server ) {
				process.send( { error: "Already initialized" } );
			} else {
				server = new TagTrackerServer( msg.config.trackerPath );
				server.ready.then(
					() =>  process.send( { initialized: true } ),
					( e ) => process.send( { error: e } ) );
			}
		}
		if ( msg.exit && server ) {
			server.shutdown();
		}
	} );
} )();
