<?php
/**
 * Resource loader module for assets associated with Wiki Templates
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
 */

/**
 * Module for site customizations
 */
class ResourceLoaderWikiTemplateModule extends ResourceLoaderWikiModule {
	protected $pages;

	/* Protected Methods */

	/**
	 * Gets list of pages used by this module
	 *
	 * @param $context ResourceLoaderContext
	 *
	 * @return Array: List of pages
	 */
	protected function getPages( ResourceLoaderContext $context ) {
		return $this->pages;
	}

	/* Methods */

	/**
	 * Gets group name
	 *
	 * @return String: Name of group
	 */
	public function getGroup() {
		return 'wikitemplate';
	}

	public function __construct( $templates = array() ) {
		global $wgUseSiteCss;
		$pages = array();
		foreach ( $templates as $key => $val ) {
			$pages[ 'Template:' . $key . '.css' ] = array( 'type' => 'style' );
		}
		$this->pages = $pages;
	}
}
