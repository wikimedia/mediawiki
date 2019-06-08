<?php

use RemexHtml\Tokenizer\Attributes;
use RemexHtml\Tokenizer\TokenHandler;
use RemexHtml\Tokenizer\Tokenizer;

/**
 * @internal
 */
class RemexStripTagHandler implements TokenHandler {
	private $text = '';
	public function getResult() {
		return $this->text;
	}

	function startDocument( Tokenizer $t, $fns, $fn ) {
		// Do nothing.
	}
	function endDocument( $pos ) {
		// Do nothing.
	}
	function error( $text, $pos ) {
		// Do nothing.
	}
	function characters( $text, $start, $length, $sourceStart, $sourceLength ) {
		$this->text .= substr( $text, $start, $length );
	}
	function startTag( $name, Attributes $attrs, $selfClose, $sourceStart, $sourceLength ) {
		// Inject whitespace for typical block-level tags to
		// prevent merging unrelated<br>words.
		if ( $this->isBlockLevelTag( $name ) ) {
			$this->text .= ' ';
		}
	}
	function endTag( $name, $sourceStart, $sourceLength ) {
		// Inject whitespace for typical block-level tags to
		// prevent merging unrelated<br>words.
		if ( $this->isBlockLevelTag( $name ) ) {
			$this->text .= ' ';
		}
	}
	function doctype( $name, $public, $system, $quirks, $sourceStart, $sourceLength ) {
		// Do nothing.
	}
	function comment( $text, $sourceStart, $sourceLength ) {
		// Do nothing.
	}

	// Per https://developer.mozilla.org/en-US/docs/Web/HTML/Block-level_elements
	// retrieved on sept 12, 2018. <br> is not block level but was added anyways.
	// The following is a complete list of all HTML block level elements
	// (although "block-level" is not technically defined for elements that are
	// new in HTML5).
	// Structured as tag => true to allow O(1) membership test.
	private static $BLOCK_LEVEL_TAGS = [
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
		return isset( self::$BLOCK_LEVEL_TAGS[$key] );
	}
}
