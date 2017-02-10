var slice = Array.prototype.slice;

module.exports = function ( formatString ) {
	var parameters = slice.call( arguments, 1 );
	return formatString.replace( /\$(\d+)/g, function ( str, match ) {
		var index = parseInt( match, 10 ) - 1;
		return parameters[ index ] !== undefined ? parameters[ index ] : '$' + match;
	} );
};
