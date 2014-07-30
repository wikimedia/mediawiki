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
 * Exception class which takes an HTML error message, and does not
 * produce a backtrace. Replacement for OutputPage::fatalError().
 *
 * @since 1.7
 * @ingroup Exception
 * @deprecated since 1.24
 */
class FatalError extends MWException {

	/**
	 * @param string $message
	 * @deprecated since 1.24 Use trigger_error for actual fatal errors
	 */
	public function __construct( $message ) {
		wfDeprecated( __CLASS__, '1.24' );
		parent::__construct( $message );
	}

	/**
	 * @return string
	 */
	public function getHTML() {
		return $this->getMessage();
	}

	/**
	 * @return string
	 */
	public function getText() {
		return $this->getMessage();
	}
}
