<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Language;

use MessageLocalizer;
use Wikimedia\Bcp47Code\Bcp47Code;

/**
 * Interface supporting message localization in MediaWiki
 *
 * @since 1.42
 * @ingroup Language
 */
interface LocalizationContext extends MessageLocalizer {

	/**
	 * Returns the target language for UI localization.
	 * @return Bcp47Code
	 */
	public function getLanguageCode();

}
