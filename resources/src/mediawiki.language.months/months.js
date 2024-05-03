/*
 * Transfer of month names from messages into mw.language.
 *
 * Loading this module also ensures the availability of appropriate messages via mw.msg.
 */
( function () {
	const
		monthMessages = [
			'january', 'february', 'march', 'april',
			'may_long', 'june', 'july', 'august',
			'september', 'october', 'november', 'december'
		],
		monthGenMessages = [
			'january-gen', 'february-gen', 'march-gen', 'april-gen',
			'may-gen', 'june-gen', 'july-gen', 'august-gen',
			'september-gen', 'october-gen', 'november-gen', 'december-gen'
		],
		monthAbbrevMessages = [
			'jan', 'feb', 'mar', 'apr',
			'may', 'jun', 'jul', 'aug',
			'sep', 'oct', 'nov', 'dec'
		];

	// Function suitable for passing to Array.prototype.map
	// Can't use mw.msg directly because Array.prototype.map passes element index as second argument
	function mwMsgMapper( key ) {
		// eslint-disable-next-line mediawiki/msg-doc
		return mw.msg( key );
	}

	/**
	 * @typedef {Object} mw.language~Months
	 * @property {Array} names Month names (in nominative case in languages which have the
	 *   distinction), zero-indexed
	 * @property {Array} genitive Month names in genitive case, zero-indexed
	 * @property {Array} abbrev Three-letter-long abbreviated month names, zero-indexed
	 * @property {Object} key Object with three keys like the above, containing zero-indexed arrays
	 *   of message keys for appropriate messages which can be passed to mw.msg
	 */

	/**
	 * Information about month names in current UI language.
	 *
	 * @type {Months}
	 */
	mw.language.months = {
		keys: {
			names: monthMessages,
			genitive: monthGenMessages,
			abbrev: monthAbbrevMessages
		},
		names: monthMessages.map( mwMsgMapper ),
		genitive: monthGenMessages.map( mwMsgMapper ),
		abbrev: monthAbbrevMessages.map( mwMsgMapper )
	};

}() );
