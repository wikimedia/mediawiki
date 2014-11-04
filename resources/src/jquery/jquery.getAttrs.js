/**
 * @class jQuery.plugin.getAttrs
 */

/**
 * Get the attributes of an element directy as a plain object.
 *
 * If there is more than one element in the collection, similar to most other jQuery getter methods,
 * this will use the first element in the collection.
 *
 * @return {Object}
 */
jQuery.fn.getAttrs = function () {
	var i,
		map = this[0].attributes,
		attrs = {},
		len = map.length;

	for ( i = 0; i < len; i++ ) {
		attrs[ map[i].name ] = map[i].value;
	}

	return attrs;
};

/**
 * @class jQuery
 * @mixins jQuery.plugin.getAttrs
 */
