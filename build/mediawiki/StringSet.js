module.exports = window.Set || ( function () {
	/**
	 * @private
	 * @class
	 */
	function StringSet() {
		this.set = {};
	}
	StringSet.prototype.add = function ( value ) {
		this.set[ value ] = true;
	};
	StringSet.prototype.has = function ( value ) {
		return this.set.hasOwnProperty( value );
	};
	return StringSet;
}() );
