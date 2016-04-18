<?php

namespace MediaWiki\Diff;

/**
 * @todo document
 * @private
 * @ingroup DifferenceEngine
 */
class WordAccumulator {
	public $insClass = ' class="diffchange diffchange-inline"';
	public $delClass = ' class="diffchange diffchange-inline"';

	private $lines = [];
	private $line = '';
	private $group = '';
	private $tag = '';

	/**
	 * @param string $new_tag
	 */
	private function flushGroup( $new_tag ) {
		if ( $this->group !== '' ) {
			if ( $this->tag == 'ins' ) {
				$this->line .= "<ins{$this->insClass}>" .
							   htmlspecialchars( $this->group ) . '</ins>';
			} elseif ( $this->tag == 'del' ) {
				$this->line .= "<del{$this->delClass}>" .
							   htmlspecialchars( $this->group ) . '</del>';
			} else {
				$this->line .= htmlspecialchars( $this->group );
			}
		}
		$this->group = '';
		$this->tag = $new_tag;
	}

	/**
	 * @param string $new_tag
	 */
	private function flushLine( $new_tag ) {
		$this->flushGroup( $new_tag );
		if ( $this->line != '' ) {
			array_push( $this->lines, $this->line );
		} else {
			# make empty lines visible by inserting an NBSP
			array_push( $this->lines, '&#160;' );
		}
		$this->line = '';
	}

	/**
	 * @param string[] $words
	 * @param string $tag
	 */
	public function addWords( $words, $tag = '' ) {
		if ( $tag != $this->tag ) {
			$this->flushGroup( $tag );
		}

		foreach ( $words as $word ) {
			// new-line should only come as first char of word.
			if ( $word == '' ) {
				continue;
			}
			if ( $word[0] == "\n" ) {
				$this->flushLine( $tag );
				$word = substr( $word, 1 );
			}
			assert( !strstr( $word, "\n" ) );
			$this->group .= $word;
		}
	}

	/**
	 * @return string[]
	 */
	public function getLines() {
		$this->flushLine( '~done' );

		return $this->lines;
	}
}
