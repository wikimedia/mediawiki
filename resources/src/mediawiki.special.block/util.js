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
	 * @param {string} duration
	 * @return {string}
	 */
	formatTimestamp: function ( timestamp, duration ) {
		if ( mw.util.isInfinity( duration ) ) {
			return mw.msg( 'infiniteblock' );
		}
		return new Date( timestamp ).toLocaleString();
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
