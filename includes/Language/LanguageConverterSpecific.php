<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

use MediaWiki\Language\LanguageConverter;
use MediaWiki\Title\Title;

/**
 * A class that extends LanguageConverter with specific behaviour.
 *
 * @ingroup Language
 */
abstract class LanguageConverterSpecific extends LanguageConverter {
	/**
	 * A function wrapper:
	 *   - if there is no selected variant, leave the link
	 *     names as they were
	 *   - do not try to find variants for usernames
	 *
	 * @param string &$link
	 * @param Title &$nt
	 * @param bool $ignoreOtherCond
	 */
	public function findVariantLink( &$link, &$nt, $ignoreOtherCond = false ) {
		// check for user namespace
		if ( is_object( $nt ) ) {
			$ns = $nt->getNamespace();
			if ( $ns === NS_USER || $ns === NS_USER_TALK ) {
				return;
			}
		}

		$oldlink = $link;
		parent::findVariantLink( $link, $nt, $ignoreOtherCond );
		if ( $this->getPreferredVariant() == $this->getMainCode() ) {
			$link = $oldlink;
		}
	}
}
