<?php
/**
 * Bootstrapping for a ResourceLoader client on an HTML page.
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

use MediaWiki\Logger\LoggerFactory;

/**
 * @since 1.28
 */
class ResourceLoaderClientHtml {

	/** @var ResourceLoader */
	private $resourceLoader;

	/** @var array */
	private $modules = [];

	/** @var array */
	private $moduleScripts = [];

	/** @var array */
	private $moduleStyles = [];

	public function __construct( ResourceLoader $resourceLoader ) {
		$this->resourceLoader = $resourceLoader;
	}

	/**
	 * Ensure one or more modules are loaded.
	 *
	 * @param string|array $modules Module name or array of module names
	 */
	public function addModules( $modules ) {
		$this->modules = array_merge( $this->modules, (array)$modules );
	}

	/**
	 * Ensure the scripts of one or more modules are loaded.
	 *
	 * @deprecated since 1.28
	 * @param string|array $modules Module name or array of module names
	 */
	public function addModuleScripts( $modules ) {
		$this->moduleScripts = array_merge( $this->moduleScripts, (array)$modules );
	}

	/**
	 * Ensure the styles of one or more modules are loaded.
	 *
	 * @deprecated since 1.28
	 * @param string|array $modules Module name or array of module names
	 */
	public function addModuleStyles( $modules ) {
		$this->moduleStyles = array_merge( $this->moduleStyles, (array)$modules );
	}

	/**
	 * TODO:
	 * - Implement analogous logic for:
	 *   - getAllowedModules() - filter by trusted origin level
	 *   - filterModules() - split by queue position (deprecated)
	 *   - split by scripts/styles-only and regular (deprecated)
	 */

	public function getDocumentElementAttributes() {
		// For OutputPage::headElement() ->
		//  Skin::getHtmlElementAttributes()
		// - class="client-nojs" (changed by startup.js)
		// - (later) data-rl-load=",,," data-rl-state-ready=",,," data-rl-state-loading=",,,"
		//    (to be dealt by startup.js, instead of current state() and load() inline scripts)
	}

	/**
	 * @return string HTML
	 */
	public function getHeadHtml() {
		// For OutputPage::headElement()
		// See
		//  getInlineHeadScripts()
		//  buildCssLinks()
		//  getExternalHeadScripts()
		//  makeResourceLoaderLink()
	}

	public function getBodyHtml() {
		// For OutputPage::getBottomScripts() ->
		//  Skin::bottomScripts -> BaseTemplate::printTrail().
	}

}
