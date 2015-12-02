var po = {};

po.Page = function ( path ) {
	this.path = path;
};

po.Page.prototype.defineElements = function ( locator, elements ) {
	for ( var name in elements ) {
		this[ name ] = locator( elements[ name ] );
	}
};

po.Page.prototype.qualify = function ( baseURL ) {
	return baseURL + this.path;
};

module.exports = po;
