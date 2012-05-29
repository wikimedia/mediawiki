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
	var $languageEn;

	/**
	 * @param $key string
	 * @return string
	 */
	function getMessage( $key ) {
		if ( !$this->languageEn ) {
			$this->languageEn = Language::factory( 'en' );
		}
		$message = '(' . $key;
		$messageEn = $this->languageEn->getMessage( $key );
		$numParams = 1;
		while ( strpos( $messageEn, '$' . $numParams ) !== false ) {
			if ( $numParams === 1 ) {
				$message .= ': $1';
			} else {
				$message .= ', $' . $numParams;
			}
			$numParams++;
		}
		return $message . ')';
	}
}
