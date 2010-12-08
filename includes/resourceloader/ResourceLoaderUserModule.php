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
 * @author Trevor Parscal
 * @author Roan Kattouw
 */

/**
 * Module for user customizations
 */
class ResourceLoaderUserModule extends ResourceLoaderWikiModule {

	/* Protected Methods */

	protected function getPages( ResourceLoaderContext $context ) {
		if ( $context->getUser() ) {
			$username = $context->getUser();
			return array(
				"$username/common.js" => array( 'ns' => NS_USER, 'type' => 'script' ),
				"$username/" . $context->getSkin() . '.js' => 
					array( 'ns' => NS_USER, 'type' => 'script' ),
				"$username/common.css" => array( 'ns' => NS_USER, 'type' => 'style' ),
				"$username/" . $context->getSkin() . '.css' => 
					array( 'ns' => NS_USER, 'type' => 'style' ),
			);
		}
		return array();
	}
	
	/* Methods */
	
	public function getGroup() {
		return 'user';
	}
}
