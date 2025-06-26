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

namespace MediaWiki\Content;

use MediaWiki\Title\Title;

/**
 * Content object for CSS pages.
 *
 * @newable
 * @since 1.21
 * @ingroup Content
 * @author Daniel Kinzler
 */
class CssContent extends TextContent {

	/**
	 * @var Title|null|false
	 */
	private $redirectTarget = false;

	/**
	 * @stable to call
	 * @param string $text CSS code.
	 * @param string $modelId the content content model
	 */
	public function __construct( $text, $modelId = CONTENT_MODEL_CSS ) {
		parent::__construct( $text, $modelId );
	}

	/**
	 * @param Title $target
	 * @return CssContent
	 */
	public function updateRedirect( Title $target ) {
		if ( !$this->isRedirect() ) {
			return $this;
		}

		// @phan-suppress-next-line PhanTypeMismatchReturnSuperType False positive
		return $this->getContentHandler()->makeRedirectContent( $target );
	}

	/**
	 * @return Title|null
	 */
	public function getRedirectTarget() {
		if ( $this->redirectTarget !== false ) {
			return $this->redirectTarget;
		}
		$this->redirectTarget = null;
		$text = $this->getText();
		if ( str_starts_with( $text, '/* #REDIRECT */' ) ) {
			// Extract the title from the url
			if ( preg_match( '/title=(.*?)&action=raw/', $text, $matches ) ) {
				$title = Title::newFromText( urldecode( $matches[1] ) );
				if ( $title ) {
					// Have a title, check that the current content equals what
					// the redirect content should be
					if ( $this->equals( $this->getContentHandler()->makeRedirectContent( $title ) ) ) {
						$this->redirectTarget = $title;
					}
				}
			}
		}

		return $this->redirectTarget;
	}

}
/** @deprecated class alias since 1.43 */
class_alias( CssContent::class, 'CssContent' );
