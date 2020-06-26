<?php
/**
 * Copyright © 2000, 2001 Geoffrey T. Dairiki <dairiki@dairiki.org>
 * You may copy this code freely under the conditions of the GPL.
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
 * @ingroup DifferenceEngine
 * @defgroup DifferenceEngine DifferenceEngine
 */

namespace MediaWiki\Diff;

/**
 * Stores, escapes and formats the results of word-level diff
 *
 * @internal
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
				$this->line .= "<ins{$this->insClass}>" . htmlspecialchars( $this->group ) . '</ins>';
			} elseif ( $this->tag == 'del' ) {
				$this->line .= "<del{$this->delClass}>" . htmlspecialchars( $this->group ) . '</del>';
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
			array_push( $this->lines, "\u{00A0}" );
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
			// FIXME: Don't use assert()
			// phpcs:ignore MediaWiki.Usage.ForbiddenFunctions.assert
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
