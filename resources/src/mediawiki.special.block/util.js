const util = {
	/**
	 * Get the message for the given block flag
	 *
	 * @param {string} flag
	 * @return {string}
	 */
	getBlockFlagMessage: function ( flag ) {
		// Potential messages:
		// * block-log-flags-anononly
		// * block-log-flags-nocreate
		// * block-log-flags-noautoblock
		// * block-log-flags-noemail
		// * block-log-flags-nousertalk
		// * block-log-flags-angry-autoblock
		// * block-log-flags-hiddenname
		return mw.message( 'block-log-flags-' + flag ).text();
	},
	/**
	 * Format a timestamp
	 *
	 * @param {string} timestamp
	 * @return {string}
	 */
	formatTimestamp: function ( timestamp ) {
		if ( !timestamp || mw.util.isInfinity( timestamp ) ) {
			return mw.msg( 'infiniteblock' );
		}
		const date = new Date( timestamp );
		return date.toLocaleString( undefined, {
			timeZone: 'UTC',
			timeZoneName: 'short',
			year: 'numeric',
			month: 'short',
			day: 'numeric',
			hour: 'numeric',
			minute: 'numeric',
			hour12: false,
			format: [ 'time', 'date' ]
		} );
	},
	/**
	 * Get the message for a given block action
	 *
	 * @param {string} action
	 * @return {string}
	 */
	getBlockActionMessage: function ( action ) {
		// Potential messages:
		// * log-action-filter-block-block
		// * log-action-filter-block-reblock
		// * log-action-filter-block-unblock
		return mw.message( 'log-action-filter-block-' + action ).text();
	}
};

module.exports = util;
