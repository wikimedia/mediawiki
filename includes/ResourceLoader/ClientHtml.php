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

namespace MediaWiki\ResourceLoader;

use MediaWiki\Html\Html;
use Wikimedia\WrappedString;
use Wikimedia\WrappedStringList;

/**
 * Load and configure a ResourceLoader client on an HTML page.
 *
 * @ingroup ResourceLoader
 * @since 1.28
 */
class ClientHtml {
	/** @var Context */
	private $context;

	/** @var ResourceLoader */
	private $resourceLoader;

	/** @var array<string,string|null|false> */
	private $options;

	/** @var array<string,mixed> */
	private $config = [];

	/** @var string[] */
	private $modules = [];

	/** @var string[] */
	private $moduleStyles = [];

	/** @var array<string,string> */
	private $exemptStates = [];

	/** @var array */
	private $data;

	/**
	 * @param Context $context
	 * @param array $options [optional] Array of options
	 *  - 'target': Parameter for modules=startup request, see StartUpModule.
	 *  - 'safemode': Parameter for modules=startup request, see StartUpModule.
	 *  - 'clientPrefEnabled': See Skin options.
	 *  - 'clientPrefCookiePrefix': See $wgCookiePrefix.
	 */
	public function __construct( Context $context, array $options = [] ) {
		$this->context = $context;
		$this->resourceLoader = $context->getResourceLoader();
		$this->options = $options + [
			'target' => null,
			'safemode' => null,
			'clientPrefEnabled' => false,
			'clientPrefCookiePrefix' => '',
		];
	}

	/**
	 * Set mw.config variables.
	 *
	 * @param array $vars Array of key/value pairs
	 */
	public function setConfig( array $vars ): void {
		foreach ( $vars as $key => $value ) {
			$this->config[$key] = $value;
		}
	}

	/**
	 * Ensure one or more modules are loaded.
	 *
	 * @param string[] $modules Array of module names
	 */
	public function setModules( array $modules ): void {
		$this->modules = $modules;
	}

	/**
	 * Ensure the styles of one or more modules are loaded.
	 *
	 * @param string[] $modules Array of module names
	 */
	public function setModuleStyles( array $modules ): void {
		$this->moduleStyles = $modules;
	}

	/**
	 * Set state of special modules that are handled by the caller manually.
	 *
	 * See OutputPage::buildExemptModules() for use cases.
	 *
	 * @param array<string,string> $states Module state keyed by module name
	 */
	public function setExemptStates( array $states ): void {
		$this->exemptStates = $states;
	}

