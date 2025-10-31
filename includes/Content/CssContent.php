<?php
/**
 * @license GPL-2.0-or-later
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
