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
 * @ingroup Parser
 */

/**
 * Stack class to help Preprocessor::preprocessToObj()
 * @ingroup Parser
 */
class PPDStack {
	/** @var PPDStackElement[] */
	public $stack;
	public $rootAccum;
	/** @var string|array */
	public $accum;

	/**
	 * @var PPDStackElement|false
	 */
	public $top;
	public $out;
	public $elementClass = PPDStackElement::class;

	public static $false = false;

	public function __construct() {
		$this->stack = [];
		$this->top = false;
		$this->rootAccum = '';
		$this->accum =& $this->rootAccum;
	}

	/**
	 * @return int
	 */
	public function count() {
		return count( $this->stack );
	}

	public function &getAccum() {
		return $this->accum;
	}

	/**
	 * @return bool|PPDPart
	 */
	public function getCurrentPart() {
		if ( $this->top === false ) {
			return false;
		} else {
			return $this->top->getCurrentPart();
		}
	}

	public function push( $data ) {
		if ( $data instanceof $this->elementClass ) {
			$this->stack[] = $data;
		} else {
			$class = $this->elementClass;
			$this->stack[] = new $class( $data );
		}
		$this->top = $this->stack[count( $this->stack ) - 1];
		$this->accum =& $this->top->getAccum();
	}

	public function pop() {
		if ( $this->stack === [] ) {
			throw new MWException( __METHOD__ . ': no elements remaining' );
		}
		$temp = array_pop( $this->stack );

		if ( count( $this->stack ) ) {
			$this->top = $this->stack[count( $this->stack ) - 1];
			$this->accum =& $this->top->getAccum();
		} else {
			$this->top = self::$false;
			$this->accum =& $this->rootAccum;
		}
		return $temp;
	}

	public function addPart( $s = '' ) {
		$this->top->addPart( $s );
		$this->accum =& $this->top->getAccum();
	}

	/**
	 * @return array
	 */
	public function getFlags() {
		if ( $this->stack === [] ) {
			return [
				'findEquals' => false,
				'findPipe' => false,
				'inHeading' => false,
			];
		} else {
			return $this->top->getFlags();
		}
	}
}
