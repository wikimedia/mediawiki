/**
 * Return a function, that, as long as it continues to be invoked, will not
 * be triggered. The function will be called after it stops being called for
 * N milliseconds. If `immediate` is passed, trigger the function on the
 * leading edge, instead of the trailing.
 *
 * Ported from: http://underscorejs.org/underscore.js
 *
 * @param {Function} func Function to debounce
 * @param {number} [wait=0] Wait period in milliseconds
 * @param {boolean} [immediate] Trigger on leading edge
 * @return {Function} Debounced function
 */
function debounce( func, wait, immediate ) {
	let timeout;

	const debounced = ( ...args ) => {
		const context = this;
		const later = () => {
			timeout = null;

			if ( !immediate ) {
				func.apply( context, args );
			}
		};

		if ( immediate && !timeout ) {
			func.apply( context, args );
		}

		if ( !timeout || wait ) {
			clearTimeout( timeout );
			timeout = setTimeout( later, wait );
		}
	};

	debounced.cancel = () => {
		if ( timeout ) {
			clearTimeout( timeout );
			timeout = null;
		}
	};

	return debounced;
}

module.exports = debounce;
