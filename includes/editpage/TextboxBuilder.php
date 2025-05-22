<?php
/**
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
 */

namespace MediaWiki\EditPage;

use MediaWiki\Html\Html;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\PageIdentity;
use MediaWiki\Parser\Sanitizer;
use MediaWiki\Title\Title;
use MediaWiki\User\UserIdentity;

/**
 * Helps EditPage build textboxes
 *
 * @newable
 * @since 1.31
 * @author Kunal Mehta <legoktm@debian.org>
 */
class TextboxBuilder {

	/**
	 * @param string $wikitext
	 * @return string
	 */
	public function addNewLineAtEnd( $wikitext ) {
		if ( strval( $wikitext ) !== '' ) {
			// Ensure there's a newline at the end, otherwise adding lines
			// is awkward.
			// But don't add a newline if the text is empty, or Firefox in XHTML
			// mode will show an extra newline. A bit annoying.
			return $wikitext . "\n";
		}
		return $wikitext;
	}

	/**
	 * @param string[] $classes
	 * @param mixed[] $attribs
	 * @return mixed[]
	 * @deprecated since 1.44, use Html::addClass() instead
	 */
	public function mergeClassesIntoAttributes( array $classes, array $attribs ) {
		if ( $classes === [] ) {
			return $attribs;
		}

		return Sanitizer::mergeAttributes(
			$attribs,
			[ 'class' => implode( ' ', $classes ) ]
		);
	}

	/**
	 * @param PageIdentity $page
	 * @return string[]
	 */
	public function getTextboxProtectionCSSClasses( PageIdentity $page ) {
		$classes = []; // Textarea CSS
		$services = MediaWikiServices::getInstance();
		if ( $services->getRestrictionStore()->isProtected( $page, 'edit' ) &&
			$services->getPermissionManager()
				->getNamespaceRestrictionLevels( $page->getNamespace() ) !== [ '' ]
		) {
			# Is the title semi-protected?
			if ( $services->getRestrictionStore()->isSemiProtected( $page ) ) {
				$classes[] = 'mw-textarea-sprotected';
			} else {
				# Then it must be protected based on static groups (regular)
				$classes[] = 'mw-textarea-protected';
			}
			# Is the title cascade-protected?
			if ( $services->getRestrictionStore()->isCascadeProtected( $page ) ) {
				$classes[] = 'mw-textarea-cprotected';
			}
		}

		return $classes;
	}

	/**
	 * @param string $name
	 * @param mixed[] $customAttribs
	 * @param UserIdentity $user
	 * @param PageIdentity $page
	 * @return mixed[]
	 */
	public function buildTextboxAttribs(
		$name, array $customAttribs, UserIdentity $user, PageIdentity $page
	) {
		$attribs = $customAttribs + [
			'accesskey' => ',',
			'id' => $name,
			'cols' => 80,
			'rows' => 25,
		];

		// The following classes can be used here:
		// * mw-editfont-monospace
		// * mw-editfont-sans-serif
		// * mw-editfont-serif
		$userOptionsLookup = MediaWikiServices::getInstance()->getUserOptionsLookup();
		$class = 'mw-editfont-' . $userOptionsLookup->getOption( $user, 'editfont' );
		Html::addClass( $attribs['class'], $class );

		$title = Title::newFromPageIdentity( $page );
		$pageLang = $title->getPageLanguage();
		$attribs['lang'] = $pageLang->getHtmlCode();
		$attribs['dir'] = $pageLang->getDir();

		return $attribs;
	}

}
