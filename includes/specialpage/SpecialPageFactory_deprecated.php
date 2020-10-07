<?php
/**
 * Factory for handling the special page list and generating SpecialPage objects.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup SpecialPage
 * @defgroup SpecialPage SpecialPage
 */

use MediaWiki\Linker\LinkRenderer;
use MediaWiki\MediaWikiServices;

// phpcs:disable MediaWiki.Files.ClassMatchesFilename.NotMatch
/**
 * Wrapper for backward compatibility for old callers that used static methods.
 *
 * @deprecated since 1.32, use the SpecialPageFactory service instead
 */
class SpecialPageFactory {
	public static function getNames() : array {
		wfDeprecated( __METHOD__, '1.32' );
		return MediaWikiServices::getInstance()->getSpecialPageFactory()->getNames();
	}

	public static function resolveAlias( $alias ) : array {
		wfDeprecated( __METHOD__, '1.32' );
		return MediaWikiServices::getInstance()->getSpecialPageFactory()->resolveAlias( $alias );
	}

	public static function exists( $name ) {
		wfDeprecated( __METHOD__, '1.32' );
		return MediaWikiServices::getInstance()->getSpecialPageFactory()->exists( $name );
	}

	public static function getPage( $name ) {
		wfDeprecated( __METHOD__, '1.32' );
		return MediaWikiServices::getInstance()->getSpecialPageFactory()->getPage( $name );
	}

	public static function getUsablePages( User $user = null ) : array {
		wfDeprecated( __METHOD__, '1.32' );
		global $wgUser;
		$user = $user ?? $wgUser;
		return MediaWikiServices::getInstance()->getSpecialPageFactory()->getUsablePages( $user );
	}

	public static function getRegularPages() : array {
		wfDeprecated( __METHOD__, '1.32' );
		return MediaWikiServices::getInstance()->getSpecialPageFactory()->getRegularPages();
	}

	public static function getRestrictedPages( User $user = null ) : array {
		wfDeprecated( __METHOD__, '1.32' );
		global $wgUser;
		$user = $user ?? $wgUser;
		return MediaWikiServices::getInstance()->getSpecialPageFactory()->getRestrictedPages( $user );
	}

	public static function executePath( Title &$title, IContextSource &$context, $including = false,
		LinkRenderer $linkRenderer = null
	) {
		wfDeprecated( __METHOD__, '1.32' );
		return MediaWikiServices::getInstance()->getSpecialPageFactory()
			->executePath( $title, $context, $including, $linkRenderer );
	}

	public static function capturePath(
		Title $title, IContextSource $context, LinkRenderer $linkRenderer = null
	) {
		wfDeprecated( __METHOD__, '1.32' );
		return MediaWikiServices::getInstance()->getSpecialPageFactory()
			->capturePath( $title, $context, $linkRenderer );
	}

	public static function getLocalNameFor( $name, $subpage = false ) {
		wfDeprecated( __METHOD__, '1.32' );
		return MediaWikiServices::getInstance()->getSpecialPageFactory()
			->getLocalNameFor( $name, $subpage );
	}

	public static function getTitleForAlias( $alias ) {
		wfDeprecated( __METHOD__, '1.32' );
		return MediaWikiServices::getInstance()->getSpecialPageFactory()
			->getTitleForAlias( $alias );
	}

	/**
	 * No-op since 1.32, call overrideMwServices() instead
	 */
	public static function resetList() {
		wfDeprecated( __METHOD__, '1.32' );
	}
}
