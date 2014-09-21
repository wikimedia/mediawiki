/*
 * Transfer of month names from messages into mw.language.
 *
 * Loading this module also ensures the availability of appropriate messages via mw.msg.
 */
( function ( mw, $ ) {
	var
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

	// Function suitable for passing to jQuery.map
	// Can't use mw.msg directly because jQuery.map passes element index as second argument
	function mwMsgMapper( key ) {
		return mw.msg( key );
	}

	/**
	 * Information about month names in current UI language.
	 *
	 * Object keys:
	 *
	 * - `names`: array of month names (in nominative case in languages which have the distinction),
	 *   zero-indexed
	 * - `genitive`: array of month names in genitive case, zero-indexed
	 * - `abbrev`: array of three-letter-long abbreviated month names, zero-indexed
	 * - `keys`: object with three keys like the above, containing zero-indexed arrays of message keys
	 *   for appropriate messages which can be passed to mw.msg.
	 *
	 * @property
	 * @member mw.language
	 */
	mw.language.months = {
		keys: {
			names: monthMessages,
			genitive: monthGenMessages,
			abbrev: monthAbbrevMessages
		},
		names: $.map( monthMessages, mwMsgMapper ),
		genitive: $.map( monthGenMessages, mwMsgMapper ),
		abbrev: $.map( monthAbbrevMessages, mwMsgMapper )
	};

}( mediaWiki, jQuery ) );
