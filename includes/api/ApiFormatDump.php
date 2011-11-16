<?php
/**
 *
 *
 * Created on August 8, 2010
 *
 * Copyright © 2010 Soxred93
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
 * API PHP's var_dump() output formatter
 * @ingroup API
 */
class ApiFormatDump extends ApiFormatBase {

	public function __construct( $main, $format ) {
		parent::__construct( $main, $format );
	}

	public function getMimeType() {
		// This looks like it should be text/plain, but IE7 is so
		// brain-damaged it tries to parse text/plain as HTML if it
		// contains HTML tags. Using MIME text/text works around this bug
		return 'text/text';
	}

	public function execute() {
		ob_start();
		var_dump( $this->getResultData() );
		$result = ob_get_contents();
		ob_end_clean();
		$this->printText( $result );
	}

	public function getDescription() {
		return 'Output data in PHP\'s var_dump() format' . parent::getDescription();
	}

	public function getVersion() {
		return __CLASS__ . ': $Id$';
	}
}
