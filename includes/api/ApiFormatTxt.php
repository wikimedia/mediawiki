<?php
/**
 *
 *
 * Created on Oct 22, 2006
 *
 * Copyright © 2008 Roan Kattouw "<Firstname>.<Lastname>@gmail.com"
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
 * API Text output formatter
 * @deprecated since 1.24
 * @ingroup API
 */
class ApiFormatTxt extends ApiFormatBase {

	public function getMimeType() {
		// This looks like it should be text/plain, but IE7 is so
		// brain-damaged it tries to parse text/plain as HTML if it
		// contains HTML tags. Using MIME text/text works around this bug
		return 'text/text';
	}

	public function execute() {
		$this->markDeprecated();
		$data = $this->getResult()->getResultData( null, array(
			'BC' => array(),
			'Types' => array(),
			'Strip' => 'all',
		) );
		$this->printText( print_r( $data, true ) );
	}

	public function isDeprecated() {
		return true;
	}
}
