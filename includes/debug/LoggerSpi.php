<?php
/**
 * @section LICENSE
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
 * Service provider interface for MWLogger implementation libraries.
 *
 * MediaWiki can be configured to use a class implementing this interface to
 * create new MWLogger instances via either the $wgMWLoggerDefaultSpi global
 * variable or code that constructs an instance and registeres it via the
 * MWLogger::registerProvider() static method.
 *
 * @see MWLogger
 * @since 1.24
 * @author Bryan Davis <bd808@wikimedia.org>
 * @copyright © 2014 Bryan Davis and Wikimedia Foundation.
 */
interface MWLoggerSpi {

	/**
	 * Get a logger instance.
	 *
	 * @param string $channel Logging channel
	 * @return MWLogger Logger instance
	 */
	public function getLogger( $channel );

}
