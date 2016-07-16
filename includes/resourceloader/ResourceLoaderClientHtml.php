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
	private $config = [];

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
	 * @param array $vars Array of key/value pairs
	 */
	public function setConfig( array $vars ) {
		foreach ( $vars as $key => $value ) {
			$this->config[$key] = $value;
		}
	}

	/**
	 * Ensure one or more modules are loaded.
	 *
	 * @param array $modules Array of module names
	 */
	public function setModules( array $modules ) {
		$this->modules = $modules;
	}

	/**
	 * Ensure the scripts of one or more modules are loaded.
	 *
	 * @deprecated since 1.28
	 * @param array $modules Array of module names
	 */
	public function setModuleScripts( array $modules ) {
		$this->moduleScripts = $modules;
	}

	/**
	 * Ensure the styles of one or more modules are loaded.
	 *
	 * @deprecated since 1.28
	 * @param array $modules Array of module names
	 */
	public function setModuleStyles( array $modules ) {
		$this->moduleStyles = $modules;
	}

	/**
	 * TODO:
	 * - Implement analogous logic for:
	 *   - getAllowedModules() - filter by trusted origin level
	 *   - filterModules() - split by queue position (deprecated)
	 *   - split by scripts/styles-only and regular (deprecated)
	 */

	public function getDocumentAttributes() {
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
		// Based on (or replaces):
		//  getInlineHeadScripts()
		//  buildCssLinks()
		//  getExternalHeadScripts()
		//  makeResourceLoaderLink()

		$chunks = [];

		// Change "client-nojs" class to client-js. This allows easy toggling of UI components.
		// This happens synchronously on every page view to avoid flashes of wrong content.
		// See also #getDocumentAttributes() and /resources/src/startup.js.
		$chunks[] = Html::inlineScript(
			'document.documentElement.className = document.documentElement.className'
			. '.replace( /(^|\s)client-nojs(\s|$)/, "$1client-js$2" );'
		);

		// RLQ 1): Set page variables
		$links[] = ResourceLoader::makeInlineScript(
			ResourceLoader::makeConfigSetScript( $this->config )
		);

		// RLQ 2): Set early module states

		// RLQ 3): Export embedded private modules

		// RLQ 4): Export load queue

		// Stylesheets
	}

	public function getBodyHtml() {
		// For OutputPage::getBottomScripts() ->
		//  Skin::bottomScripts -> BaseTemplate::printTrail().
	}

}
