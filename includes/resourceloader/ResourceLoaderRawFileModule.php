<?php
/**
 * Module containing files that are loaded without ResourceLoader.
 *
 * Primary usecase being "base" modules loaded by the startup module,
 * such as jquery and the mw.loader client itself. These make use of
 * ResourceLoaderModule and load.php for convenience but aren't actually
 * registered in the startup module (as it would have to load itself).
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
 * @author Timo Tijhof
 */

class ResourceLoaderRawFileModule extends ResourceLoaderFileModule {

	/**
	 * Enable raw mode to omit mw.loader.state() call as mw.loader
	 * does not yet exist when these modules execute.
	 * @var bool
	 */
	protected $raw = true;

	/**
	 * Get all JavaScript code.
	 *
	 * @param ResourceLoaderContext $context
	 * @return string JavaScript code
	 */
	public function getScript( ResourceLoaderContext $context ) {
		$script = parent::getScript( $context );
		// Add closure explicitly because raw modules can't be wrapped mw.loader.implement.
		// Unlike with mw.loader.implement, this closure is immediately invoked.
		// @see ResourceLoader::makeModuleResponse
		// @see ResourceLoader::makeLoaderImplementScript
		return "(function () {\n{$script}\n}());";
	}
}
