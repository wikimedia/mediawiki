<?php
/**
 * Output handler for the web installer.
 *
 * @file
 * @ingroup Deployment
 */

/**
 * Output class modelled on OutputPage.
 *
 * I've opted to use a distinct class rather than derive from OutputPage here in
 * the interests of separation of concerns: if we used a subclass, there would be
 * quite a lot of things you could do in OutputPage that would break the installer,
 * that wouldn't be immediately obvious.
 *
 * @ingroup Deployment
 * @since 1.17
 */
class WebInstallerOutput {
	/**
	 * The WebInstaller object this WebInstallerOutput is used by.
	 *
	 * @var WebInstaller
	 */
	public $parent;

	/**
	 * Buffered contents that haven't been output yet
	 * @var String
	 */
	private $contents = '';

	/**
	 * Has the header (or short header) been output?
	 * @var bool
	 */
	private $headerDone = false;

	public $redirectTarget;

	/**
	 * Whether to use the limited header (used during CC license callbacks)
	 * @var bool
	 */
	private $useShortHeader = false;

	/**
	 * Constructor.
	 *
	 * @param $parent WebInstaller
	 */
	public function __construct( WebInstaller $parent ) {
		$this->parent = $parent;
	}

	public function addHTML( $html ) {
		$this->contents .= $html;
		$this->flush();
	}

	public function addWikiText( $text ) {
		$this->addHTML( $this->parent->parse( $text ) );
	}

	public function addHTMLNoFlush( $html ) {
		$this->contents .= $html;
	}

	public function redirect( $url ) {
		if ( $this->headerDone ) {
			throw new MWException( __METHOD__ . ' called after sending headers' );
		}
		$this->redirectTarget = $url;
	}

	public function output() {
		$this->flush();
		$this->outputFooter();
	}

	/**
	 * Get the raw vector CSS, flipping if needed
	 * @param $dir String 'ltr' or 'rtl'
	 * @return String
	 */
	public function getCSS( $dir ) {
		$skinDir = dirname( dirname( dirname( __FILE__ ) ) ) . '/skins';
		$vectorCssFile = "$skinDir/vector/screen.css";
		$configCssFile = "$skinDir/common/config.css";
		wfSuppressWarnings();
		$vectorCss = file_get_contents( $vectorCssFile );
		$configCss = file_get_contents( $configCssFile );
		wfRestoreWarnings();
		$css = str_replace( 'images/', '../skins/vector/images/', $vectorCss ) . "\n" . str_replace( 'images/', '../skins/common/images/', $configCss );
		if( !$css ) {
			return "/** Your webserver cannot read $vectorCssFile or $configCssFile, please check file permissions */";
		} elseif( $dir == 'rtl' ) {
			$css = CSSJanus::transform( $css, true );
		}
		return $css;
	}

	/**
	 * URL for index.php?css=foobar
	 * @return String
	 */
	private function getCssUrl( ) {
		return $_SERVER['PHP_SELF'] . '?css=' . $this->getDir();
	}

	public function useShortHeader( $use = true ) {
		$this->useShortHeader = $use;
	}

	public function flush() {
		if ( !$this->headerDone ) {
			$this->outputHeader();
		}
		if ( !$this->redirectTarget && strlen( $this->contents ) ) {
			echo $this->contents;
			flush();
			$this->contents = '';
		}
	}

	public function getDir() {
		global $wgLang;
		if( !is_object( $wgLang ) || !$wgLang->isRtl() )
			return 'ltr';
		else
			return 'rtl';
	}

	public function getLanguageCode() {
		global $wgLang;
		if( !is_object( $wgLang ) )
			return 'en';
		else
			return $wgLang->getCode();
	}

	public function getHeadAttribs() {
		return array(
			'dir' => $this->getDir(),
			'lang' => $this->getLanguageCode(),
		);
	}

	/**
	 * Get whether the header has been output
	 * @return bool
	 */
	public function headerDone() {
		return $this->headerDone;
	}

	public function outputHeader() {
		$this->headerDone = true;
		$dbTypes = $this->parent->getDBTypes();

		$this->parent->request->response()->header("Content-Type: text/html; charset=utf-8");
		if ( $this->redirectTarget ) {
			$this->parent->request->response()->header( 'Location: '.$this->redirectTarget );
			return;
		}

		if ( $this->useShortHeader ) {
			$this->outputShortHeader();
			return;
		}

?>
<?php echo Html::htmlHeader( $this->getHeadAttribs() ); ?>
<head>
	<meta name="robots" content="noindex, nofollow" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title><?php $this->outputTitle(); ?></title>
	<?php echo Html::linkedStyle( '../skins/common/shared.css' ) . "\n"; ?>
	<?php echo Html::linkedStyle( $this->getCssUrl() ) . "\n"; ?>
	<?php echo Html::inlineScript(  "var dbTypes = " . Xml::encodeJsVar( $dbTypes ) ) . "\n"; ?>
	<?php echo $this->getJQuery() . "\n"; ?>
	<?php echo $this->getJQueryTipsy() . "\n"; ?>
	<?php echo Html::linkedScript( '../skins/common/config.js' ) . "\n"; ?>
</head>

<?php echo Html::openElement( 'body', array( 'class' => $this->getDir() ) ) . "\n"; ?>
<div id="mw-page-base"></div>
<div id="mw-head-base"></div>
<div id="content">
<div id="bodyContent">

<h1><?php $this->outputTitle(); ?></h1>
<?php
	}

	public function outputFooter() {
		if ( $this->useShortHeader ) {
?>
</body></html>
<?php
			return;
		}
?>

</div></div>


<div id="mw-panel">
	<div class="portlet" id="p-logo">
	  <a style="background-image: url(../skins/common/images/mediawiki.png);"
	    href="http://www.mediawiki.org/"
	    title="Main Page"></a>
	</div>
	<script type="text/javascript"> if (window.isMSIE55) fixalpha(); </script>
	<div class="portlet"><div class="body">
<?php
	echo $this->parent->parse( wfMsgNoTrans( 'config-sidebar' ), true );
?>
	</div></div>
</div>

</body>
</html>
<?php
	}

	public function outputShortHeader() {
?>
<?php echo Html::htmlHeader( $this->getHeadAttribs() ); ?>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex, nofollow" />
	<title><?php $this->outputTitle(); ?></title>
	<?php echo Html::linkedStyle( $this->getCssUrl() ) . "\n"; ?>
	<?php echo $this->getJQuery(); ?>
	<?php echo $this->getJQueryTipsy() . "\n"; ?>
	<?php echo Html::linkedScript( '../skins/common/config.js' ); ?>
</head>

<body style="background-image: none">
<?php
	}

	public function outputTitle() {
		global $wgVersion;
		echo htmlspecialchars( wfMsg( 'config-title', $wgVersion ) );
	}

	public function getJQuery() {
		return Html::linkedScript( "../resources/jquery/jquery.js" );
	}
	public function getJQueryTipsy() {
		return Html::linkedScript( "../resources/jquery/jquery.tipsy.js" );
	}
}
