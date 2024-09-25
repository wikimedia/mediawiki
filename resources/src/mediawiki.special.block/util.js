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
		return new Date( timestamp ).toLocaleString();
	}
};

module.exports = util;
