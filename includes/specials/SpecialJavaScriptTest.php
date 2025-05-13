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

namespace MediaWiki\Specials;

use MediaWiki\Config\HashConfig;
use MediaWiki\Config\MultiConfig;
use MediaWiki\Exception\HttpError;
use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\Request\FauxRequest;
use MediaWiki\ResourceLoader as RL;
use MediaWiki\ResourceLoader\ResourceLoader;
use MediaWiki\SpecialPage\SpecialPage;

/**
 * @ingroup SpecialPage
 * @ingroup ResourceLoader
 */
class SpecialJavaScriptTest extends SpecialPage {

	public function __construct() {
		parent::__construct( 'JavaScriptTest' );
	}

	public function execute( $par ) {
		$this->getOutput()->disable();

		if ( $par === 'qunit/export' ) {
			// Send the JavaScript payload.
			$this->exportJS();
		} elseif ( $par === null || $par === '' || $par === 'qunit' || $par === 'qunit/plain' ) {
			// Render the page
			// (Support "/qunit" and "/qunit/plain" for backwards-compatibility)
			$this->renderPage();
		} else {
			wfHttpError( 404, 'Unknown action', "Unknown action \"$par\"." );
		}
	}

	private function getComponents(): array {
		$components = [];

		$rl = $this->getOutput()->getResourceLoader();
		foreach ( $rl->getTestSuiteModuleNames() as $module ) {
			if ( str_starts_with( $module, 'test.' ) ) {
				$components[] = substr( $module, 5 );
			}
		}

		return $components;
	}

	/**
	 * Used on both GUI (renderPage, Special:JavaScriptTest) and CLI (exposeJS, via Gruntfile.js).
	 */
	private function getModulesForComponentOrThrow( ?string $component ): array {
		if ( $component !== null ) {
			if ( !in_array( $component, $this->getComponents() ) ) {
				throw new HttpError(
					404,
					"No test module found for the '$component' component.\n"
						. "Make sure the extension is enabled via wfLoadExtension(),\n"
						. "and register a test module via the QUnitTestModule attribute in extension.json.",
					'Unknown component',
				);
			}
			return [ 'test.' . $component ];
		} else {
			$rl = $this->getOutput()->getResourceLoader();
			return $rl->getTestSuiteModuleNames();
		}
	}

	/**
	 * Send the standalone JavaScript payload.
	 *
	 * Loaded by the GUI (on Special:JavaScriptTest), and by the CLI (via grunt-karma).
	 */
	private function exportJS() {
		$out = $this->getOutput();
		$req = $this->getContext()->getRequest();
		$rl = $out->getResourceLoader();

		// Allow framing (disabling wgBreakFrames). Otherwise, mediawiki.page.ready
		// will close this tab when running from CLI using karma-qunit.
		$out->getMetadata()->setPreventClickjacking( false );

		$query = [
			'lang' => 'qqx',
			'skin' => 'fallback',
			'debug' => $req->getRawVal( 'debug' ),
			'target' => 'test',
		];
		$embedContext = new RL\Context( $rl, new FauxRequest( $query ) );
		$query['only'] = 'scripts';
		$startupContext = new RL\Context( $rl, new FauxRequest( $query ) );

		$component = $req->getRawVal( 'component' );
		$modules = $this->getModulesForComponentOrThrow( $component );

		// Disable module storage.
		// The unit test for mw.loader.store will enable it (with a mock timers).
		$config = new MultiConfig( [
			new HashConfig( [ MainConfigNames::ResourceLoaderStorageEnabled => false ] ),
			$rl->getConfig(),
		] );

		// The below is essentially a pure-javascript version of OutputPage::headElement().
		$startupModule = $rl->getModule( 'startup' );
		$startupModule->setConfig( $config );
		$code = $rl->makeModuleResponse( $startupContext, [ 'startup' => $startupModule ] );
		// The following has to be deferred via RLQ because the startup module is asynchronous.
		$code .= ResourceLoader::makeLoaderConditionalScript(
			// Embed page-specific mw.config variables.
			//
			// For compatibility with older tests, these will come from the user
			// action "viewing Special:JavaScripTest".
			//
			// This is deprecated since MediaWiki 1.25 and slowly being phased out in favour of:
			// 1. tests explicitly mocking the configuration they depend on.
			// 2. tests explicitly skipping or not loading code that is only meant
			//    for real page views (e.g. not loading as dependency, or using a QUnit
			//    conditional).
			//
			// See https://phabricator.wikimedia.org/T89434.
			// Keep a select few that are commonly referenced.
			ResourceLoader::makeConfigSetScript( [
				// used by mediawiki.util
				'wgPageName' => 'Special:Badtitle/JavaScriptTest',
				// used as input for mw.Title
				'wgRelevantPageName' => 'Special:Badtitle/JavaScriptTest',
				// used by testrunner.js for QUnit toolbar
				'wgTestModuleComponents' => $this->getComponents(),
			] )
			// Embed private modules as they're not allowed to be loaded dynamically
			. $rl->makeModuleResponse( $embedContext, [
				'user.options' => $rl->getModule( 'user.options' ),
			] )
			// Load all the test modules
			. Html::encodeJsCall( 'mw.loader.load', [ $modules ] )
		);
		$encModules = Html::encodeJsVar( $modules );
		$code .= ResourceLoader::makeInlineCodeWithModule( 'mediawiki.base', <<<JAVASCRIPT
	// Wait for each module individually, so that partial failures wont break the page
	// completely by rejecting the promise before all/ any modules are loaded.
	var promises = $encModules.map( function( module ) {
		return mw.loader.using( module ).promise();
	} );
	Promise.allSettled( promises ).then( QUnit.start );
JAVASCRIPT
		);

		header( 'Content-Type: text/javascript; charset=utf-8' );
		header( 'Cache-Control: private, no-cache, must-revalidate' );
		echo $code;
	}

	private function renderPage() {
		$req = $this->getContext()->getRequest();
		$component = $req->getRawVal( 'component' );
		// If set, validate
		$this->getModulesForComponentOrThrow( $component );

		$basePath = $this->getConfig()->get( MainConfigNames::ResourceBasePath );
		$headHtml = implode( "\n", [
			Html::linkedStyle( "$basePath/resources/lib/qunitjs/qunit.css" ),
			Html::linkedStyle( "$basePath/resources/src/qunitjs/qunit-local.css" ),
		] );

		$scriptUrl = $this->getPageTitle( 'qunit/export' )->getFullURL( [
			'debug' => $req->getRawVal( 'debug' ) ?? '0',
			'component' => $component,
		] );
		$script = implode( "\n", [
			Html::linkedScript( "$basePath/resources/lib/qunitjs/qunit.js" ),
			Html::inlineScript( 'QUnit.config.autostart = false;' ),
			Html::linkedScript( $scriptUrl ),
		] );

		header( 'Content-Type: text/html; charset=utf-8' );
		echo <<<HTML
<!DOCTYPE html>
<title>QUnit</title>
$headHtml
<div id="qunit"></div>
<div id="qunit-fixture"></div>
$script
HTML;
	}

	protected function getGroupName() {
		return 'other';
	}
}

/** @deprecated class alias since 1.41 */
class_alias( SpecialJavaScriptTest::class, 'SpecialJavaScriptTest' );
