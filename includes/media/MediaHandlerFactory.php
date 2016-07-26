<?php
/**
 * Media-handling base classes and generic functionality.
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
 * @ingroup Media
 */

/**
 * Class to construct MediaHandler objects
 *
 * @since 1.28
 */
class MediaHandlerFactory {

	/**
	 * @var MediaHandler[]
	 */
	private $handlers;

	/**
	 * @param string $type mimetype
	 * @return bool|MediaHandler
	 */
	public function getHandler( $type ) {
		global $wgMediaHandlers;
		if ( !isset( $wgMediaHandlers[$type] ) ) {
			wfDebug( __METHOD__ . ": no handler found for $type.\n" );

			return false;
		}
		$class = $wgMediaHandlers[$type];
		if ( !isset( $this->handlers[$class] ) ) {
			$this->handlers[$class] = new $class;
			if ( !$this->handlers[$class]->isEnabled() ) {
				wfDebug( __METHOD__ . ": $class is not enabled\n" );
				$this->handlers[$class] = false;
			}
		}

		return $this->handlers[$class];
	}
}
