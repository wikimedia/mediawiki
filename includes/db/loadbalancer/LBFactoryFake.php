<?php
/**
 * Generator of database load balancing objects.
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
 * @ingroup Database
 */

/**
 * LBFactory class that throws an error on any attempt to use it.
 * This will typically be done via wfGetDB().
 * Call LBFactory::disableBackend() to start using this, and
 * LBFactory::enableBackend() to return to normal behavior
 */
class LBFactoryFake extends LBFactory {
	public function newMainLB( $wiki = false ) {
		throw new DBAccessError;
	}

	public function getMainLB( $wiki = false ) {
		throw new DBAccessError;
	}

	protected function newExternalLB( $cluster, $wiki = false ) {
		throw new DBAccessError;
	}

	public function &getExternalLB( $cluster, $wiki = false ) {
		throw new DBAccessError;
	}

	public function forEachLB( $callback, array $params = [] ) {
	}
}
