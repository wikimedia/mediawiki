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
		// Do nothing.
	}
	function endTag( $name, $sourceStart, $sourceLength ) {
		// Do nothing.
	}
	function doctype( $name, $public, $system, $quirks, $sourceStart, $sourceLength ) {
		// Do nothing.
	}
	function comment( $text, $sourceStart, $sourceLength ) {
		// Do nothing.
	}
}
