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
 */

use WrappedString\WrappedStringList;

/**
 * Bootstrap a ResourceLoader client on an HTML page.
 *
 * @since 1.28
 */
class ResourceLoaderClientHtml {

	/** @var ResourceLoaderContext */
	private $context;

	/** @var ResourceLoader */
	private $resourceLoader;

	/** @var string|null */
	private $target;

	/** @var array */
	private $config = [];

	/** @var array */
	private $modules = [];

	/** @var array */
	private $moduleStyles = [];

	/** @var array */
	private $moduleScripts = [];

	/** @var array */
	private $exemptStates = [];

	/** @var array */
	private $data;

	/**
	 * @param ResourceLoaderContext $context
	 * @param string|null $target [optional] Custom 'target' parameter for the startup module
	 */
	public function __construct( ResourceLoaderContext $context, $target = null ) {
		$this->context = $context;
		$this->resourceLoader = $context->getResourceLoader();
		$this->target = $target;
	}

	/**
	 * Set mw.config variables.
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
	 * Ensure the styles of one or more modules are loaded.
	 *
	 * @deprecated since 1.28
	 * @param array $modules Array of module names
	 */
	public function setModuleStyles( array $modules ) {
		$this->moduleStyles = $modules;
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
	 * @return array
	 */
	private function getData() {
		if ( $this->data ) {
			// @codeCoverageIgnoreStart
			return $this->data;
			// @codeCoverageIgnoreEnd
		}

		$rl = $this->resourceLoader;
		$data = [
			'states' => [
				// moduleName => state
			],
			'general' => [],
			'styles' => [
				// moduleName
			],
			'scripts' => [],
			// Embedding for private modules
			'embed' => [
				'styles' => [],
				'general' => [],
			],

		];

		foreach ( $this->modules as $name ) {
			$module = $rl->getModule( $name );
			if ( !$module ) {
				continue;
			}

			$group = $module->getGroup();

			if ( $group === 'private' ) {
				// Embed via mw.loader.implement per T36907.
				$data['embed']['general'][] = $name;
				// Avoid duplicate request from mw.loader
				$data['states'][$name] = 'loading';
			} else {
				// Load via mw.loader.load()
				$data['general'][] = $name;
			}
		}

		foreach ( $this->moduleStyles as $name ) {
			$module = $rl->getModule( $name );
			if ( !$module ) {
				continue;
			}

			if ( $module->getType() !== ResourceLoaderModule::LOAD_STYLES ) {
				$logger = $rl->getLogger();
				$logger->warning( 'Unexpected general module "{module}" in styles queue.', [
					'module' => $name,
				] );
			} else {
				// Stylesheet doesn't trigger mw.loader callback.
				// Set "ready" state to allow dependencies and avoid duplicate requests. (T87871)
				$data['states'][$name] = 'ready';
			}

			$group = $module->getGroup();
			$context = $this->getContext( $group, ResourceLoaderModule::TYPE_STYLES );
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
			$context = $this->getContext( $group, ResourceLoaderModule::TYPE_SCRIPTS );
			if ( $module->isKnownEmpty( $context ) ) {
				// Avoid needless request for empty module
				$data['states'][$name] = 'ready';
			} else {
				// Load from load.php?only=scripts via <script src></script>
				$data['scripts'][] = $name;

				// Avoid duplicate request from mw.loader
				$data['states'][$name] = 'loading';
			}
		}

		return $data;
	}

	/**
	 * @return array Attribute key-value pairs for the HTML document element
	 */
	public function getDocumentAttributes() {
		return [ 'class' => 'client-nojs' ];
	}

	/**
	 * The order of elements in the head is as follows:
	 * - Inline scripts.
	 * - Stylesheets.
	 * - Async external script-src.
	 *
	 * Reasons:
	 * - Script execution may be blocked on preceeding stylesheets.
	 * - Async scripts are not blocked on stylesheets.
	 * - Inline scripts can't be asynchronous.
	 * - For styles, earlier is better.
	 *
	 * @return string|WrappedStringList HTML
	 */
	public function getHeadHtml() {
		$data = $this->getData();
		$chunks = [];

		// Change "client-nojs" class to client-js. This allows easy toggling of UI components.
		// This happens synchronously on every page view to avoid flashes of wrong content.
		// See also #getDocumentAttributes() and /resources/src/startup.js.
		$chunks[] = Html::inlineScript(
			'document.documentElement.className = document.documentElement.className'
			. '.replace( /(^|\s)client-nojs(\s|$)/, "$1client-js$2" );'
		);

		// Inline RLQ: Set page variables
		if ( $this->config ) {
			$chunks[] = ResourceLoader::makeInlineScript(
				ResourceLoader::makeConfigSetScript( $this->config )
			);
		}

		// Inline RLQ: Initial module states
		$states = array_merge( $this->exemptStates, $data['states'] );
		if ( $states ) {
			$chunks[] = ResourceLoader::makeInlineScript(
				ResourceLoader::makeLoaderStateScript( $states )
			);
		}

		// Inline RLQ: Embedded modules
		if ( $data['embed']['general'] ) {
			$chunks[] = $this->getLoad(
				$data['embed']['general'],
				ResourceLoaderModule::TYPE_COMBINED
			);
		}

		// Inline RLQ: Load general modules
		if ( $data['general'] ) {
			$chunks[] = ResourceLoader::makeInlineScript(
				Xml::encodeJsCall( 'mw.loader.load', [ $data['general'] ] )
			);
		}

		// Inline RLQ: Load only=scripts
		if ( $data['scripts'] ) {
			$chunks[] = $this->getLoad(
				$data['scripts'],
				ResourceLoaderModule::TYPE_SCRIPTS
			);
		}

		// External stylesheets
		if ( $data['styles'] ) {
			$chunks[] = $this->getLoad(
				$data['styles'],
				ResourceLoaderModule::TYPE_STYLES
			);
		}

		// Inline stylesheets (embedded only=styles)
		if ( $data['embed']['styles'] ) {
			$chunks[] = $this->getLoad(
				$data['embed']['styles'],
				ResourceLoaderModule::TYPE_STYLES
			);
		}

		// Async scripts. Once the startup is loaded, inline RLQ scripts will run.
		// Pass-through a custom target from OutputPage (T143066).
		$startupQuery = $this->target ? [ 'target' => $this->target ] : [];
		$chunks[] = $this->getLoad(
			'startup',
			ResourceLoaderModule::TYPE_SCRIPTS,
			$startupQuery
		);

		return WrappedStringList::join( "\n", $chunks );
	}

	/**
	 * @return string|WrappedStringList HTML
	 */
	public function getBodyHtml() {
		return '';
	}

	private function getContext( $group, $type ) {
		return self::makeContext( $this->context, $group, $type );
	}

	private function getLoad( $modules, $only, array $extraQuery = [] ) {
		return self::makeLoad( $this->context, (array)$modules, $only, $extraQuery );
	}

	private static function makeContext( ResourceLoaderContext $mainContext, $group, $type,
		array $extraQuery = []
	) {
		// Create new ResourceLoaderContext so that $extraQuery may trigger isRaw().
		$req = new FauxRequest( array_merge( $mainContext->getRequest()->getValues(), $extraQuery ) );
		// Set 'only' if not combined
		$req->setVal( 'only', $type === ResourceLoaderModule::TYPE_COMBINED ? null : $type );
		// Remove user parameter in most cases
		if ( $group !== 'user' && $group !== 'private' ) {
			$req->setVal( 'user', null );
		}
		$context = new ResourceLoaderContext( $mainContext->getResourceLoader(), $req );
		// Allow caller to setVersion() and setModules()
		return new DerivativeResourceLoaderContext( $context );
	}

	/**
	 * Explicily load or embed modules on a page.
	 *
	 * @param ResourceLoaderContext $mainContext
	 * @param array $modules One or more module names
	 * @param string $only ResourceLoaderModule TYPE_ class constant
	 * @param array $extraQuery [optional] Array with extra query parameters for the request
	 * @return string|WrappedStringList HTML
	 */
	public static function makeLoad( ResourceLoaderContext $mainContext, array $modules, $only,
		array $extraQuery = []
	) {
		$rl = $mainContext->getResourceLoader();
		$chunks = [];

		// Sort module names so requests are more uniform
		sort( $modules );

		if ( $mainContext->getDebug() && count( $modules ) > 1 ) {

			$chunks = [];
			// Recursively call us for every item
			foreach ( $modules as $name ) {
				$chunks[] = self::makeLoad( $mainContext, [ $name ], $only, $extraQuery );
			}
			return new WrappedStringList( "\n", $chunks );
		}

		// Create keyed-by-source and then keyed-by-group list of module objects from modules list
		$sortedModules = [];
		foreach ( $modules as $name ) {
			$module = $rl->getModule( $name );
			if ( !$module ) {
				$rl->getLogger()->warning( 'Unknown module "{module}"', [ 'module' => $name ] );
				continue;
			}
			$sortedModules[$module->getSource()][$module->getGroup()][$name] = $module;
		}

		foreach ( $sortedModules as $source => $groups ) {
			foreach ( $groups as $group => $grpModules ) {
				$context = self::makeContext( $mainContext, $group, $only, $extraQuery );
				$context->setModules( array_keys( $grpModules ) );

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
				// This should NOT be done for the site group (T29564) because anons get that too
				// and we shouldn't be putting timestamps in CDN-cached HTML
				if ( $group === 'user' ) {
					// Must setModules() before makeVersionQuery()
					$context->setVersion( $rl->makeVersionQuery( $context ) );
				}

				$url = $rl->createLoaderURL( $source, $context, $extraQuery );

				// Decide whether to use 'style' or 'script' element
				if ( $only === ResourceLoaderModule::TYPE_STYLES ) {
					$chunk = Html::linkedStyle( $url );
				} else {
					if ( $context->getRaw() || $isRaw ) {
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

		return new WrappedStringList( "\n", $chunks );
	}
}
