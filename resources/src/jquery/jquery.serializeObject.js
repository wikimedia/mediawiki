/**
 * @class jQuery.plugin.serializeObject
 */

/**
 * Get form data as a plain object mapping form control names to their values.
 *
 * @return {Object}
 */
jQuery.fn.serializeObject = function () {
	var fields = this.serializeArray(), data = {}, field;
	while ( field = fields.pop() ) {
		data[field.name] = field.value;
	}
	return data;
};

/**
 * @class jQuery
 * @mixins jQuery.plugin.serializeObject
 */
