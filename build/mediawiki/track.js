var trackCallbacks = $.Callbacks( 'memory' ),
	now = require( './now' ),
	trackHandlers = [],
	trackQueue = [];

function trackUnsubscribe( callback ) {
	trackHandlers = $.grep( trackHandlers, function ( fns ) {
		if ( fns[ 1 ] === callback ) {
			trackCallbacks.remove( fns[ 0 ] );
			// Ensure the tuple is removed to avoid holding on to closures
			return false;
		}
		return true;
	} );
}

function trackSubscribe( topic, callback ) {
	var seen = 0;
	function handler( trackQueue ) {
		var event;
		for ( ; seen < trackQueue.length; seen++ ) {
			event = trackQueue[ seen ];
			if ( event.topic.indexOf( topic ) === 0 ) {
				callback.call( event, event.topic, event.data );
			}
		}
	}

	trackHandlers.push( [ handler, callback ] );

	trackCallbacks.add( handler );
}

function track( topic, data ) {
	trackQueue.push( { topic: topic, timeStamp: now(), data: data } );
	trackCallbacks.fire( trackQueue );
}

module.exports = {
	track: track,
	trackUnsubscribe: trackUnsubscribe,
	trackSubscribe: trackSubscribe
};
