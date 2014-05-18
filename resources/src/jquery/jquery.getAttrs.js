/**
 * @class jQuery.plugin.getAttrs
 */

/**
 * Get the attributes of an element directy as a plain object.
 *
 * If there are more elements in the collection, like most jQuery get/read methods,
 * this method will use the first element in the collection.
 *
 * In IE6, the `attributes` map of a node includes *all* allowed attributes
 * for an element (including those not set). Those will have values like
 * `undefined`, `null`, `0`, `false`, `""` or `"inherit"`.
 *
 * However there may be attributes genuinely set to one of those values, and there
 * is no way to distinguish between attributes set to that and those not set and
 * it being the default. If you need them, set `all` to `true`. They are filtered out
 * by default.
 *
 * @param {boolean} [all=false]
 * @return {Object}
 */
jQuery.fn.getAttrs = function ( all ) {
	var map = this[0].attributes,
		attrs = {},
		len = map.length,
		i, v;

	for ( i = 0; i < len; i++ ) {
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
