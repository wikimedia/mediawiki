<?php
/**
 * Helps EditPage build textboxes
 *
 * (C) Copyright 2017 Kunal Mehta <legoktm@member.fsf.org>
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
 */

namespace MediaWiki\EditPage;

use MediaWiki\MediaWikiServices;
use Sanitizer;
use Title;
use User;

/**
 * Helps EditPage build textboxes
 *
 * @since 1.31
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
			$wikitext .= "\n";
			return $wikitext;
		}
		return $wikitext;
	}

	/**
	 * @param string[] $classes
	 * @param mixed[] $attribs
	 * @return mixed[]
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
	 * @param Title $title
	 * @return string[]
	 */
	public function getTextboxProtectionCSSClasses( Title $title ) {
		$classes = []; // Textarea CSS
		if ( $title->isProtected( 'edit' ) &&
			MediaWikiServices::getInstance()->getPermissionManager()
				->getNamespaceRestrictionLevels( $title->getNamespace() ) !== [ '' ]
		) {
			# Is the title semi-protected?
			if ( $title->isSemiProtected() ) {
				$classes[] = 'mw-textarea-sprotected';
			} else {
				# Then it must be protected based on static groups (regular)
				$classes[] = 'mw-textarea-protected';
			}
			# Is the title cascade-protected?
			if ( $title->isCascadeProtected() ) {
				$classes[] = 'mw-textarea-cprotected';
			}
		}

		return $classes;
	}

	/**
	 * @param string $name
	 * @param mixed[] $customAttribs
	 * @param User $user
	 * @param Title $title
	 * @return mixed[]
	 */
	public function buildTextboxAttribs( $name, array $customAttribs, User $user, Title $title ) {
		$attribs = $customAttribs + [
				'accesskey' => ',',
				'id' => $name,
				'cols' => 80,
				'rows' => 25,
				// Avoid PHP notices when appending preferences
				// (appending allows customAttribs['style'] to still work).
				'style' => ''
			];

		// The following classes can be used here:
		// * mw-editfont-monospace
		// * mw-editfont-sans-serif
		// * mw-editfont-serif
		$class = 'mw-editfont-' . $user->getOption( 'editfont' );

		if ( isset( $attribs['class'] ) ) {
			if ( is_string( $attribs['class'] ) ) {
				$attribs['class'] .= ' ' . $class;
			} elseif ( is_array( $attribs['class'] ) ) {
				$attribs['class'][] = $class;
			}
		} else {
			$attribs['class'] = $class;
		}

		$pageLang = $title->getPageLanguage();
		$attribs['lang'] = $pageLang->getHtmlCode();
		$attribs['dir'] = $pageLang->getDir();

		return $attribs;
	}

}
