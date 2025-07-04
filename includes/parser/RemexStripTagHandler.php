<?php

namespace MediaWiki\Parser;

use Wikimedia\RemexHtml\Tokenizer\Attributes;
use Wikimedia\RemexHtml\Tokenizer\NullTokenHandler;

/**
 * Helper class for Sanitizer::stripAllTags().
 * @internal
 */
class RemexStripTagHandler extends NullTokenHandler {
	/** @var bool */
	private $insideNonVisibleTag = false;
	/** @var string */
	private $text = '';

	/** @inheritDoc */
	public function getResult() {
		return $this->text;
	}

	/** @inheritDoc */
	public function characters( $text, $start, $length, $sourceStart, $sourceLength ) {
		if ( !$this->insideNonVisibleTag ) {
			$this->text .= substr( $text, $start, $length );
		}
	}

	/** @inheritDoc */
	public function startTag( $name, Attributes $attrs, $selfClose, $sourceStart, $sourceLength ) {
		if ( $this->isNonVisibleTag( $name ) ) {
			$this->insideNonVisibleTag = true;
		}
		// Inject whitespace for typical block-level tags to
		// prevent merging unrelated<br>words.
		if ( $this->isBlockLevelTag( $name ) ) {
			$this->text .= ' ';
		}
	}

	/** @inheritDoc */
	public function endTag( $name, $sourceStart, $sourceLength ) {
		if ( $this->isNonVisibleTag( $name ) ) {
			$this->insideNonVisibleTag = false;
		}
		// Inject whitespace for typical block-level tags to
		// prevent merging unrelated<br>words.
		if ( $this->isBlockLevelTag( $name ) ) {
			$this->text .= ' ';
		}
	}

	// Per https://developer.mozilla.org/en-US/docs/Web/HTML/Block-level_elements
	// retrieved on sept 12, 2018. <br> is not block level but was added anyways.
	// The following is a complete list of all HTML block level elements
	// (although "block-level" is not technically defined for elements that are
	// new in HTML5).
	// Structured as tag => true to allow O(1) membership test.
	private const BLOCK_LEVEL_TAGS = [
		'address' => true,
		'article' => true,
		'aside' => true,
		'blockquote' => true,
		'br' => true,
		'canvas' => true,
		'dd' => true,
		'div' => true,
		'dl' => true,
		'dt' => true,
		'fieldset' => true,
		'figcaption' => true,
		'figure' => true,
		'footer' => true,
		'form' => true,
		'h1' => true,
		'h2' => true,
		'h3' => true,
		'h4' => true,
		'h5' => true,
		'h6' => true,
		'header' => true,
		'hgroup' => true,
		'hr' => true,
		'li' => true,
		'main' => true,
		'nav' => true,
		'noscript' => true,
		'ol' => true,
		'output' => true,
		'p' => true,
		'pre' => true,
		'section' => true,
		'table' => true,
		'td' => true,
		'tfoot' => true,
		'th' => true,
		'tr' => true,
		'ul' => true,
		'video' => true,
	];

	/**
	 * Detect block level tags. Of course css can make anything a block
	 * level tag, but this is still better than nothing.
	 *
	 * @param string $tagName HTML tag name
	 * @return bool True when tag is an html block level element
	 */
	private function isBlockLevelTag( $tagName ) {
		$key = strtolower( trim( $tagName ) );
		return isset( self::BLOCK_LEVEL_TAGS[$key] );
	}

	private const NON_VISIBLE_TAGS = [
		'style' => true,
		'script' => true,
	];

	/**
	 * Detect block tags which by default are non-visible items.
	 * Of course css can make anything non-visible,
	 * but this is still better than nothing.
	 *
	 * We use this primarily to hide TemplateStyles
	 * from output in notifications/emails etc.
	 *
	 * @param string $tagName HTML tag name
	 * @return bool True when tag is a html element which should be filtered out
	 */
	private function isNonVisibleTag( $tagName ) {
		$key = strtolower( trim( $tagName ) );
		return isset( self::NON_VISIBLE_TAGS[$key] );
	}

}
