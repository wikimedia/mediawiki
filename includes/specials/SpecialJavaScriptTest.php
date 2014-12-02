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
	 * @var array Mapping of framework ids and their initilizer methods
	 * in this class. If a framework is requested but not in this array,
	 * the 'unknownframework' error is served.
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

		$out->addModules( 'mediawiki.special.javaScriptTest' );

		// No framework specified
		if ( $par == '' ) {
			// TODO: HTTP 404
			$out->setPageTitle( $this->msg( 'javascripttest' ) );
			$summary = $this->wrapSummaryHtml(
				$this->msg( 'javascripttest-pagetext-noframework' )->escaped() .
					$this->getFrameworkListHtml(),
				'noframework'
			);
			$out->addHtml( $summary );
			return;
		}

		// Determine framework
		$pars = explode( '/', $par, 2 );
		$framework = $pars[0];

		if ( !in_array( $framework, self::$frameworks ) ) {
			// TODO: HTTP 404
			// Framework not found, display error
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

		if ( isset( $pars[1] ) && $pars[1] === 'debug' ) {
			$method = $pars[1] . ucfirst( $framework );
			$this->$method();
			return;
		}

		if ( isset( $pars[1] ) && $pars[1] === 'export' ) {
			$method = 'export' . ucfirst( $framework );
			$this->$method();
			return;
		}

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
	 * Initialize QUnit page.
	 */
	private function viewQUnit() {
		$out = $this->getOutput();
		$testConfig = $this->getConfig()->get( 'JavaScriptTestConfig' );

		$out->addModules( 'test.mediawiki.qunit.testrunner' );
		$qunitTestModules = $out->getResourceLoader()->getTestModuleNames( 'qunit' );
		$out->addModules( $qunitTestModules );

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

		// This special page is disabled by default ($wgEnableJavaScriptTest), and contains
		// no sensitive data. In order to allow TestSwarm to embed it into a test client window,
		// we need to allow iframing of this page.
		$out->allowClickjacking();

		// Used in ./tests/qunit/data/testrunner.js, see also documentation of
		// $wgJavaScriptTestConfig in DefaultSettings.php
		$out->addJsConfigVars(
			'QUnitTestSwarmInjectJSPath',
			$testConfig['qunit']['testswarm-injectjs']
		);
	}

	private function debugQUnit() {
		$out = $this->getOutput();
		$out->disable();
		header( 'Content-Type: text/html; charset=utf-8' );
		echo '
<!DOCTYPE html>
<title>MediaWiki</title>
<div id="qunit"></div>
<div id="qunit-fixture"></div>
<script src="./export"></script>';

	}

	private function exportQUnit() {
		$out = $this->getOutput();

		$out->disable();

		$rl = $out->getResourceLoader();

		$query = ResourceLoader::makeLoaderQuery(
			array(), // modules
			$this->getLanguage()->getCode(),
			$this->getSkin()->getSkinName(),
			null, // user
			null, // version
			ResourceLoader::inDebugMode(),
			'scripts',
			$out->isPrintable(),
			$this->getRequest()->getBool( 'handheld' )
		);
		$context = new ResourceLoaderContext( $rl, new FauxRequest( $query ) );

		$modules = array( 'test.mediawiki.qunit.testrunner' );
		$modules = array_merge( $modules, $rl->getTestModuleNames( 'qunit' ) );

		header( 'Content-Type: text/javascript; charset=utf-8' );
		// Don't cache
		header( 'Cache-Control: private, no-cache, must-revalidate' );
		header( 'Pragma: no-cache' );

		echo $rl->makeModuleResponse( $context, array(
			$rl->getModule( 'startup' ),
		) );
		// The mw.loader.load call has to be in a script tag because the startup
		// module also writes a script tag. And while script tags are synchronous
		// between each-other, the code right after 'document.write' executes before
		// the referened script.
		$scriptTag = Html::inlineScript( Xml::encodeJsCall( 'mw.loader.load', array( $modules ) ) );
		echo Xml::encodeJsCall( 'document.write', array( $scriptTag ) );
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
