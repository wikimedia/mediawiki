/* JavaScript for MediaWIki JS2 */

/**
 * This is designed to be directly compatible with (and is essentially taken
 * directly from) the mv_embed code for bringing internationalized messages into
 * the JavaScript space. As such, if we get to the point of merging that stuff
 * into the main branch this code will be uneeded and probably cause issues.
 */

/**
 * Mimics the no-conflict method used by the js2 stuff
 */
$j = jQuery.noConflict();
/**
 * Provides js2 compatible mw functions
 */
if( typeof mw == 'undefined' || !mw ){
   mw = { };
   /**
    * Provides js2 compatible onload hook
    * @param func Function to call when ready
    */
   mw.ready = function( func ) {
       $j(document).ready( func );
   }
   // Define a dummy mw.load function:
   mw.load = function( deps, callback ) { callback(); };
   
   // Deinfe a dummy mw.loadDone function: 
   mw.loadDone = function( className ) { };
   
   // Creates global message object if not already in existence
	if ( !gMsg ) var gMsg = {};
   
	/**
	 * Caches a list of messages for later retrieval
	 * @param {Object} msgSet Hash of key:value pairs of messages to cache
	 */	 
	mw.addMessages = function ( msgSet ){
		for ( var i in msgSet ){
			gMsg[ i ] = msgSet[i];
		}
	}
	/**
	 * Retieves a message from the global message cache, performing on-the-fly
	 * replacements using MediaWiki message syntax ($1, $2, etc.)
	 * @param {String} key Name of message as it is in MediaWiki
	 * @param {Array} args Array of replacement arguments
	 */
	function gM( key, args ) {
		var ms = '';	
		if ( key in gMsg ) {
			ms = gMsg[ key ];
			if ( typeof args == 'object' || typeof args == 'array' ) {
				for ( var v in args ){
					var rep = '\$'+ ( parseInt(v) + 1 );
					ms = ms.replace( rep, args[v]);
				}
			} else if ( typeof args =='string' || typeof args =='number' ) {
				ms = ms.replace( /\$1/, args );
			}
			return ms;
		} else {
			return '[' + key + ']';
		}
	}
   
}
