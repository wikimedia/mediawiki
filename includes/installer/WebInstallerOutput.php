<?php

/**
 * Output class modelled on OutputPage.
 *
 * I've opted to use a distinct class rather than derive from OutputPage here in 
 * the interests of separation of concerns: if we used a subclass, there would be 
 * quite a lot of things you could do in OutputPage that would break the installer, 
 * that wouldn't be immediately obvious. 
 */
class WebInstallerOutput {
	var $parent;
	var $contents = '';
	var $warnings = '';
	var $headerDone = false;
	var $redirectTarget;
	var $debug = true;
	var $useShortHeader = false;

	function __construct( $parent ) {
		$this->parent = $parent;
	}

	function addHTML( $html ) {
		$this->contents .= $html;
		$this->flush();
	}

	function addWikiText( $text ) {
		$this->addHTML( $this->parent->parse( $text ) );
	}

	function addHTMLNoFlush( $html ) {
		$this->contents .= $html;
	}

	function addWarning( $msg ) {
		$this->warnings .= "<p>$msg</p>\n";
	}
	
	function addWarningMsg( $msg /*, ... */ ) {
		$params = func_get_args();
		array_shift( $params );
		$this->addWarning( wfMsg( $msg, $params ) );
	}

	function redirect( $url ) {
		if ( $this->headerDone ) {
			throw new MWException( __METHOD__ . ' called after sending headers' );
		}
		$this->redirectTarget = $url;
	}

	function output() {
		$this->flush();
		$this->outputFooter();
	}

	function useShortHeader( $use = true ) {
		$this->useShortHeader = $use;
	}

	function flush() {
		if ( !$this->headerDone ) {
			$this->outputHeader();
		}
		if ( !$this->redirectTarget && strlen( $this->contents ) ) {
			echo $this->contents;
			ob_flush();
			flush();
			$this->contents = '';
		}
	}

	function getDir() {
		global $wgLang;
		if( !is_object( $wgLang ) || !$wgLang->isRtl() )
			return 'ltr';
		else
			return 'rtl';
	}

	function getLanguageCode() {
		global $wgLang;
		if( !is_object( $wgLang ) )
			return 'en';
		else
			return $wgLang->getCode();
	}

	function getHeadAttribs() {
		return array(
			'dir' => $this->getDir(),
			'lang' => $this->getLanguageCode(),
		);
	}

	function headerDone() {
		return $this->headerDone;
	}

	function outputHeader() {
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
	<?php echo Html::linkedStyle( '../skins/monobook/main.css' ) . "\n"; ?>
	<?php echo Html::linkedStyle( '../skins/common/config.css' ) . "\n"; ?>
	<?php echo Html::inlineScript(  "var dbTypes = " . Xml::encodeJsVar( $dbTypes ) ) . "\n"; ?>
	<?php $this->outputJQuery() . "\n"; ?>
	<?php echo Html::linkedScript( '../skins/common/config.js' ) . "\n"; ?>
</head>

<?php echo Html::openElement( 'body', array( 'class' => $this->getDir() ) ) . "\n"; ?>
<noscript>
<style type="text/css">
.config-help-message { display: block; }
.config-show-help { display: none; }
</style>
</noscript>
<div id="globalWrapper">
<div id="column-content">
<div id="content">
<div id="bodyContent">

<h1><?php $this->outputTitle(); ?></h1>
<?php
	}

	function outputFooter() {
		$this->outputWarnings();

		if ( $this->useShortHeader ) {
?>
</body></html>
<?php
			return;
		}
?>

</div></div></div>


<div id="column-one">
	<div class="portlet" id="p-logo">
	  <a style="background-image: url(../skins/common/images/mediawiki.png);"
	    href="http://www.mediawiki.org/"
	    title="Main Page"></a>
	</div>
	<script type="text/javascript"> if (window.isMSIE55) fixalpha(); </script>
	<div class='portlet'><div class='pBody'>
<?php
	echo $this->parent->parse( wfMsgNoTrans( 'config-sidebar' ), true );
?>
	</div></div>
</div>

</div>

</body>
</html>
<?php
	}

	function outputShortHeader() {
?>
<?php echo Html::htmlHeader( $this->getHeadAttribs() ); ?>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex, nofollow" />
	<title><?php $this->outputTitle(); ?></title>
	<?php echo Html::linkedStyle( '../skins/monobook/main.css' ) . "\n"; ?>
	<?php echo Html::linkedStyle( '../skins/common/config.css' ) . "\n"; ?>
	<?php $this->outputJQuery(); ?>
	<?php echo Html::linkedScript( '../skins/common/config.js' ); ?>
</head>

<body style="background-image: none">
<?php
	}

	function outputTitle() {
		global $wgVersion;
		echo htmlspecialchars( wfMsg( 'config-title', $wgVersion ) );
	}

	function outputJQuery() {
		global $wgJQueryVersion;
		echo Html::linkedScript( "../skins/common/jquery-$wgJQueryVersion.min.js" );
	}

	function outputWarnings() {
		$this->addHTML( $this->warnings );
		$this->warnings = '';
	}
}
