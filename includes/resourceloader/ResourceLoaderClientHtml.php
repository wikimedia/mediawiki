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

use WrappedString\WrappedString;
use WrappedString\WrappedStringList;
use MediaWiki\Logger\LoggerFactory;

/**
 * @since 1.28
 */
class ResourceLoaderClientHtml {

	/** @var ResourceLoaderContext */
	private $context;

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

	/** @var array */
	private $outsource = [];

	/** @var array */
	private $data;

	/**
	 * @param ResourceLoaderContext $context
	 */
	public function __construct( ResourceLoaderContext $context ) {
		$this->context = $context;
		$this->resourceLoader = $context->getResourceLoader();
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
	 * Set state of special modules that are handled by the caller manually.
	 *
	 * See OutputPage::buildExemptModules() for use cases.
	 *
	 * @param array $modules Module state keyed by module name
	 */
	public function setExemptStates( array $states ) {
		$this->exemptStates = $states;
	}

	/**
	 * Relevant tasks:
	 *
	 * - Embed private modules. (T36907)
	 * - Set state to "ready" for moduleStyles. (T87871)
	 *
	 * @return array
	 */
	private function getData() {
		if ( $this->data ) {
			return $this->data;
		}

		$rl = $this->resourceLoader;
		$data = [
			'states' => [
				// moduleName => state
			],
			'general' => [
				// position => [ moduleName ]
				'top' => [],
				'bottom' => [],
			],
			'styles' => [
				// moduleName
			],
			'scripts' => [
				// position => [ moduleName ]
				'top' => [],
				'bottom' => [],
			],
			// Embedding for private modules
			'embed' => [
				'styles' => [],
				'scripts' => [],
				'general' => [
					'top' => [],
					'bottom' => [],
				],
			],

		];

		foreach ( $this->modules as $name ) {
			$module = $rl->getModule( $name );
			if ( !$module ) {
				continue;
			}

			$group = $module->getGroup();
			$position = $module->getPosition();

			if ( $group === 'private' ) {
				// Embed via mw.loader.implement()
				$data['embed']['general'][$position][] = $name;
				// Avoid duplicate request from mw.loader
				$data['states'][$name] = 'loading';
			} else {
				// Load via mw.loader.load()
				$data['general'][$position][] = $name;
			}
		}

		foreach ( $this->moduleStyles as $name ) {
			$module = $rl->getModule( $name );
			if ( !$module ) {
				continue;
			}

			$group = $module->getGroup();
			$context = $this->makeContext( $group, ResourceLoaderModule::TYPE_STYLES );

			if ( $module->getType() !== ResourceLoaderModule::LOAD_STYLES ) {
				$logger = $rl->getLogger();
				$logger->debug( 'Unexpected general module "{module}" in styles queue.', [
					'module' => $name,
				] );
			} else {
				// Stylesheet doesn't trigger mw.loader callback.
				// Set "ready" state to allow dependencies and avoid duplicate requests.
				$data['states'][$name] = 'ready';
			}

			if ( $module->isKnownEmpty( $context ) ) {
				// Avoid needless request for empty module
				$data['states'][$name] = 'ready';
			} else {
				if ( $group === 'private' ) {
					// Embed via style element
					$data['embed']['styles'][] = $name;
					// Avoid duplicate request from mw.loader
					$data['states'][$name] = 'ready';
				} else {
					// Load from load.php?only=styles via <link rel=stylesheet>
					$data['styles'][] = $name;
				}
			}
		}

		foreach ( $this->moduleScripts as $name ) {
			$module = $rl->getModule( $name );
			if ( !$module ) {
				continue;
			}

			$group = $module->getGroup();
			$context = $this->makeContext( $group, ResourceLoaderModule::TYPE_SCRIPTS );

			if ( $module->isKnownEmpty( $context ) ) {
				// Avoid needless request for empty module
				$data['states'][$name] = 'ready';
			} else {
				// Load from load.php?only=scripts via <script src></script>
				$data['scripts'][$position][] = $name;

				// Avoid duplicate request from mw.loader
				$data['states'][$name] = 'loading';
			}
		}

		return $data;
	}

	/**
	 * TODO:
	 * - Implement analogous logic for:
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
	 * For performance, it is important that the following order is used in the head:
	 * - Inline scripts.
	 * - Stylesheets.
	 * - Async external script-src.
	 *
	 * Reason:
	 * - Scripts may be blocked on preceeding stylesheets.
	 * - Async scripts are not blocked on stylesheets.
	 * - For styles, earlier is better.
	 *
	 * @return string|WrappedStringList HTML
	 */
	public function getHeadHtml() {
		$data = $this->getData();

		// For OutputPage::headElement(). Replaces:
		//  - getInlineHeadScripts()
		//  - buildCssLinks()
		//  - getExternalHeadScripts()
		//  - makeResourceLoaderLink()

		$chunks = [];

		// $chunks[] = '<!--  ' . json_encode( $data, JSON_PRETTY_PRINT ) . ' -->';

		// Change "client-nojs" class to client-js. This allows easy toggling of UI components.
		// This happens synchronously on every page view to avoid flashes of wrong content.
		// See also #getDocumentAttributes() and /resources/src/startup.js.
		$chunks[] = Html::inlineScript(
			'document.documentElement.className = document.documentElement.className'
			. '.replace( /(^|\s)client-nojs(\s|$)/, "$1client-js$2" );'
		);

		// RLQ: Set page variables
		if ( $this->config ) {
			$chunks[] = ResourceLoader::makeInlineScript(
				ResourceLoader::makeConfigSetScript( $this->config )
			);
		}

		// RLQ: Initial module states
		$states = array_merge( $this->exemptStates, $data['states'] );
		if ( $states ) {
			$chunks[] = ResourceLoader::makeInlineScript(
				ResourceLoader::makeLoaderStateScript( $states )
			);
		}

		// RLQ: Embedded modules and only=scripts
		if ( $data['embed']['scripts'] ) {
			$chunks[] = $this->makeResourceLoaderLink(
				$data['embed']['scripts'],
				ResourceLoaderModule::TYPE_SCRIPTS
			);
		}
		if ( $data['embed']['general']['top'] ) {
			$chunks[] = $this->makeResourceLoaderLink(
				$data['embed']['general']['top'],
				ResourceLoaderModule::TYPE_COMBINED
			);
		}

		// RLQ: Load general modules
		if ( $data['general']['top'] ) {
			$chunks[] = ResourceLoader::makeInlineScript(
				Xml::encodeJsCall( 'mw.loader.load', [ $data['general']['top'] ] )
			);
		}

		// Load only=scripts
		if ( $data['scripts']['top'] ) {
			$chunks[] = $this->makeResourceLoaderLink(
				$data['scripts']['top'],
				ResourceLoaderModule::TYPE_SCRIPTS
			);
		}

		// Stylesheets
		if ( $data['styles'] ) {
			$chunks[] = $this->makeResourceLoaderLink(
				$data['styles'],
				ResourceLoaderModule::TYPE_STYLES
			);
		}

		// Embedded only=styles
		if ( $data['embed']['styles'] ) {
			$chunks[] = $this->makeResourceLoaderLink(
				$data['embed']['styles'],
				ResourceLoaderModule::TYPE_STYLES
			);
		}

		// TODO: From buildCssLinks()
		// - styles-only load 'site.styles', 'noscript' and 'cssprefs' (don't hardcode, make OutputPage call addModules)
		// - makeResourceLoaderLink TYPE_STYLES getModuleStyles()
		// - <meta> ResourceLoaderDynamicStyles
		// - makeResourceLoaderLink TYPE_STYLES site/user groups

		// TODO: From getExternalHeadScripts()
		// - $this->makeResourceLoaderLink( 'startup', TYPE_SCRIPTS );

		return WrappedStringList::join( "\n", $chunks );
	}

	public function getBodyHtml() {
		// For OutputPage::getBottomScripts() ->
		//  Skin::bottomScripts -> BaseTemplate::printTrail().

		// TODO: From getScriptsForBottomQueue()
		// - "Only scripts"
		//    $this->makeResourceLoaderLink( $this->getModuleScripts( true, 'bottom' ), TYPE_SCRIPTS );
		// - mw.loader.load $this->getModules( true, 'bottom' )
		// - user module + excludepage handling (keep in OutputPage?)

		// TODO: $data['embed']['general']['bottom'];
	}

	/**
	 * @param string|null $group
	 * @param string $type ResourceLoaderModule TYPE_ class constant
	 * @return ResourceLoaderContext
	 */
	private function makeContext( $group, $type ) {
		$derived = new DerivativeResourceLoaderContext( $this->context );
		// Set 'only' if not combined
		$derived->setOnly( $type === ResourceLoaderModule::TYPE_COMBINED ? null : $type );
		// Remove user parameter in most cases
		if ( $group !== 'user' && $group !== 'private' ) {
			$derived->setUser( null );
		}

		return $derived;
	}

	/**
	 * Construct necessary html and loader preset states to load modules on a page.
	 *
	 * @param array|string $modules One or more module names
	 * @param string $only ResourceLoaderModule TYPE_ class constant
	 * @param array $extraQuery [optional] Array with extra query parameters for the request
	 * @return string|WrappedStringList HTML
	 */
	public function makeResourceLoaderLink( array $modules, $only, array $extraQuery = [] ) {
		$rl = $this->resourceLoader;
		$chunks = [];

		// Sort module names so requests are more uniform
		sort( $modules );

		// Create keyed-by-source and then keyed-by-group list of module objects from modules list
		$sortedModules = [];
		foreach ( $modules as $name ) {
			$module = $rl->getModule( $name );
			$sortedModules[$module->getSource()][$module->getGroup()][$name] = $module;
		}

		foreach ( $sortedModules as $source => $groups ) {
			foreach ( $groups as $group => $grpModules ) {
				$context = $this->makeContext( $group, $only );

				if ( $group === 'private' ) {
					// Decide whether to use style or script element
					if ( $only == ResourceLoaderModule::TYPE_STYLES ) {
						$chunks[] = Html::inlineStyle(
							$rl->makeModuleResponse( $context, $grpModules )
						);
					} else {
						$chunks[] = ResourceLoader::makeInlineScript(
							$rl->makeModuleResponse( $context, $grpModules )
						);
					}

					continue;
				}

				// See if we have one or more raw modules
				$isRaw = false;
				foreach ( $grpModules as $key => $module ) {
					$isRaw |= $module->isRaw();
				}

				// Special handling for the user group; because users might change their stuff
				// on-wiki like user pages, or user preferences; we need to find the highest
				// timestamp of these user-changeable modules so we can ensure cache misses on change
				// This should NOT be done for the site group (bug 27564) because anons get that too
				// and we shouldn't be putting timestamps in CDN-cached HTML
				if ( $group === 'user' ) {
					$version = $rl->getCombinedVersion( $context, array_keys( $grpModules ) );
					$context->setVersion( $version );
				}

				$context->setModules( array_keys( $grpModules ) );
				$url = $rl->createLoaderURL( $source, $context, $extraQuery );

				// Device whether to use 'style' or 'script' element
				if ( $only === ResourceLoaderModule::TYPE_STYLES ) {
					$chunk = Html::linkedStyle( $url );
				} else {
					if ( $context->getRaw() || $isRaw ) {
						// Startup module can't load itself, needs to use script-src instead of mw.loader.load
						$chunk = Html::element( 'script', [
							// In SpecialJavaScriptTest, QUnit must load synchronous
							'async' => !isset( $extraQuery['sync'] ),
							'src' => $url
						] );
					} else {
						$chunk = ResourceLoader::makeInlineScript(
							Xml::encodeJsCall( 'mw.loader.load', [ $url ] )
						);
					}
				}

				if ( $group == 'noscript' ) {
					$chunks[] = Html::rawElement( 'noscript', [], $chunk );
				} else {
					$chunks[] = $chunk;
				}
			}
		}

		return WrappedString::join( "\n", $chunks );
	}

}
