/*!
 * i18n plugin for Vue that connects to MediaWiki's i18n system.
 *
 * Based on https://github.com/santhoshtr/vue-banana-i18n , but modified to connect to mw.message()
 * instead of banana-i18n.
 */

module.exports = {
	install: function ( Vue ) {
		/**
		 * @class Vue
		 */

		/**
		 * Adds an `$i18n()` instance method that can be used in all components. This method is a
		 * proxy to mw.message.
		 *
		 * Usage:
		 *     `<p>{{ $i18n( 'my-message-keys', param1, param2 ) }}</p>`
		 *     or
		 *     `<p>{{ $i18n( 'my-message-keys' ).params( [ param1, param2 ] ) }}</p>`
		 *
		 * Note that this method only works for messages that return text. For messages that
		 * need to be parsed to HTML, use the v-i18n-html directive.
		 *
		 * @param {string} key Key of message to get
		 * @param {...Mixed} parameters Values for $N replacements
		 * @return {mw.Message}
		 */
		Vue.prototype.$i18n = function () {
			return mw.message.apply( mw, arguments );
		};

		/*
		 * Add a custom v-i18n-html directive. This is used to inject parsed i18n message contents.
		 *
		 * <div v-i18n-html:my-message-key />
		 *     Parses the my-message-key message and injects the parsed HTML into the div.
		 *     Equivalent to v-html="mw.message( 'my-message-key' ).parse()"
		 *
		 * <div v-i18n-html="msgKey" />
		 *     Looks in the msgKey variable for the message name, and parses that message.
		 *     Equivalent to v-html="mw.message( msgKey ).parse()"
		 *
		 * <div v-i18n-html="'my-message-key'" />
		 *     Parses the message named my-message-key. Note the nested quotes!
		 *     Equivalent to v-html="mw.message( 'my-message-key' ).parse()"
		 *
		 * <div v-i18n-html:my-message-key="[ param1, param2 ]" />
		 *     Parses the my-message-key message, passing parameters param1 and param2
		 *     Equivalent to v-html="mw.message( 'my-message-key' ).params( [ param1, param2 ] ).parse()"
		 *
		 * <div v-i18n-html:my-message-key="[ param1 ]" />
		 *     Parses the my-message-key message, passing only one parameter. Note the array brackets!
		 *     Equivalent to v-html="mw.message( 'my-message-key' ).params( [ param1 ] ).parse()"
		 *
		 * <div v-i18n-html="$i18n( 'my-message-key' ).params( [ param1, param2 ] )" />
		 *     If a mw.Message object is passed in, .parse() will be called on it.
		 *     Equivalent to v-html="mw.message( 'my-message-key' ).params( [ param1, param2 ] ).parse()"
		 *     This is only recommended for when you have a Message object coming from a
		 *     computed property or a method, or for when you can't use any of the other calling
		 *     styles (e.g. because the message key is dynamic, or contains unusual characters).
		 *     Note that you can use mw.message() in computed properties, but in template attributes
		 *     you have to use $i18n() instead as demonstrated above.
		 */
		Vue.directive( 'i18n-html', function ( el, binding ) {
			var message;
			/* eslint-disable mediawiki/msg-doc */
			if ( Array.isArray( binding.value ) ) {
				if ( binding.arg === undefined ) {
					// v-i18n-html="[ ...params ]" (error)
					throw new Error( 'v-i18n-html used with parameter array but without message key' );
				}
				// v-i18n-html:messageKey="[ ...params ]"
				message = mw.message( binding.arg ).params( binding.value );
			} else if ( binding.value instanceof mw.Message ) {
				// v-i18n-html="mw.message( '...' ).params( [ ... ] )"
				message = binding.value;
			} else {
				// v-i18n-html:foo or v-i18n-html="'foo'"
				message = mw.message( binding.arg || binding.value );
			}
			/* eslint-enable mediawiki/msg-doc */

			el.innerHTML = message.parse();
		} );
	}
};