	private function getData(): array {
		if ( $this->data ) {
			return $this->data;
		}

		$rl = $this->resourceLoader;
		$data = [
			'states' => [
				// moduleName => state
			],
			'general' => [],
			'styles' => [],
			// Embedding for private modules
			'embed' => [
				'styles' => [],
				'general' => [],
			],
			// Deprecation warnings for style-only modules
			'styleDeprecations' => [],
		];

		foreach ( $this->modules as $name ) {
			$module = $rl->getModule( $name );
			if ( !$module ) {
				continue;
			}

			$group = $module->getGroup();
			$context = $this->getContext( $group, Module::TYPE_COMBINED );
			$shouldEmbed = $module->shouldEmbedModule( $this->context );

			if ( ( $group === Module::GROUP_USER || $shouldEmbed ) &&
				$module->isKnownEmpty( $context ) ) {
				// This is a user-specific or embedded module, which means its output
				// can be specific to the current page or user. As such, we can optimise
				// the way we load it based on the current version of the module.
				// Avoid needless embed for empty module, preset ready state.
				$data['states'][$name] = 'ready';
			} elseif ( $group === Module::GROUP_USER || $shouldEmbed ) {
				// - For group=user: We need to provide a pre-generated load.php
				//   url to the client that has the 'user' and 'version' parameters
				//   filled in. Without this, the client would wrongly use the static
				//   version hash, per T64602.
				// - For shouldEmbed=true:  Embed via mw.loader.impl, per T36907.
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

			if ( $module->getType() !== Module::LOAD_STYLES ) {
				$logger = $rl->getLogger();
				$logger->error( 'Unexpected general module "{module}" in styles queue.', [
					'module' => $name,
				] );
				continue;
			}

			// Stylesheet doesn't trigger mw.loader callback.
			// Set "ready" state to allow script modules to depend on this module  (T87871).
			// And to avoid duplicate requests at run-time from mw.loader.
			//
			// Optimization: Exclude state for "noscript" modules. Since these are also excluded
			// from the startup registry, no need to send their states (T291735).
			$group = $module->getGroup();
			if ( $group !== Module::GROUP_NOSCRIPT ) {
				$data['states'][$name] = 'ready';
			}

			$context = $this->getContext( $group, Module::TYPE_STYLES );
			if ( $module->shouldEmbedModule( $this->context ) ) {
				// Avoid needless embed for private embeds we know are empty.
				// (Set "ready" state directly instead, which we do a few lines above.)
				if ( !$module->isKnownEmpty( $context ) ) {
					// Embed via <style> element
					$data['embed']['styles'][] = $name;
				}
			// For other style modules, always request them, regardless of whether they are
			// currently known to be empty. Because:
			// 1. Those modules are requested in batch, so there is no extra request overhead
			//    or extra HTML element to be avoided.
			// 2. Checking isKnownEmpty for those can be expensive and slow down page view
			//    generation (T230260).
			// 3. We don't want cached HTML to vary on the current state of a module.
			//    If the module becomes non-empty a few minutes later, it should start working
			//    on cached HTML without requiring a purge.
			//
			// But, user-specific modules:
			// * ... are used on page views not publicly cached.
			// * ... are in their own group and thus a require a request we can avoid
			// * ... have known-empty status preloaded by ResourceLoader.
			} elseif ( $group !== Module::GROUP_USER || !$module->isKnownEmpty( $context ) ) {
				// Load from load.php?only=styles via <link rel=stylesheet>
				$data['styles'][] = $name;
			}
			$deprecation = $module->getDeprecationWarning();
			if ( $deprecation ) {
				$data['styleDeprecations'][] = $deprecation;
			}
		}

		$this->data = $data;

		return $this->data;
	}

	/**
	 * @return array<string,string> Attributes pairs for the HTML document element
	 */
	public function getDocumentAttributes() {
		return [ 'class' => 'client-nojs' ];
	}

	/**
	 * Set relevant classes on document.documentElement
	 *
	 * @param string|null $nojsClass Class name that Skin will set on HTML document
	 * @return string
	 */
	private function getDocumentClassNameScript( $nojsClass ) {
		// Change "client-nojs" to "client-js".
		// This enables server rendering of UI components, even for those that should be hidden
		// in Grade C where JavaScript is unsupported, whilst avoiding a flash of wrong content.
		//
		// See also Skin:getHtmlElementAttributes() and startup/startup.js.
		//
		// Optimisation: Produce shorter and faster JS by only writing to DOM.
		// This is possible because Skin informs RL about the final value of <html class>, and
		// because RL already controls the first element in HTML <head> for performance reasons.
		// - Avoid reading HTMLElement.className
		// - Avoid executing JS regexes in the common case, by doing the string replace in PHP.
		$nojsClass ??= $this->getDocumentAttributes()['class'];
		$jsClass = preg_replace( '/(^|\s)client-nojs(\s|$)/', '$1client-js$2', $nojsClass );
		$jsClassJson = $this->context->encodeJson( $jsClass );

		// Apply client preferences stored by mw.user.clientPrefs as "class1,class2", where each
		// item is an <html> class following the pattern "<key>-clientpref-<value>" (T339268)
		if ( $this->options['clientPrefEnabled'] ) {
			$cookiePrefix = $this->options['clientPrefCookiePrefix'];
			$script = strtr(
				file_get_contents( MW_INSTALL_PATH . '/resources/src/startup/clientprefs.js' ),
				[
					'$VARS.jsClass' => $jsClassJson,
					'__COOKIE_PREFIX__' => $cookiePrefix,
				]
			);
		} else {
			$script = "document.documentElement.className = {$jsClassJson};";
		}

		return $script;
	}

	/**
	 * The order of elements in the head is as follows:
	 * - Inline scripts.
	 * - Stylesheets.
	 * - Async external script-src.
	 *
	 * Reasons:
	 * - Script execution may be blocked on preceding stylesheets.
	 * - Async scripts are not blocked on stylesheets.
	 * - Inline scripts can't be asynchronous.
	 * - For styles, earlier is better.
	 *
	 * @param string|null $nojsClass Class name that caller uses on HTML document element
	 * @return string|WrappedStringList HTML
	 */
	public function getHeadHtml( $nojsClass = null ) {
		$script = $this->getDocumentClassNameScript( $nojsClass );

		// Inline script: Declare mw.config variables for this page.
		if ( $this->config ) {
			$confJson = $this->context->encodeJson( $this->config );
			$script .= "
RLCONF = {$confJson};
";
		}

		$data = $this->getData();

		// Inline script: Declare initial module states for this page.
		$states = array_merge( $this->exemptStates, $data['states'] );
		if ( $states ) {
			$stateJson = $this->context->encodeJson( $states );
			$script .= "
RLSTATE = {$stateJson};
";
		}

		// Inline script: Declare general modules to load on this page.
		if ( $data['general'] ) {
			$pageModulesJson = $this->context->encodeJson( $data['general'] );
			$script .= "
RLPAGEMODULES = {$pageModulesJson};
";
		}

		if ( !$this->context->getDebug() ) {
			$script = ResourceLoader::filter( 'minify-js', $script, [ 'cache' => false ] );
		}

		$chunks = [];
		$chunks[] = Html::inlineScript( $script );

		// Inline RLQ: Embedded modules
		if ( $data['embed']['general'] ) {
			$chunks[] = $this->getLoad(
				$data['embed']['general'],
				Module::TYPE_COMBINED
			);
		}

		// External stylesheets (only=styles)
		if ( $data['styles'] ) {
			$chunks[] = $this->getLoad(
				$data['styles'],
				Module::TYPE_STYLES
			);
		}

		// Inline stylesheets (embedded only=styles)
		if ( $data['embed']['styles'] ) {
			$chunks[] = $this->getLoad(
				$data['embed']['styles'],
				Module::TYPE_STYLES
			);
		}

		// Async scripts. Once the startup is loaded, inline RLQ scripts will run.
		// Pass-through a custom 'target' from OutputPage (T143066).
		$startupQuery = [ 'raw' => '1' ];
		foreach ( [ 'target', 'safemode' ] as $param ) {
			if ( $this->options[$param] !== null ) {
				$startupQuery[$param] = (string)$this->options[$param];
			}
		}
		$chunks[] = $this->getLoad(
			'startup',
			Module::TYPE_SCRIPTS,
			$startupQuery
		);

		return WrappedString::join( "\n", $chunks );
	}

	/**
	 * @return string|WrappedStringList HTML
	 */
	public function getBodyHtml() {
		$data = $this->getData();
		$chunks = [];

		// Deprecations for only=styles modules
		if ( $data['styleDeprecations'] ) {
			$calls = '';
			foreach ( $data['styleDeprecations'] as $warning ) {
				$calls .= Html::encodeJsCall( 'mw.log.warn', [ $warning ] );
			}
			$chunks[] = ResourceLoader::makeInlineScript( $calls );
		}

		return WrappedString::join( "\n", $chunks );
	}

	private function getContext( $group, $type ): Context {
		return self::makeContext( $this->context, $group, $type );
	}

	private function getLoad( $modules, $only, array $extraQuery = [] ) {
		return self::makeLoad( $this->context, (array)$modules, $only, $extraQuery );
	}

	private static function makeContext( Context $mainContext, $group, $type,
		array $extraQuery = []
	): DerivativeContext {
		// Allow caller to setVersion() and setModules()
		$ret = new DerivativeContext( $mainContext );
		// Set 'only' if not combined
		$ret->setOnly( $type === Module::TYPE_COMBINED ? null : $type );
		// Remove user parameter in most cases
		if ( $group !== Module::GROUP_USER && $group !== Module::GROUP_PRIVATE ) {
			$ret->setUser( null );
		}
		if ( isset( $extraQuery['raw'] ) ) {
			$ret->setRaw( true );
		}
		return $ret;
	}

	/**
	 * Explicitly load or embed modules on a page.
	 *
	 * @param Context $mainContext
	 * @param string[] $modules One or more module names
	 * @param string $only Module TYPE_ class constant
	 * @param array $extraQuery [optional] Array with extra query parameters for the request
	 * @return string|WrappedStringList HTML
	 */
	public static function makeLoad( Context $mainContext, array $modules, $only,
		array $extraQuery = []
	) {
		$rl = $mainContext->getResourceLoader();
		$chunks = [];

		// Sort module names so requests are more uniform
		sort( $modules );

		if ( $mainContext->getDebug() && count( $modules ) > 1 ) {
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

				// Separate sets of linked and embedded modules while preserving order
				$moduleSets = [];
				$idx = -1;
				foreach ( $grpModules as $name => $module ) {
					$shouldEmbed = $module->shouldEmbedModule( $context );
					if ( !$moduleSets || $moduleSets[$idx][0] !== $shouldEmbed ) {
						$moduleSets[++$idx] = [ $shouldEmbed, [] ];
					}
					$moduleSets[$idx][1][$name] = $module;
				}

				// Link/embed each set
				foreach ( $moduleSets as [ $embed, $moduleSet ] ) {
					$moduleSetNames = array_keys( $moduleSet );
					$context->setModules( $moduleSetNames );
					if ( $embed ) {
						$response = $rl->makeModuleResponse( $context, $moduleSet );
						// Decide whether to use style or script element
						if ( $only == Module::TYPE_STYLES ) {
							$chunks[] = Html::inlineStyle( $response );
						} else {
							$chunks[] = ResourceLoader::makeInlineScript( $response );
						}
					} else {
						// Not embedded

						// Special handling for the user group; because users might change their stuff
						// on-wiki like user pages, or user preferences; we need to find the highest
						// timestamp of these user-changeable modules so we can ensure cache misses on change
						// This should NOT be done for the site group (T29564) because anons get that too
						// and we shouldn't be putting timestamps in CDN-cached HTML
						if ( $group === Module::GROUP_USER ) {
							$context->setVersion( $rl->makeVersionQuery( $context, $moduleSetNames ) );
						}

						// Must setModules() before createLoaderURL()
						$url = $rl->createLoaderURL( $source, $context, $extraQuery );

						// Decide whether to use 'style' or 'script' element
						if ( $only === Module::TYPE_STYLES ) {
							$chunk = Html::linkedStyle( $url );
						} elseif ( $context->getRaw() ) {
							/** Fandom change - start **/
							$config = $mainContext->getResourceLoader()->getConfig();
							$useDefer = $config->get( 'ResourceLoaderUseDeferScripts' );
							// This request is asking for the module to be delivered standalone,
							// (aka "raw") without communicating to any mw.loader client.
							// For:
							// - startup (naturally because this is what will define mw.loader)
							$chunk = Html::element( 'script', [
								$useDefer ? 'defer' : 'async' => true,
								'src' => $url,
							] );
							/** Fandom change - end **/
						} else {
							$chunk = ResourceLoader::makeInlineScript(
								'mw.loader.load(' . $mainContext->encodeJson( $url ) . ');'
							);
						}

						if ( $group == Module::GROUP_NOSCRIPT ) {
							$chunks[] = Html::rawElement( 'noscript', [], $chunk );
						} else {
							$chunks[] = $chunk;
						}
					}
				}
			}
		}

		return new WrappedStringList( "\n", $chunks );
	}
}
