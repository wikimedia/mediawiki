<?php

/** 
 * Default skin for HTML dumps, based on MonoBook.php
 */

if( !defined( 'MEDIAWIKI' ) )
	die();

/** */
require_once( 'includes/SkinTemplate.php' );

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinHTMLDump extends SkinTemplate {
	/** Using monobook. */
	function initPage( &$out ) {
		SkinTemplate::initPage( $out );
		$this->template  = 'HTMLDumpTemplate';
	}

	function getNavigationLinks() {
		$links = parent::getNavigationLinks();
		foreach ( $links as $index => $link ) {
			if ( $link['href'] == 'recentchanges-url' ) {
				unset( $links[$index] );
			}
			if ( $link['href'] == 'randompage-url' ) {
				unset( $links[$index] );
			}
		}
		return $links;
	}

	function buildContentActionUrls() {
		$content_actions = array();
		$nskey = $this->getNameSpaceKey();
		$content_actions[$nskey] = $this->tabAction(
			$this->mTitle->getSubjectPage(),
			$nskey,
			!$this->mTitle->isTalkPage() );
		
		$content_actions['talk'] = $this->tabAction(
			$this->mTitle->getTalkPage(),
			'talk',
			$this->mTitle->isTalkPage(),
			'',
			true);
		return $content_actions;
	}

	function makeBrokenLinkObj( &$nt, $text = '', $query = '', $trail = '', $prefix = '' ) {
		if ( !isset( $nt ) ) {
			return "<!-- ERROR -->{$prefix}{$text}{$trail}";
		}
		
		if ( $nt->getNamespace() == NS_CATEGORY ) {
			return $this->makeKnownLinkObj( $nt, $text, $query, $trail, $prefix );
		}
		
		if ( $text == '' ) {
			$text = $nt->getPrefixedText();
		}
		return $prefix . $text . $trail;
	}
}

/**
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */
class HTMLDumpTemplate extends QuickTemplate {
	/**
	 * Template filter callback for MonoBook skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 *
	 * @access private
	 */
	function execute() {
		$this->modifySetup();
		$this->reallyExecute();
	}


	function modifySetup() {
		/*
		foreach ( $this->data['navigation_urls'] as $index => $link ) {
			if ( $link['text'] == 'recentchanges' ) {
				unset( $this->data['navigation_urls'][$index] );
			} elseif ( $link['text'] */
	}
	
