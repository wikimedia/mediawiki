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

	function startDocument( Tokenizer $t, $fns, $fn ) {}
	function endDocument( $pos ) {}
	function error( $text, $pos ) {}
	function characters( $text, $start, $length, $sourceStart, $sourceLength ) {
		$this->text .= substr( $text, $start, $length );
	}
	function startTag( $name, Attributes $attrs, $selfClose, $sourceStart, $sourceLength ) {}
	function endTag( $name, $sourceStart, $sourceLength ) {}
	function doctype( $name, $public, $system, $quirks, $sourceStart, $sourceLength ) {}
	function comment( $text, $sourceStart, $sourceLength ) {}
}
