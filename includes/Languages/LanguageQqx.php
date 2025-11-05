<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\Language;

/**
 * Dummy language that returns the message names.
 *
 * For all translated messages, this returns a special value handled in Message::format()
 * to display the message key (and fallback keys) and the parameters passed to the message.
 * This does not affect untranslated messages.
 *
 * NOTE: It returns a valid title, because there are some poorly written
 * extensions that assume the contents of some messages are valid.
 *
 * @ingroup Languages
 * @deprecated since 1.41. The overridden method is deprecated. The feature has been reimplemented
 *   in MessageCache. Callers doing "new LanguageQqx" should use
 *   $languageFactory->getLanguage( 'qqx' ) to get a Language object with its code set to qqx.
 */
class LanguageQqx extends Language {
	/** @inheritDoc */
	public function getMessage( $key ) {
		// Special value replaced in Message::format()
		return '($*)';
	}
}
