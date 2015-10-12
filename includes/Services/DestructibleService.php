<?php
namespace MediaWiki\Services;

/**
 * Interface for destructible services.
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
 *
 * @since 1.27
 */

/**
 * DestructibleService defines a standard interface for shutting down a service instance.
 * The intended use is for a service container to be able to shut down services that should
 * no longer be used, and allow such services to release any system resources.
 *
 * @note There is no expectation that services will be destroyed when the process (or web request)
 * terminates.
 */
interface DestructibleService {

	/**
	 * Notifies the service object that it should expect to no longer be used, and should release
	 * any system resources it may own. The behavior of all service methods becomes undefined after
	 * destroy() has been called. It is recommended that implementing classes should throw an
	 * exception when service methods are accessed after destroy() has been called.
	 */
	public function destroy();

}
