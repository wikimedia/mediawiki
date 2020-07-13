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
 * Abort the web request with a custom HTML string that will represent
 * the entire response.
 *
 * This is not caught anywhere in MediaWiki code. It is handled through PHP's
 * exception handler, which calls MWExceptionHandler::report.
 *
 * Unlike MWException, this will not provide error IDs or stack traces.
 * It is intended for early installation and configuration problems where
 * the exception is all the site administrator needs to know.
 *
 * @newable
 * @stable to extend
 * @since 1.7
 * @ingroup Exception
 */
class FatalError extends MWException {

	/**
	 * Replace our usual detailed HTML response for uncaught exceptions,
	 * with just the bare message as HTML.
	 *
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
