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
		$out = $this->getOutput();

		$this->setHeaders();
		$out->disallowUserJs();

		// This special page is disabled by default ($wgEnableJavaScriptTest), and contains
		// no sensitive data. In order to allow TestSwarm to embed it into a test client window,
		// we need to allow iframing of this page.
		$out->allowClickjacking();

		// Sub resource: Internal JavaScript export bundle for QUnit
		if ( $par === 'qunit/export' ) {
			$this->exportQUnit();
			return;
		}

		// Regular view: QUnit test runner
		// (Support "/qunit" and "/qunit/plain" for backwards compatibility)
		if ( $par === null || $par === '' || $par === 'qunit' || $par === 'qunit/plain' ) {
			$this->plainQUnit();
			return;
		}

		// Unknown action
		$out->setStatusCode( 404 );
		$out->setPageTitle( $this->msg( 'javascripttest' ) );
		$out->addHTML(
			'<div class="error">'
			. $this->msg( 'javascripttest-pagetext-unknownaction' )
				->plaintextParams( $par )->parseAsBlock()
			. '</div>'
		);
	}

	/**
	 * Get summary text wrapped in a container
	 *
	 * @return string HTML
	 */
	private function getSummaryHtml() {
		$summary = $this->msg( 'javascripttest-qunit-intro' )
			->params( 'https://www.mediawiki.org/wiki/Manual:JavaScript_unit_testing' )
			->parseAsBlock();
		return "<div id=\"mw-javascripttest-summary\">$summary</div>";
	}

	/**
	 * Generate self-sufficient JavaScript payload to run the tests elsewhere.
	 *
	 * Includes startup module to request modules from ResourceLoader.
	 *
	 * Note: This modifies the registry to replace 'jquery.qunit' with an
	 * empty module to allow external environment to preload QUnit with any
	 * neccecary framework adapters (e.g. Karma). Loading it again would
	 * re-define QUnit and dereference event handlers from Karma.
	 */
	private function exportQUnit() {
		$out = $this->getOutput();
		$out->disable();

		$rl = $out->getResourceLoader();

		$query = [
			'lang' => $this->getLanguage()->getCode(),
			'skin' => $this->getSkin()->getSkinName(),
			'debug' => ResourceLoader::inDebugMode() ? 'true' : 'false',
			'target' => 'test',
		];
		$embedContext = new ResourceLoaderContext( $rl, new FauxRequest( $query ) );
		$query['only'] = 'scripts';
		$startupContext = new ResourceLoaderContext( $rl, new FauxRequest( $query ) );

		$query['raw'] = true;

		$modules = $rl->getTestModuleNames( 'qunit' );

		// Disable autostart because we load modules asynchronously. By default, QUnit would start
		// at domready when there are no tests loaded and also fire 'QUnit.done' which then instructs
		// Karma to end the run before the tests even started.
		$qunitConfig = 'QUnit.config.autostart = false;'
			. 'if (window.__karma__) {'
			// karma-qunit's use of autostart=false and QUnit.start conflicts with ours.
			// Hack around this by replacing 'karma.loaded' with a no-op and call it ourselves later.
			// See <https://github.com/karma-runner/karma-qunit/issues/27>.
			. 'window.__karma__.loaded = function () {};'
			. '}';

		// The below is essentially a pure-javascript version of OutputPage::headElement().
		$startup = $rl->makeModuleResponse( $startupContext, [
			'startup' => $rl->getModule( 'startup' ),
		] );
		// Embed page-specific mw.config variables.
		// The current Special page shouldn't be relevant to tests, but various modules (which
		// are loaded before the test suites), reference mw.config while initialising.
		$code = ResourceLoader::makeConfigSetScript( $out->getJSVars() );
		// Embed private modules as they're not allowed to be loaded dynamically
		$code .= $rl->makeModuleResponse( $embedContext, [
			'user.options' => $rl->getModule( 'user.options' ),
			'user.tokens' => $rl->getModule( 'user.tokens' ),
		] );
		// Catch exceptions (such as "dependency missing" or "unknown module") so that we
		// always start QUnit. Re-throw so that they are caught and reported as global exceptions
		// by QUnit and Karma.
		$code .= '(function () {'
			. 'var start = window.__karma__ ? window.__karma__.start : QUnit.start;'
			. 'try {'
			. 'mw.loader.using( ' . Xml::encodeJsVar( $modules ) . ' ).always( start );'
			. '} catch ( e ) { start(); throw e; }'
			. '}());';

		header( 'Content-Type: text/javascript; charset=utf-8' );
		header( 'Cache-Control: private, no-cache, must-revalidate' );
		header( 'Pragma: no-cache' );
		echo $qunitConfig;
		echo $startup;
		// The following has to be deferred via RLQ because the startup module is asynchronous.
		echo ResourceLoader::makeLoaderConditionalScript( $code );
	}

	private function plainQUnit() {
		$out = $this->getOutput();
		$out->disable();

		$styles = $out->makeResourceLoaderLink( 'jquery.qunit',
			ResourceLoaderModule::TYPE_STYLES
		);

		// Use 'raw' because QUnit loads before ResourceLoader initialises (omit mw.loader.state call)
		// Use 'test' to ensure OutputPage doesn't use the "async" attribute because QUnit must
		// load before qunit/export.
		$scripts = $out->makeResourceLoaderLink( 'jquery.qunit',
			ResourceLoaderModule::TYPE_SCRIPTS,
			[ 'raw' => true, 'sync' => true ]
		);

		$head = implode( "\n", [ $styles, $scripts ] );
		$summary = $this->getSummaryHtml();
		$html = <<<HTML
<!DOCTYPE html>
<title>QUnit</title>
$head
$summary
<div id="qunit"></div>
HTML;

		$url = $this->getPageTitle( 'qunit/export' )->getFullURL( [
			'debug' => ResourceLoader::inDebugMode() ? 'true' : 'false',
		] );
		$html .= "\n" . Html::linkedScript( $url );

		header( 'Content-Type: text/html; charset=utf-8' );
		echo $html;
	}

	/**
	 * Return an array of subpages that this special page will accept.
	 *
	 * @return string[] subpages
	 */
	public function getSubpagesForPrefixSearch() {
		return self::$frameworks;
	}

	protected function getGroupName() {
		return 'other';
	}
}
