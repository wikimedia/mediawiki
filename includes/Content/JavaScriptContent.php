<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Content;

use MediaWiki\Title\Title;

/**
 * Content for JavaScript pages.
 *
 * @newable
 * @since 1.21
 * @ingroup Content
 * @author Daniel Kinzler
 */
class JavaScriptContent extends TextContent {

	/**
	 * @var Title|null|false
	 */
	private $redirectTarget = false;

	/**
	 * @stable to call
	 * @param string $text JavaScript code.
	 * @param string $modelId the content model name
	 */
	public function __construct( $text, $modelId = CONTENT_MODEL_JAVASCRIPT ) {
		parent::__construct( $text, $modelId );
	}

	/**
	 * If this page is a redirect, return the content
	 * if it should redirect to $target instead
	 *
	 * @param Title $target
	 * @return JavaScriptContent
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
			// Compatiblity with pages created by MW 1.41 and earlier:
			// Older redirects use an over-escaped \u0026 instead of a literal ampersand (T107289)
			$text = str_replace( '\u0026', '&', $text );
			// Extract the title from the url
			if ( preg_match( '/title=(.*?)&action=raw/', $text, $matches ) ) {
				$title = Title::newFromText( urldecode( $matches[1] ) );
				if ( $title ) {
					// Have a title, check that the current content equals what
					// the redirect content should be
					$expected = $this->getContentHandler()->makeRedirectContent( $title );
					'@phan-var JavaScriptContent $expected';
					if ( $expected->getText() === $text ) {
						$this->redirectTarget = $title;
					}
				}
			}
		}

		return $this->redirectTarget;
	}

}
/** @deprecated class alias since 1.43 */
class_alias( JavaScriptContent::class, 'JavaScriptContent' );
