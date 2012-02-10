<?php
/**
 * @ingroup SpecialPage
 * @file
 */

/**
 * @ingroup SpecialPage
 */
class SpecialJavaScriptTest extends SpecialPage {

	/**
	 * @var $frameworks Array: Mapping of framework ids and their initilizer methods
	 * in this class. If a framework is requested but not in this array,
	 * the 'unknownframework' error is served.
	 */
	static $frameworks = array(
		'qunit' => 'initQUnitTesting',
	);

	public function __construct() {
		parent::__construct( 'JavaScriptTest' );
	}

	public function execute( $par ) {
		global $wgEnableJavaScriptTest;

		$out = $this->getOutput();

		$this->setHeaders();
		$out->disallowUserJs();

		// Abort early if we're disabled
		if ( $wgEnableJavaScriptTest !== true ) {
			$out->addWikiMsg( 'javascripttest-disabled' );
			return;
		}

		$out->addModules( 'mediawiki.special.javaScriptTest' );

		// Determine framework
		$pars = explode( '/', $par );
		$framework = strtolower( $pars[0] );

		// No framework specified
		if ( $par == '' ) {
			$out->setPageTitle( $this->msg( 'javascripttest' ) );
			$summary = $this->wrapSummaryHtml(
				$this->msg( 'javascripttest-pagetext-noframework' )->escaped() . $this->getFrameworkListHtml(),
				'noframework'
			);
			$out->addHtml( $summary );

		// Matched! Display proper title and initialize the framework
		} elseif ( isset( self::$frameworks[$framework] ) ) {
			$out->setPageTitle( $this->msg( 'javascripttest-title', $this->msg( "javascripttest-$framework-name" )->plain() ) );
			$out->setSubtitle( $this->msg( 'javascripttest-backlink' )->rawParams( Linker::linkKnown( $this->getTitle() ) ) );
			$this->{self::$frameworks[$framework]}();

		// Framework not found, display error
		} else {
			$out->setPageTitle( $this->msg( 'javascripttest' ) );
			$summary = $this->wrapSummaryHtml( '<p class="error">'
				. $this->msg( 'javascripttest-pagetext-unknownframework', $par )->escaped()
				. '</p>'
				. $this->getFrameworkListHtml(),
				'unknownframework'
			);
			$out->addHtml( $summary );
		}
	}

	/**
	 * Get a list of frameworks (including introduction paragraph and links to the framework run pages)
	 * @return String: HTML
	 */
	private function getFrameworkListHtml() {
		$list = '<ul>';
		foreach( self::$frameworks as $framework => $initFn ) {
			$list .= Html::rawElement(
				'li',
				array(),
				Linker::link( $this->getTitle( $framework ), $this->msg( "javascripttest-$framework-name" )->escaped() )
			);
		}
		$list .= '</ul>';
		$msg = $this->msg( 'javascripttest-pagetext-frameworks' )->rawParams( $list )->parseAsBlock();

		return $msg;
	}

	/**
	 * Function to wrap the summary.
	 * It must be given a valid state as a second parameter or an exception will
	 * be thrown.
	 * @param $html String: The raw HTML.
	 * @param $state String: State, one of 'noframework', 'unknownframework' or 'frameworkfound'
	 * @return string
	 */
	private function wrapSummaryHtml( $html, $state ) {
		$validStates = array( 'noframework', 'unknownframework', 'frameworkfound' );
		if( !in_array( $state, $validStates ) ) {
			throw new MWException( __METHOD__
				. ' given an invalid state. Must be one of "'
				. join( '", "', $validStates) . '".'
			);
		}
		return "<div id=\"mw-javascripttest-summary\" class=\"mw-javascripttest-$state\">$html</div>";
	}

	/**
	 * Initialize the page for QUnit.
	 */
	private function initQUnitTesting() {
		global $wgJavaScriptTestConfig;

		$out = $this->getOutput();

		$out->addModules( 'mediawiki.tests.qunit.testrunner' );
		$qunitTestModules = $out->getResourceLoader()->getTestModuleNames( 'qunit' );
		$out->addModules( $qunitTestModules );

		$summary = $this->msg( 'javascripttest-qunit-intro' )
			->params( $wgJavaScriptTestConfig['qunit']['documentation'] )
			->parseAsBlock();
		$header = $this->msg( 'javascripttest-qunit-heading' )->escaped();
		$userDir = $this->getLanguage()->getDir();

		$baseHtml = <<<HTML
<div class="mw-content-ltr">
<div id="qunit-header"><span dir="$userDir">$header</span></div>
<div id="qunit-banner"></div>
<div id="qunit-testrunner-toolbar"></div>
<div id="qunit-userAgent"></div>
<ol id="qunit-tests"></ol>
<div id="qunit-fixture">test markup, will be hidden</div>
</div>
HTML;
		$out->addHtml( $this->wrapSummaryHtml( $summary, 'frameworkfound' ) . $baseHtml );

	}

	public function isListed(){
		global $wgEnableJavaScriptTest;
		return $wgEnableJavaScriptTest === true;
	}

}
