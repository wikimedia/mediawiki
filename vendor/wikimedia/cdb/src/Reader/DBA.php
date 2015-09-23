<?php

namespace Cdb\Reader;

use Cdb\Exception;
use Cdb\Reader;

/**
 * DBA-based CDB reader/writer
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
 * Reader class which uses the DBA extension
 */
class DBA extends Reader {
	public function __construct( $fileName ) {
		$this->handle = dba_open( $fileName, 'r-', 'cdb' );
		if ( !$this->handle ) {
			throw new Exception( 'Unable to open CDB file "' . $fileName . '"' );
		}
	}

	public function close() {
		if ( isset( $this->handle ) ) {
			dba_close( $this->handle );
		}
		unset( $this->handle );
	}

	public function get( $key ) {
		return dba_fetch( $key, $this->handle );
	}

	public function exists( $key ) {
		return dba_exists( $key, $this->handle );
	}

	public function firstkey() {
		return dba_firstkey( $this->handle );
	}

	public function nextkey() {
		return dba_nextkey( $this->handle );
	}
}
