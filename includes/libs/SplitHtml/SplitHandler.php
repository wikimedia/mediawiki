<?php

namespace Wikimedia\SplitHtml;

use RemexHtml\Tokenizer\Attributes;
use RemexHtml\Tokenizer\Tokenizer;
use RemexHtml\Tokenizer\TokenHandler;

/**
 * @unstable Private to SplitHtml
 */
class SplitHandler implements TokenHandler {
	private $output = '';
	private $depth = 0;
	private $firstPart = false;
	private $outLength = 0;
	private $maxLength;

	public function __construct( $maxLength ) {
		$this->maxLength = $maxLength;
	}

	public function startDocument( Tokenizer $tokenizer, $fns, $fn ) {
	}

	public function endDocument( $pos ) {
	}

	public function error( $text, $pos ) {
	}

	public function getParts() {
		if ( $this->firstPart === false ) {
			return [ $this->output, '' ];
		} else {
			return [ $this->firstPart, $this->output ];
		}
	}

	public function characters( $text, $start, $length, $sourceStart, $sourceLength ) {
		$text = substr( $text, $start, $length );
		$chars = mb_strlen( $text );
		if ( $this->depth === 0 && $this->firstPart === false
			&& $chars + $this->outLength >= $this->maxLength
		) {
			$remainderLength = max( 0, $this->maxLength - $this->outLength );
			$this->firstPart = $this->output .
				htmlspecialchars( mb_substr( $text, 0, $remainderLength ) );
			$this->output = htmlspecialchars( mb_substr( $text, $remainderLength ) );
		} else {
			$this->outLength += $chars;
			$this->output .= htmlspecialchars( $text );
		}
	}

	public function startTag( $name, Attributes $attrs, $selfClose, $sourceStart, $sourceLength ) {
		if ( !$selfClose ) {
			$this->depth++;
		}
		$attrs = $attrs->getValues();
		$this->output .= "<$name";
		foreach ( $attrs as $name => $value ) {
			$this->output .= " $name=\"" . htmlspecialchars( $value ) . '"';
		}
		if ( $selfClose ) {
			$this->output .= ' /';
		}
		$this->output .= '>';
	}

	public function endTag( $name, $sourceStart, $sourceLength ) {
		$this->depth--;
		$this->output .= "</$name>";
	}

	public function doctype( $name, $public, $system, $quirks, $sourceStart, $sourceLength ) {
	}

	public function comment( $text, $sourceStart, $sourceLength ) {
	}
}
