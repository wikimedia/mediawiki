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
 * This exception will be thrown when dieUsage is called to stop module execution.
 *
 * @ingroup API
 * @deprecated since 1.29, use ApiUsageException instead
 */
class UsageException extends MWException {

	private $mCodestr;

	/**
	 * @var null|array
	 */
	private $mExtraData;

	/**
	 * @param string $message
	 * @param string $codestr
	 * @param int $code
	 * @param array|null $extradata
	 */
	public function __construct( $message, $codestr, $code = 0, $extradata = null ) {
		parent::__construct( $message, $code );
		$this->mCodestr = $codestr;
		$this->mExtraData = $extradata;

		if ( !$this instanceof ApiUsageException ) {
			wfDeprecated( __METHOD__, '1.29' );
		}

		// This should never happen, so throw an exception about it that will
		// hopefully get logged with a backtrace (T138585)
		if ( !is_string( $codestr ) || $codestr === '' ) {
			throw new InvalidArgumentException( 'Invalid $codestr, was ' .
				( $codestr === '' ? 'empty string' : gettype( $codestr ) )
			);
		}
	}

	/**
	 * @return string
	 */
	public function getCodeString() {
		wfDeprecated( __METHOD__, '1.29' );
		return $this->mCodestr;
	}

	/**
	 * @return array
	 */
	public function getMessageArray() {
		wfDeprecated( __METHOD__, '1.29' );
		$result = [
			'code' => $this->mCodestr,
			'info' => $this->getMessage()
		];
		if ( is_array( $this->mExtraData ) ) {
			$result = array_merge( $result, $this->mExtraData );
		}

		return $result;
	}

	/**
	 * @return string
	 */
	public function __toString() {
		return "{$this->getCodeString()}: {$this->getMessage()}";
	}
}
