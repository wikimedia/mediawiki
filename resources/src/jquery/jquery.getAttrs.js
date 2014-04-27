/**
 * @class jQuery.plugin.getAttrs
 */

/**
 * Utility to get the attributes of an element directy as a plain object.
 *
 * If there are more elements in the collection, like most jQuery get/read methods,
 * this method will use the first element in the collection.
 *
 * @param {boolean} [all]
 * @return {Object}
 */
jQuery.fn.getAttrs = function ( all ) {
	var map = this[0].attributes,
		attrs = {},
		len = map.length,
		i, v;

	for ( i = 0; i < len; i++ ) {
		// IE6 includes *all* allowed attributes for thew element (including those
		// not set). Those have values like undefined, null, 0, false, "" or "inherit".
		// However there may be genuine attributes set to that. If you need them,
		// set all to true. They are excluded by default.
		v = map[i].nodeValue;
		if ( all || ( v && v !== 'inherit' ) ) {
			attrs[ map[i].nodeName ] = v;
		}
	}

	return attrs;
};

/**
 * @class jQuery
 * @mixins jQuery.plugin.getAttrs
 */
