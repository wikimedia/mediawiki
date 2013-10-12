var eliza = new ElizaBot();
onmessage = function( e ) {
	postMessage( eliza.transform( e.data ) );
};
