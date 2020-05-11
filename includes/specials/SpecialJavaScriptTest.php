<?php
/**
 * Implements Special:JavaScriptTest
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
 * @ingroup SpecialPage
 */

/**
 * @ingroup SpecialPage
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

	/**
	 * Send the standalone JavaScript payload.
	 *
	 * Loaded by the GUI (on Special:JavacriptTest), and by the CLI (via grunt-karma).
	 */
	private function exportJS() {
		$out = $this->getOutput();
		$rl = $out->getResourceLoader();

		// Allow framing (disabling wgBreakFrames). Otherwise, mediawiki.page.startup.js
		// will close this tab when run from CLI using karma-qunit.
		$out->allowClickjacking();

		$query = [
			'lang' => 'qqx',
			'skin' => 'fallback',
			'debug' => ResourceLoader::inDebugMode() ? 'true' : 'false',
			'target' => 'test',
		];
		$embedContext = new ResourceLoaderContext( $rl, new FauxRequest( $query ) );
		$query['only'] = 'scripts';
		$startupContext = new ResourceLoaderContext( $rl, new FauxRequest( $query ) );

		$modules = $rl->getTestSuiteModuleNames();

		// Disable module storage.
		// The unit test for mw.loader.store will enable it (with a mock timers).
		$config = new MultiConfig( [
			new HashConfig( [ 'ResourceLoaderStorageEnabled' => false ] ),
			$rl->getConfig(),
		] );

		// Disable autostart because we load modules asynchronously. By default, QUnit would start
		// at domready when there are no tests loaded and also fire 'QUnit.done' which then instructs
		// Karma to exit the browser process before the tests even finished loading.
		$qunitConfig = 'QUnit.config.autostart = false;'
			. 'if (window.__karma__) {'
			// karma-qunit's use of autostart=false and QUnit.start conflicts with ours.
			// Hack around this by replacing 'karma.loaded' with a no-op and perform its duty of calling
			// `__karma__.start()` ourselves. See <https://github.com/karma-runner/karma-qunit/issues/27>.
			. 'window.__karma__.loaded = function () {};'
			. '}';

		// The below is essentially a pure-javascript version of OutputPage::headElement().
		$startupModule = $rl->getModule( 'startup' );
		$startupModule->setConfig( $config );
		$code = $rl->makeModuleResponse( $startupContext, [ 'startup' => $startupModule ] );
		// The following has to be deferred via RLQ because the startup module is asynchronous.
		$code .= ResourceLoader::makeLoaderConditionalScript(
			// Embed page-specific mw.config variables.
			// The current Special page shouldn't be relevant to tests, but various modules (which
			// are loaded before the test suites), reference mw.config while initialising.
			ResourceLoader::makeConfigSetScript( $out->getJSVars() )
			// Embed private modules as they're not allowed to be loaded dynamically
			. $rl->makeModuleResponse( $embedContext, [
				'user.options' => $rl->getModule( 'user.options' ),
			] )
			// Load all the test suites
			. Xml::encodeJsCall( 'mw.loader.load', [ $modules ] )
		);
		$encModules = Xml::encodeJsVar( $modules );
		$code .= ResourceLoader::makeInlineCodeWithModule( 'mediawiki.base', <<<JAVASCRIPT
	var start = window.__karma__ ? window.__karma__.start : QUnit.start;
	mw.loader.using( $encModules ).always( start );
	mw.trackSubscribe( 'resourceloader.exception', function ( topic, err ) {
		// Things like "dependency missing" or "unknown module".
		// Re-throw so that they are reported as global exceptions by QUnit and Karma.
		setTimeout( function () {
			throw err;
		} );
	} );
JAVASCRIPT
	);

		header( 'Content-Type: text/javascript; charset=utf-8' );
		header( 'Cache-Control: private, no-cache, must-revalidate' );
		header( 'Pragma: no-cache' );
		echo $qunitConfig;
		echo $code;
	}

	private function renderPage() {
		$basePath = $this->getConfig()->get( 'ResourceBasePath' );
		$headHtml = implode( "\n", [
			Html::linkedScript( "$basePath/resources/lib/qunitjs/qunit.js" ),
			Html::linkedStyle( "$basePath/resources/lib/qunitjs/qunit.css" ),
			Html::linkedStyle( "$basePath/resources/src/qunitjs/qunit-local.css" ),
		] );

		$introHtml = $this->msg( 'javascripttest-qunit-intro' )
			->params( 'https://www.mediawiki.org/wiki/Manual:JavaScript_unit_testing' )
			->parseAsBlock();

		$scriptUrl = $this->getPageTitle( 'qunit/export' )->getFullURL( [
			'debug' => ResourceLoader::inDebugMode() ? 'true' : 'false',
		] );
		$script = Html::linkedScript( $scriptUrl );

		header( 'Content-Type: text/html; charset=utf-8' );
		echo <<<HTML
<!DOCTYPE html>
<title>QUnit</title>
$headHtml
$introHtml
<div id="qunit"></div>
$script
HTML;
	}

	protected function getGroupName() {
		return 'other';
	}
}
