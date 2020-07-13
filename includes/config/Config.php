<?php
/**
 * Copyright 2014
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
 * Interface for configuration instances
 *
 * @stable to implement
 *
 * @since 1.23
 */
interface Config {

	/**
	 * Get a configuration variable such as "Sitename" or "UploadMaintenance."
	 *
	 * @param string $name Name of configuration option
	 * @return mixed Value configured
	 * @throws ConfigException
	 */
	public function get( $name );

	/**
	 * Check whether a configuration option is set for the given name
	 *
	 * @param string $name Name of configuration option
	 * @return bool
	 * @since 1.24
	 */
	public function has( $name );
}
