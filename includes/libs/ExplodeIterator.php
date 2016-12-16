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

/**
 * An iterator which works exactly like:
 *
 * foreach ( explode( $delim, $s ) as $element ) {
 *    ...
 * }
 *
 * Except it doesn't use 193 byte per element
 */
class ExplodeIterator implements Iterator {
	// The subject string
	private $subject, $subjectLength;

	// The delimiter
	private $delim, $delimLength;

	// The position of the start of the line
	private $curPos;

	// The position after the end of the next delimiter
	private $endPos;

	/** @var string|false The current token */
	private $current;

	/**
	 * Construct a DelimIterator
	 * @param string $delim
	 * @param string $subject
	 */
	public function __construct( $delim, $subject ) {
		$this->subject = $subject;
		$this->delim = $delim;

		// Micro-optimisation (theoretical)
		$this->subjectLength = strlen( $subject );
		$this->delimLength = strlen( $delim );

		$this->rewind();
	}

	public function rewind() {
		$this->curPos = 0;
		$this->endPos = strpos( $this->subject, $this->delim );
		$this->refreshCurrent();
	}

	public function refreshCurrent() {
		if ( $this->curPos === false ) {
			$this->current = false;
		} elseif ( $this->curPos >= $this->subjectLength ) {
			$this->current = '';
		} elseif ( $this->endPos === false ) {
			$this->current = substr( $this->subject, $this->curPos );
		} else {
			$this->current = substr( $this->subject, $this->curPos, $this->endPos - $this->curPos );
		}
	}

	public function current() {
		return $this->current;
	}

	/**
	 * @return int|bool Current position or boolean false if invalid
	 */
	public function key() {
		return $this->curPos;
	}

	/**
	 * @return string
	 */
	public function next() {
		if ( $this->endPos === false ) {
			$this->curPos = false;
		} else {
			$this->curPos = $this->endPos + $this->delimLength;
			if ( $this->curPos >= $this->subjectLength ) {
				$this->endPos = false;
			} else {
				$this->endPos = strpos( $this->subject, $this->delim, $this->curPos );
			}
		}
		$this->refreshCurrent();

		return $this->current;
	}

	/**
	 * @return bool
	 */
	public function valid() {
		return $this->curPos !== false;
	}
}
