<?php
/**
 * Class that defers a slow string generation until the string is actually needed.
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
 */

/**
 * @since 1.25
 */
class DeferredStringifier {
	/** @var callable Callback used for result string generation */
	private $callback;

	/** @var array */
	private $params;

	/** @var string */
	private $result;

	/**
	 * @param callable $callback Callback that gets called by __toString
	 * @param mixed $param,... Parameters to the callback
	 */
	public function __construct( $callback /*...*/ ) {
		$this->params = func_get_args();
		array_shift( $this->params );
		$this->callback = $callback;
	}

	/**
	 * Get the string generated from the callback
	 *
	 * @return string
	 */
	public function __toString() {
		if ( $this->result === null ) {
			$this->result = call_user_func_array( $this->callback, $this->params );
		}
		return $this->result;
	}
}
