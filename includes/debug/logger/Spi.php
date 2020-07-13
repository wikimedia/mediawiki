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

namespace MediaWiki\Logger;

/**
 * Service provider interface for \Psr\Log\LoggerInterface implementation
 * libraries.
 *
 * MediaWiki can be configured to use a class implementing this interface to
 * create new \Psr\Log\LoggerInterface instances via either the
 * $wgMWLoggerDefaultSpi global variable or code that constructs an instance
 * and registers it via the LoggerFactory::registerProvider() static method.
 *
 * @see \MediaWiki\Logger\LoggerFactory
 * @stable to implement
 *
 * @since 1.25
 * @copyright © 2014 Wikimedia Foundation and contributors
 */
interface Spi {

	/**
	 * Get a logger instance.
	 *
	 * @param string $channel Logging channel
	 * @return \Psr\Log\LoggerInterface Logger instance
	 */
	public function getLogger( $channel );

}
