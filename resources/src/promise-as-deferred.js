// TODO: deprecation messages
Promise.prototype.fail = function( failHandler ) {
	this.catch( failHandler );
	return this;
};
Promise.prototype.done = function( doneHandler ) {
	this.then( doneHandler );
	return this;
};
Promise.prototype.always = function( alwaysHandler ) {
	this.catch( function() {} ).then( alwaysHandler );
	return this;
};
