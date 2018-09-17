/**
 * @class jQuery.plugin.getAttrs
 */
( function () {
	function serializeControls( controls ) {
		var i,
			data = {},
			len = controls.length;

		for ( i = 0; i < len; i++ ) {
			data[ controls[ i ].name ] = controls[ i ].value;
		}

		return data;
	}

	/**
	 * Get the attributes of an element directy as a plain object.
	 *
	 * If there is more than one element in the collection, similar to most other jQuery getter methods,
	 * this will use the first element in the collection.
	 *
	 * @return {Object}
	 */
	$.fn.getAttrs = function () {
		return serializeControls( this[ 0 ].attributes );
	};

	/**
	 * Get form data as a plain object mapping form control names to their values.
	 *
	 * @return {Object}
	 */
	$.fn.serializeObject = function () {
		return serializeControls( this.serializeArray() );
	};
}() );
