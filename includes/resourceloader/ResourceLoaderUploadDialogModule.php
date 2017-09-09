<?php
/**
 * ResourceLoader module for the upload dialog configuration data.
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
 * ResourceLoader module for the upload dialog configuration data.
 *
 * @since 1.27
 */
class ResourceLoaderUploadDialogModule extends ResourceLoaderModule {

	protected $targets = [ 'desktop', 'mobile' ];

	/**
	 * @param ResourceLoaderContext $context
	 * @return string JavaScript code
	 */
	public function getScript( ResourceLoaderContext $context ) {
		$config = $context->getResourceLoader()->getConfig();
		return ResourceLoader::makeConfigSetScript( [
			'wgUploadDialog' => $config->get( 'UploadDialog' ),
		] );
	}

	/**
	 * @return bool
	 */
	public function enableModuleContentVersion() {
		return true;
	}
}
