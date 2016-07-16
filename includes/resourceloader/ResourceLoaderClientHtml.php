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

	/** @var string|null */
	private $target;

	/** @var array */
	private $config = [];

	/** @var array */
	private $modules = [];

	/** @var array */
	private $moduleScripts = [];

	/** @var array */
	private $moduleStyles = [];

	/**
	 * @param ResourceLoader $resourceLoader
	 * @param string $target [optional]
	 */
	public function __construct( ResourceLoader $resourceLoader, $target = null ) {
		$this->resourceLoader = $resourceLoader;
		$this->target = $target;
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
	 * For performance, it is important that the following order is used:
	 * - Inline scripts.
	 * - Stylesheets.
	 * - Async external script-src.
	 *
	 * Reason:
	 * - Scripts may be blocked on preceeding stylesheets.
	 * - Async scripts are not blocked on stylesheets.
	 * - For styles, earlier is better.
	 *
	 * @return string HTML
	 */
	public function getHeadHtml() {
		// For OutputPage::headElement()
		// Based on (or replaces):
		//  - getInlineHeadScripts()
		//  - buildCssLinks()
		//  - getExternalHeadScripts()
		//  - makeResourceLoaderLink()

		$chunks = [];

		// Change "client-nojs" class to client-js. This allows easy toggling of UI components.
		// This happens synchronously on every page view to avoid flashes of wrong content.
		// See also #getDocumentAttributes() and /resources/src/startup.js.
		$chunks[] = Html::inlineScript(
			'document.documentElement.className = document.documentElement.className'
			. '.replace( /(^|\s)client-nojs(\s|$)/, "$1client-js$2" );'
		);

		// RLQ 1): Set page variables
		if ( $this->config ) {
			$links[] = ResourceLoader::makeInlineScript(
				ResourceLoader::makeConfigSetScript( $this->config )
			);
		}

		// RLQ 2): Set early module states

		// RLQ 3): Export embedded private modules

		// RLQ 4): Export load() queue

		// Stylesheets

		// TODO: From getInlineHeadScripts()
		// - embed user.options (don't hardcode, make OutputPage call addModules)
		// - embed user.tokens (don't hardcode, make OutputPage call addModules)
		//   // Separate user.tokens to avoid caching (T84960)
		// - mw.loader.load $this->getModules( true, 'top' );
		// - "Only scripts"
		//    $this->makeResourceLoaderLink( $this->getModuleScripts( true, 'top' ), ResourceLoaderModule::TYPE_SCRIPTS )

		// TODO: From buildCssLinks()
		// - styles-only load 'site.styles', 'noscript' and 'cssprefs' (don't hardcode, make OutputPage call addModules)
		// - makeResourceLoaderLink TYPE_STYLES getModuleStyles()
		// - <meta> ResourceLoaderDynamicStyles
		// - makeResourceLoaderLink TYPE_STYLES site/user groups

		// TODO: From getExternalHeadScripts()
		// - $this->makeResourceLoaderLink( 'startup', TYPE_SCRIPTS );

	}

	public function getBodyHtml() {
		// For OutputPage::getBottomScripts() ->
		//  Skin::bottomScripts -> BaseTemplate::printTrail().

		// TODO: From getScriptsForBottomQueue()
		// - "Only scripts"
		//    $this->makeResourceLoaderLink( $this->getModuleScripts( true, 'bottom' ), TYPE_SCRIPTS );
		// - mw.loader.load $this->getModules( true, 'bottom' )
		// - user module + excludepage handling (keep in OutputPage?)
	}

	private function process() {
		$rl = $this->resourceLoader();
		$data = [];

		foreach ( $this->modules as $name ) {
			$module = $resourceLoader->getModule( $name );
		}

		foreach ( $this->moduleStyles as $name ) {
			$module = $resourceLoader->getModule( $name );
		}

		foreach ( $this->moduleScripts as $name ) {
			$module = $resourceLoader->getModule( $name );
		}

		return $data;
	}

	/**
	 * Construct neccecary html and loader preset states to load modules on a page.
	 *
	 * Use getHtmlFromLoaderLinks() to convert this array to HTML.
	 *
	 * @param array|string $modules One or more module names
	 * @param string $only ResourceLoaderModule TYPE_ class constant
	 * @param array $extraQuery [optional] Array with extra query parameters for the request
	 * @return array A list of HTML strings and array of client loader preset states
	 */
	public function makeResourceLoaderLink( $modules, $only, array $extraQuery = [] ) {
		$modules = (array)$modules;

		// Create keyed-by-source and then keyed-by-group list of module objects from modules list
		$sortedModules = [];
		$resourceLoader = $this->getResourceLoader();
		foreach ( $modules as $name ) {
			$module = $resourceLoader->getModule( $name );

			if ( $only === ResourceLoaderModule::TYPE_STYLES ) {
				if ( $module->getType() !== ResourceLoaderModule::LOAD_STYLES ) {
					$logger = $resourceLoader->getLogger();
					$logger->debug( 'Unexpected general module "{module}" in styles queue.', [
						'module' => $name,
					] );
				} else {
					$links['states'][$name] = 'ready';
				}
			}

			$sortedModules[$module->getSource()][$module->getGroup()][$name] = $module;
		}

		foreach ( $sortedModules as $source => $groups ) {
			foreach ( $groups as $group => $grpModules ) {
				// Special handling for user-specific groups
				$user = null;
				if ( ( $group === 'user' || $group === 'private' ) && $this->getUser()->isLoggedIn() ) {
					$user = $this->getUser()->getName();
				}

				// Create a fake request based on the one we are about to make so modules return
				// correct timestamp and emptiness data
				$query = ResourceLoader::makeLoaderQuery(
					[], // modules; not determined yet
					$this->getLanguage()->getCode(),
					$this->getSkin()->getSkinName(),
					$user,
					null, // version; not determined yet
					ResourceLoader::inDebugMode(),
					$only === ResourceLoaderModule::TYPE_COMBINED ? null : $only,
					$this->isPrintable(),
					$this->getRequest()->getBool( 'handheld' ),
					$extraQuery
				);
				$context = new ResourceLoaderContext( $resourceLoader, new FauxRequest( $query ) );

				// Extract modules that know they're empty and see if we have one or more
				// raw modules
				$isRaw = false;
				foreach ( $grpModules as $key => $module ) {
					// Inline empty modules: since they're empty, just mark them as 'ready' (bug 46857)
					// If we're only getting the styles, we don't need to do anything for empty modules.
					if ( $module->isKnownEmpty( $context ) ) {
						unset( $grpModules[$key] );
						if ( $only !== ResourceLoaderModule::TYPE_STYLES ) {
							$links['states'][$key] = 'ready';
						}
					}

					$isRaw |= $module->isRaw();
				}

				// If there are no non-empty modules, skip this group
				if ( count( $grpModules ) === 0 ) {
					continue;
				}

				// Inline private modules. These can't be loaded through load.php for security
				// reasons, see bug 34907. Note that these modules should be loaded from
				// getExternalHeadScripts() before the first loader call. Otherwise other modules can't
				// properly use them as dependencies (bug 30914)
				if ( $group === 'private' ) {
					if ( $only == ResourceLoaderModule::TYPE_STYLES ) {
						$links['html'][] = Html::inlineStyle(
							$resourceLoader->makeModuleResponse( $context, $grpModules )
						);
					} else {
						$links['html'][] = ResourceLoader::makeInlineScript(
							$resourceLoader->makeModuleResponse( $context, $grpModules )
						);
					}
					continue;
				}

				// Special handling for the user group; because users might change their stuff
				// on-wiki like user pages, or user preferences; we need to find the highest
				// timestamp of these user-changeable modules so we can ensure cache misses on change
				// This should NOT be done for the site group (bug 27564) because anons get that too
				// and we shouldn't be putting timestamps in CDN-cached HTML
				$version = null;
				if ( $group === 'user' ) {
					$query['version'] = $resourceLoader->getCombinedVersion( $context, array_keys( $grpModules ) );
				}

				$query['modules'] = ResourceLoader::makePackedModulesString( array_keys( $grpModules ) );
				$moduleContext = new ResourceLoaderContext( $resourceLoader, new FauxRequest( $query ) );
				$url = $resourceLoader->createLoaderURL( $source, $moduleContext, $extraQuery );

				// Automatically select style/script elements
				if ( $only === ResourceLoaderModule::TYPE_STYLES ) {
					$link = Html::linkedStyle( $url );
				} else {
					if ( $context->getRaw() || $isRaw ) {
						// Startup module can't load itself, needs to use <script> instead of mw.loader.load
						$link = Html::element( 'script', [
							// In SpecialJavaScriptTest, QUnit must load synchronous
							'async' => !isset( $extraQuery['sync'] ),
							'src' => $url
						] );
					} else {
						$link = ResourceLoader::makeInlineScript(
							Xml::encodeJsCall( 'mw.loader.load', [ $url ] )
						);
					}

					// For modules requested directly in the html via <script> or mw.loader.load
					// tell mw.loader they are being loading to prevent duplicate requests.
					foreach ( $grpModules as $key => $module ) {
						// Don't output state=loading for the startup module.
						if ( $key !== 'startup' ) {
							$links['states'][$key] = 'loading';
						}
					}
				}

				if ( $group == 'noscript' ) {
					$links['html'][] = Html::rawElement( 'noscript', [], $link );
				} else {
					$links['html'][] = $link;
				}
			}
		}

		return $links;
	}

}