	function reallyExecute() {
		wfSuppressWarnings();
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php $this->text('lang') ?>" lang="<?php $this->text('lang') ?>" dir="<?php $this->text('dir') ?>">
  <head>
    <meta http-equiv="Content-Type" content="<?php $this->text('mimetype') ?>; charset=<?php $this->text('charset') ?>" />
    <?php $this->html('headlinks') ?>
    <title><?php $this->text('pagetitle') ?></title>
    <style type="text/css" media="screen,projection">/*<![CDATA[*/ @import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/main.css"; /*]]>*/</style>
    <link rel="stylesheet" type="text/css" media="print" href="<?php $this->text('stylepath') ?>/common/commonPrint.css" />
    <!--[if lt IE 5.5000]><style type="text/css">@import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/IE50Fixes.css";</style><![endif]-->
    <!--[if IE 5.5000]><style type="text/css">@import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/IE55Fixes.css";</style><![endif]-->
    <!--[if IE 6]><style type="text/css">@import "<?php $this->text('stylepath') ?>/<?php $this->text('stylename') ?>/IE60Fixes.css";</style><![endif]-->
    <!--[if IE]><script type="<?php $this->text('jsmimetype') ?>" src="<?php $this->text('stylepath') ?>/common/IEFixes.js"></script>
    <meta http-equiv="imagetoolbar" content="no" /><![endif]-->
    <?php if($this->data['jsvarurl'  ]) { ?><script type="<?php $this->text('jsmimetype') ?>" src="<?php $this->text('jsvarurl'  ) ?>"></script><?php } ?>
    <script type="<?php $this->text('jsmimetype') ?>" src="<?php $this->text('stylepath' ) ?>/common/wikibits.js"></script>
  </head>
  <body 
    <?php if($this->data['nsclass'        ]) { ?>class="<?php      $this->text('nsclass')         ?>"<?php } ?>>
    <div id="globalWrapper">
      <div id="column-content">
	<div id="content">
	  <a name="top" id="contentTop"></a>
	  <?php if($this->data['sitenotice']) { ?><div id="siteNotice"><?php $this->html('sitenotice') ?></div><?php } ?>
	  <h1 class="firstHeading"><?php $this->text('title') ?></h1>
	  <div id="bodyContent">
	    <h3 id="siteSub"><?php $this->msg('tagline') ?></h3>
	    <div id="contentSub"><?php $this->html('subtitle') ?></div>
	    <?php if($this->data['undelete']) { ?><div id="contentSub"><?php     $this->html('undelete') ?></div><?php } ?>
	    <?php if($this->data['newtalk'] ) { ?><div class="usermessage"><?php $this->html('newtalk')  ?></div><?php } ?>
	    <!-- start content -->
	    <?php $this->html('bodytext') ?>
	    <?php if($this->data['catlinks']) { ?><div id="catlinks"><?php       $this->html('catlinks') ?></div><?php } ?>
	    <!-- end content -->
	    <div class="visualClear"></div>
	  </div>
	</div>
      </div>
      <div id="column-one">
	<div id="p-cactions" class="portlet">
	  <h5>Views</h5>
	  <ul>
	    <?php foreach($this->data['content_actions'] as $key => $action) {
	       ?><li id="ca-<?php echo htmlspecialchars($key) ?>"
	       <?php if($action['class']) { ?>class="<?php echo htmlspecialchars($action['class']) ?>"<?php } ?>
	       ><a href="<?php echo htmlspecialchars($action['href']) ?>"><?php
	       echo htmlspecialchars($action['text']) ?></a></li><?php
	     } ?>
	  </ul>
	</div>
	<div class="portlet" id="p-logo">
	  <a style="background-image: url(<?php $this->text('logopath') ?>);"
	    href="<?php echo htmlspecialchars($this->data['nav_urls']['mainpage']['href'])?>"
	    title="<?php $this->msg('mainpage') ?>"></a>
	</div>
	<script type="<?php $this->text('jsmimetype') ?>"> if (window.isMSIE55) fixalpha(); </script>
	<div class="portlet" id="p-navigation">
	  <h5><?php $this->msg('navigation') ?></h5>
	  <div class="pBody">
	    <ul>
	      <?php foreach($this->data['navigation_urls'] as $navlink) { ?>
	      <li id="<?php echo htmlspecialchars($navlink['id'])
	        ?>"><a href="<?php echo htmlspecialchars($navlink['href']) ?>"><?php 
	        echo htmlspecialchars($navlink['text']) ?></a></li><?php } ?>
	    </ul>
	  </div>
	</div>
	<?php if( $this->data['language_urls'] ) { ?><div id="p-lang" class="portlet">
	  <h5><?php $this->msg('otherlanguages') ?></h5>
	  <div class="pBody">
	    <ul>
	      <?php foreach($this->data['language_urls'] as $langlink) { ?>
	      <li>
	      <a href="<?php echo htmlspecialchars($langlink['href'])
	        ?>"><?php echo $langlink['text'] ?></a>
	      </li>
	      <?php } ?>
	    </ul>
	  </div>
	</div>
	<?php } ?>
      </div><!-- end of the left (by default at least) column -->
      <div class="visualClear"></div>
      <div id="footer">
    <?php if($this->data['poweredbyico']) { ?><div id="f-poweredbyico"><?php $this->html('poweredbyico') ?></div><?php } ?>
	<?php if($this->data['copyrightico']) { ?><div id="f-copyrightico"><?php $this->html('copyrightico') ?></div><?php } ?>
	<ul id="f-list">
	  <?php if($this->data['lastmod'   ]) { ?><li id="f-lastmod"><?php    $this->html('lastmod')    ?></li><?php } ?>
	  <?php if($this->data['numberofwatchingusers' ]) { ?><li id="f-numberofwatchingusers"><?php  $this->html('numberofwatchingusers') ?></li><?php } ?>
	  <?php if($this->data['credits'   ]) { ?><li id="f-credits"><?php    $this->html('credits')    ?></li><?php } ?>
	  <?php if($this->data['copyright' ]) { ?><li id="f-copyright"><?php  $this->html('copyright')  ?></li><?php } ?>
	  <?php if($this->data['about'     ]) { ?><li id="f-about"><?php      $this->html('about')      ?></li><?php } ?>
	  <?php if($this->data['disclaimer']) { ?><li id="f-disclaimer"><?php $this->html('disclaimer') ?></li><?php } ?>
	  <?php if($this->data['tagline']) { ?><li id="f-tagline"><?php echo $this->data['tagline'] ?></li><?php } ?>
	</ul>
      </div>
    </div>
    <?php $this->html('reporttime') ?>
  </body>
</html>
<?php
		wfRestoreWarnings();
	}
}
?>
