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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<meta name="robots" content="noindex, nofollow" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title><?php $this->outputTitle(); ?></title>
	<link rel="stylesheet" type="text/css" href="../skins/common/shared.css"/>
	<link rel="stylesheet" type="text/css" href="../skins/monobook/main.css"/>
	<link rel="stylesheet" type="text/css" href="../skins/common/config.css"/>
	<script type="text/javascript"><!--
<?php echo "var dbTypes = " . Xml::encodeJsVar( $dbTypes ) . ";\n"; ?>
	// -->
	</script>
	<script type="text/javascript" src="../skins/common/jquery.min.js"></script>
	<script type="text/javascript" src="../skins/common/config.js"></script>
</head>

<body class="<?php print $this->getDir(); ?>">
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
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" dir="ltr">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex, nofollow" />
	<title><?php $this->outputTitle(); ?></title>
	<link rel="stylesheet" type="text/css" href="../skins/monobook/main.css"/>
	<link rel="stylesheet" type="text/css" href="../skins/common/config.css"/>
	<script type="text/javascript" src="../skins/common/jquery.min.js"></script>
	<script type="text/javascript" src="../skins/common/config.js"></script>
</head>

<body style="background-image: none">
<?php
	}

	function outputTitle() {
		global $wgVersion;
		echo htmlspecialchars( wfMsg( 'config-title', $wgVersion ) );
	}

	function outputWarnings() {
		$this->addHTML( $this->warnings );
		$this->warnings = '';
	}
}
