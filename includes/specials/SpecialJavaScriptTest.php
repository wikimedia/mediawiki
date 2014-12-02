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
	/**
	 * @var array Supported frameworks.
	 */
	private static $frameworks = array(
		'qunit',
	);

	public function __construct() {
		parent::__construct( 'JavaScriptTest' );
	}

	public function execute( $par ) {
		$out = $this->getOutput();

		$this->setHeaders();
		$out->disallowUserJs();

		if ( $par === null ) {
			// No framework specified
			$out->setStatusCode( 404 );
			$out->setPageTitle( $this->msg( 'javascripttest' ) );
			$summary = $this->wrapSummaryHtml(
				$this->msg( 'javascripttest-pagetext-noframework' )->escaped() .
					$this->getFrameworkListHtml(),
				'noframework'
			);
			$out->addHtml( $summary );
			return;
		}

		// Determine framework and mode
		$pars = explode( '/', $par, 2 );

		$framework = $pars[0];
		if ( !in_array( $framework, self::$frameworks ) ) {
			// Framework not found
			$out->setStatusCode( 404 );
			$out->setPageTitle( $this->msg( 'javascripttest' ) );
			$summary = $this->wrapSummaryHtml(
				'<p class="error">' .
					$this->msg( 'javascripttest-pagetext-unknownframework', $par )->escaped() .
					'</p>' .
					$this->getFrameworkListHtml(),
				'unknownframework'
			);
			$out->addHtml( $summary );
			return;
		}

		// This special page is disabled by default ($wgEnableJavaScriptTest), and contains
		// no sensitive data. In order to allow TestSwarm to embed it into a test client window,
		// we need to allow iframing of this page.
		$out->allowClickjacking();

		// Custom modes
		if ( isset( $pars[1] ) ) {
			$mode = $pars[1];
			if ( !in_array( $mode, array( 'export', 'plain' ) ) ) {
				$out->setStatusCode( 404 );
				return;
			}
			$method = $mode . ucfirst( $framework );
			$this->$method();
			return;
		}

		$out->addModules( 'mediawiki.special.javaScriptTest' );

		$method = 'view' . ucfirst( $framework );
		$this->$method();
		$out->setPageTitle( $this->msg(
			'javascripttest-title',
			// Messages: javascripttest-qunit-name
			$this->msg( "javascripttest-$framework-name" )->plain()
		) );
		$out->setSubtitle(
			$this->msg( 'javascripttest-backlink' )
				->rawParams( Linker::linkKnown( $this->getPageTitle() ) )
		);
	}

	/**
	 * Get a list of frameworks (including introduction paragraph and links
	 * to the framework run pages)
	 *
	 * @return string HTML
	 */
	private function getFrameworkListHtml() {
		$list = '<ul>';
		foreach ( self::$frameworks as $framework ) {
			$list .= Html::rawElement(
				'li',
				array(),
				Linker::link(
					$this->getPageTitle( $framework ),
					// Message: javascripttest-qunit-name
					$this->msg( "javascripttest-$framework-name" )->escaped()
				)
			);
		}
		$list .= '</ul>';

		return $this->msg( 'javascripttest-pagetext-frameworks' )->rawParams( $list )
			->parseAsBlock();
	}

	/**
	 * Function to wrap the summary.
	 * It must be given a valid state as a second parameter or an exception will
	 * be thrown.
	 * @param string $html The raw HTML.
	 * @param string $state State, one of 'noframework', 'unknownframework' or 'frameworkfound'
	 * @throws MWException
	 * @return string
	 */
	private function wrapSummaryHtml( $html, $state ) {
		static $validStates = array( 'noframework', 'unknownframework', 'frameworkfound' );

		if ( !in_array( $state, $validStates ) ) {
			throw new MWException( __METHOD__
				. ' given an invalid state. Must be one of "'
				. join( '", "', $validStates ) . '".'
			);
		}

		return "<div id=\"mw-javascripttest-summary\" class=\"mw-javascripttest-$state\">$html</div>";
	}

	/**
	 * Run the test suite on the Special page.
	 *
	 * Rendered by OutputPage and Skin.
	 */
	private function viewQUnit() {
		$out = $this->getOutput();
		$testConfig = $this->getConfig()->get( 'JavaScriptTestConfig' );

		// Used in ./tests/qunit/data/testrunner.js, see also documentation of
		// $wgJavaScriptTestConfig in DefaultSettings.php
		$out->addJsConfigVars(
			'QUnitTestSwarmInjectJSPath',
			$testConfig['qunit']['testswarm-injectjs']
		);

		$modules = $out->getResourceLoader()->getTestModuleNames( 'qunit' );

		$summary = $this->msg( 'javascripttest-qunit-intro' )
			->params( $testConfig['qunit']['documentation'] )
			->parseAsBlock();

		$baseHtml = <<<HTML
<div class="mw-content-ltr">
<div id="qunit"></div>
<div id="qunit-fixture"></div>
</div>
HTML;

		$out->addHtml( $this->wrapSummaryHtml( $summary, 'frameworkfound' ) . $baseHtml );

		// The testrunner configures QUnit and essentially depends on it. However, test suites
		// are reusable in environments that preload QUnit (or a compatibility interface to
		// another framework). Therefore we have to load it ourselves.
		$out->addHtml( Html::inlineScript(
			ResourceLoader::makeLoaderConditionalScript(
				Xml::encodeJsCall( 'mw.loader.using', array(
					array( 'jquery.qunit', 'jquery.qunit.completenessTest' ),
					new XmlJsCode(
						'function () {' . Xml::encodeJsCall( 'mw.loader.load', array( $modules ) ) . '}'
					)
				) )
			)
		) );
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

		$query = array(
			'lang' => $this->getLanguage()->getCode(),
			'skin' => $this->getSkin()->getSkinName(),
			'debug' => ResourceLoader::inDebugMode() ? 'true' : 'false',
		);
		$embedContext = new ResourceLoaderContext( $rl, new FauxRequest( $query ) );
		$query['only'] = 'scripts';
		$startupContext = new ResourceLoaderContext( $rl, new FauxRequest( $query ) );

		$modules = $rl->getTestModuleNames( 'qunit' );

		// The below is essentially a pure-javascript version of OutputPage::getHeadScripts.
		$startup = $rl->makeModuleResponse( $startupContext, array(
			'startup' => $rl->getModule( 'startup' ),
		) );
		// Embed page-specific mw.config variables.
		// The current Special page shouldn't be relevant to tests, but various modules (which
		// are loaded before the test suites), reference mw.config while initialising.
		$code = ResourceLoader::makeConfigSetScript( $out->getJSVars() );
		// Embed private modules as they're not allowed to be loaded dynamically
		$code .= $rl->makeModuleResponse( $embedContext, array(
			'user.options' => $rl->getModule( 'user.options' ),
			'user.tokens' => $rl->getModule( 'user.tokens' ),
		) );
		$code .= Xml::encodeJsCall( 'mw.loader.load', array( $modules ) );

		header( 'Content-Type: text/javascript; charset=utf-8' );
		header( 'Cache-Control: private, no-cache, must-revalidate' );
		header( 'Pragma: no-cache' );
		echo $startup;
		echo "\n";
		// Note: The following has to be wrapped in a script tag because the startup module also
		// writes a script tag (the one loading mediawiki.js). Script tags are synchronous, block
		// each other, and run in order. But they don't nest. The code appended after the startup
		// module runs before the added script tag is parsed and executed.
		echo Xml::encodeJsCall( 'document.write', array( Html::inlineScript( $code  ) ) );
	}

	private function plainQUnit() {
		$out = $this->getOutput();
		$out->disable();

		$url = $this->getPageTitle( 'qunit/export' )->getFullURL( array(
			'debug' => ResourceLoader::inDebugMode() ? 'true' : 'false',
		) );
		$html = <<<HTML
<!DOCTYPE html>
<title>QUnit</title>
<div id="qunit"></div>
<div id="qunit-fixture"></div>
HTML;
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
