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
		 */
		Vue.directive( 'i18n-html', function ( el, binding ) {
			// If v-i18n-html:foo was used, binding.arg = 'foo'
			// If v-i18n-html="'foo'" was used, binding.value = 'foo'
			var messageKey = binding.arg || binding.value;
			// eslint-disable-next-line mediawiki/msg-doc
			el.innerHTML = mw.message( messageKey ).parse();
		} );
	}
};
