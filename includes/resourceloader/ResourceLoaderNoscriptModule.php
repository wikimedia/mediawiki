<?php
/**
 * Resource loader for site customizations for users without JavaScript enabled.
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
 * @author Trevor Parscal
 * @author Roan Kattouw
 */

/**
 * Module for site customizations
 */
class ResourceLoaderNoscriptModule extends ResourceLoaderWikiModule {

	/* Protected Methods */

	/**
	 * Gets list of pages used by this module.  Obviously, it makes absolutely no
	 * sense to include JavaScript files here... :D
	 *
	 * @param ResourceLoaderContext $context
	 *
	 * @return array List of pages
	 */
	protected function getPages( ResourceLoaderContext $context ) {
		return array( 'MediaWiki:Noscript.css' => array( 'type' => 'style' ) );
	}

	/* Methods */

	/**
	 * Gets group name
	 *
	 * @return string Name of group
	 */
	public function getGroup() {
		return 'noscript';
	}
}
