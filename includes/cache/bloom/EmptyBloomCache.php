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
 * @author Aaron Schulz
 */

class EmptyBloomCache extends BloomCache {
	public function __construct( array $config ) {
		parent::__construct( array( 'cacheId' => 'none' ) );
	}

	public function getServers() {
		return array();
	}

	public function getConnection( $server = null ) {
		return null;
	}

	protected function doInit( $key, $size, $precision ) {
		return true;
	}

	protected function doAdd( $key, array $members ) {
		return true;
	}

	protected function doDelete( $key ) {
		return true;
	}

	protected function doSetStatus( $virtualKey, array $values ) {
		return true;
	}

	protected function doGetStatus( $virtualKey ) {
		return array( 'lastID' => null, 'asOfTime' => null, 'epoch' => null );
	}
}
