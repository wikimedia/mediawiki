<?php
/**
 * For all translated messages, this returns the name of the message bracketed.
 * This does not affect untranslated messages.
 *
 * NOTE: It returns a valid title, because there are some poorly written
 * extentions that assume the contents of some messages are valid.
 *
 * @ingroup Language
 */
class LanguageQqx extends Language {
	function getMessage( $key ) {
		return "($key)";
	}
}
