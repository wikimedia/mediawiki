<?php
/**
 * MonoBook nouveau
 *
 * Translated from gwicke's previous TAL template version to remove
 * dependency on PHPTAL.
 *
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */

/** */
require_once('includes/SkinTemplate.php');

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @package MediaWiki
 * @subpackage Skins
 */
class SkinMonoBook extends SkinTemplate {
	/** Using monobook. */
	function initPage( &$out ) {
		SkinTemplate::initPage( $out );
		$this->skinname  = 'monobook';
		$this->stylename = 'monobook';
		$this->template  = 'MonoBookTemplate';
	}
}

/**
 * Template filter callback for MonoBook skin.
 * Takes an associative array of data set from a SkinTemplate-based
 * class, and a wrapper for MediaWiki's localization database, and
 * outputs a formatted page.
 *
 * @param array $data
 * @param MediaWiki_I18N $translator
 * @access private
 */
function MonoBookTemplate( &$data, &$translator ) {
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo htmlspecialchars($data['lang']) ?>" lang="<?php echo htmlspecialchars($data['lang']) ?>" dir="<?php echo htmlspecialchars($data['dir']) ?>">
  <head>
    <meta http-equiv="Content-Type" content="<?php echo htmlspecialchars($data['mimetype']) ?>; charset=<?php echo htmlspecialchars($data['charset']) ?>" />
    <?php echo $data['headlinks'] ?>
    <title><?php echo htmlspecialchars( $data['pagetitle'] ) ?></title>
    <style type="text/css" media="screen,projection">/*<![CDATA[*/ @import "<?php echo htmlspecialchars($data['stylepath']) ?>/<?php echo htmlspecialchars($data['stylename']) ?>/main.css"; /*]]>*/</style>
    <link rel="stylesheet" type="text/css" media="print" href="<?php echo htmlspecialchars($data['stylepath']) ?>/common/commonPrint.css" />
    <!--[if IE]><style type="text/css" media="all">@import "<?php echo htmlspecialchars($data['stylepath']) ?>/<?php echo htmlspecialchars($data['stylename']) ?>/IEFixes.css";</style>
    <script type="text/javascript" src="<?php echo htmlspecialchars($data['stylepath']) ?>/common/IEFixes.js"></script>
    <meta http-equiv="imagetoolbar" content="no" /><![endif]-->
    <?php if($data['jsvarurl'  ]) { ?><script type="text/javascript" src="<?php echo htmlspecialchars($data['jsvarurl'  ]) ?>"></script><?php } ?>
    <script type="text/javascript" src="<?php                                   echo htmlspecialchars($data['stylepath'])  ?>/common/wikibits.js"></script>
    <?php if($data['usercss'   ]) { ?><style type="text/css"><?php              echo                  $data['usercss']     ?></style><?php    } ?>
    <?php if($data['userjs'    ]) { ?><script type="text/javascript" src="<?php echo htmlspecialchars($data['userjs'    ]) ?>"></script><?php } ?>
    <?php if($data['userjsprev']) { ?><script type="text/javascript"><?php      echo                  $data['userjsprev']  ?></script><?php   } ?>
  </head>
  <body <?php if($data['body_ondblclick']) { ?>ondblclick="<?php echo htmlspecialchars($data['body_ondblclick']) ?>"<?php } ?>
        <?php if($data['nsclass'        ]) { ?>class="<?php      echo htmlspecialchars($data['nsclass'        ]) ?>"<?php } ?>>
    <div id="globalWrapper">
      <div id="column-content">
	<div id="content">
	  <a name="top" id="contentTop"></a>
	  <?php if($data['sitenotice']) { ?><div id="siteNotice"><?php echo $data['sitenotice'] ?></div><?php } ?>
	  <h1 class="firstHeading"><?php echo htmlspecialchars($data['title']) ?></h1>
	  <div id="bodyContent">
	    <h3 id="siteSub"><?php echo htmlspecialchars($translator->translate('tagline')) ?></h3>
	    <div id="contentSub"><?php echo $data['subtitle'] ?></div>
	    <?php if($data['undelete']) { ?><div id="contentSub"><?php     echo $data['undelete'] ?></div><?php } ?>
	    <?php if($data['newtalk'] ) { ?><div class="usermessage"><?php echo $data['newtalk']  ?></div><?php } ?>
	    <!-- start content -->
	    <?php echo $data['bodytext'] ?>
	    <?php if($data['catlinks']) { ?><div id="catlinks"><?php echo $data['catlinks'] ?></div><?php } ?>
	    <!-- end content -->
	    <div class="visualClear"></div>
	  </div>
	</div>
      </div>
      <div id="column-one">
	<div id="p-cactions" class="portlet">
	  <h5>Views</h5>
	  <ul>
	    <?php foreach($data['content_actions'] as $key => $action) {
	       ?><li id="ca-<?php echo htmlspecialchars($key) ?>"
	       <?php if($action['class']) { ?>class="<?php echo htmlspecialchars($action['class']) ?>"<?php } ?>
	       ><a href="<?php echo htmlspecialchars($action['href']) ?>"><?php
	       echo htmlspecialchars($action['text']) ?></a></li><?php
	     } ?>
	  </ul>
	</div>
	<div class="portlet" id="p-personal">
	  <h5><?php echo htmlspecialchars($translator->translate('personaltools')) ?></h5>
	  <div class="pBody">
	    <ul>
	    <?php foreach($data['personal_urls'] as $key => $item) {
	       ?><li id="pt-<?php echo htmlspecialchars($key) ?>"><a href="<?php
	       echo htmlspecialchars($item['href']) ?>"<?php
	       if(!empty($item['class'])) { ?> class="<?php
	       echo htmlspecialchars($item['class']) ?>"<?php } ?>><?php
	       echo htmlspecialchars($item['text']) ?></a></li><?php
	    } ?>
	    </ul>
	  </div>
	</div>
	<div class="portlet" id="p-logo">
	  <a style="background-image: url(<?php echo htmlspecialchars($data['logopath']) ?>);"
	    href="<?php echo htmlspecialchars($data['nav_urls']['mainpage']['href'])?>"
	    title="<?php echo htmlspecialchars($translator->translate('mainpage')) ?>"></a>
	</div>
	<div class="portlet" id="p-nav">
	  <h5><?php echo htmlspecialchars($translator->translate('navigation')) ?></h5>
	  <div class="pBody">
	    <ul>
	      <?php foreach($data['navigation_urls'] as $navlink) { ?>
	      <li id="<?php echo htmlspecialchars($navlink['id'])
	        ?>"><a href="<?php echo htmlspecialchars($navlink['href']) ?>"><?php 
	        echo htmlspecialchars($navlink['text']) ?></a></li><?php } ?>
	    </ul>
	  </div>
	</div>
	<div id="p-search" class="portlet">
	  <h5><?php echo htmlspecialchars($translator->translate('search')) ?></h5>
	  <div class="pBody">
	    <form name="searchform" action="<?php echo htmlspecialchars($data['searchaction']) ?>" id="searchform">
	      <input id="searchInput" name="search" type="text"
	        <?php if($accesskey = $translator->translate('accesskey-search')) {
	          ?>accesskey="<?php echo htmlspecialchars($accesskey) ?>"<?php } ?> />
	      <input type='submit' name="go" class="searchButton"
	        value="<?php echo htmlspecialchars($translator->translate('go')) ?>"
	        />&nbsp;<input type='submit' name="fulltext"
	        class="searchButton"
	        value="<?php echo htmlspecialchars($translator->translate('search')) ?>" />
	    </form>
	  </div>
	</div>
	<div class="portlet" id="p-tb">
	  <h5><?php echo htmlspecialchars($translator->translate('toolbox')) ?></h5>
	  <div class="pBody">
	    <ul>
		  <?php if($data['notspecialpage']) { foreach( array( 'whatlinkshere', 'recentchangeslinked' ) as $special ) { ?>
		  <li id="t-<?php echo $special?>"><a href="<?php
		    echo htmlspecialchars($data['nav_urls'][$special]['href']) 
		    ?>"><?php echo htmlspecialchars($translator->translate($special)) ?></a></li>
		  <?php } } ?>
	      <?php if($data['feeds']) { ?><li id="feedlinks"><?php foreach($data['feeds'] as $key => $feed) {
	        ?><span id="feed-<?php echo htmlspecialchars($key) ?>"><a href="<?php
	        echo htmlspecialchars($feed['href']) ?>"><?php echo htmlspecialchars($feed['text'])?>&nbsp;</span>
	        <?php } ?></li><?php } ?>
	      <?php foreach( array('contributions', 'emailuser', 'upload', 'specialpages') as $special ) { ?>
	      <?php if($data['nav_urls'][$special]) {?><li id="t-<?php echo $special ?>"><a href="<?php
	        echo htmlspecialchars($data['nav_urls'][$special]['href'])
	        ?>"><?php echo htmlspecialchars($translator->translate($special)) ?></a></li><?php } ?>
	      <?php } ?>
	    </ul>
	  </div>
	</div>
	<?php if( $data['language_urls'] ) { ?><div id="p-lang" class="portlet">
	  <h5><?php echo htmlspecialchars($translator->translate('otherlanguages')) ?></h5>
	  <div class="pBody">
	    <ul>
	      <?php foreach($data['language_urls'] as $langlink) { ?>
	      <li>
	      <a href="<?php echo htmlspecialchars($langlink['href'])
	        ?>"><?php echo htmlspecialchars($langlink['text']) ?></a>
	      </li>
	      <?php } ?>
	    </ul>
	  </div>
	</div>
	<?php } ?>
      </div><!-- end of the left (by default at least) column -->
      <div class="visualClear"></div>
      <div id="footer">
    <?php if($data['poweredbyico']) { ?><div id="f-poweredbyico"><?php echo $data['poweredbyico'] ?></div><?php } ?>
	<?php if($data['copyrightico']) { ?><div id="f-copyrightico"><?php echo $data['copyrightico'] ?></div><?php } ?>
	<ul id="f-list">
	  <?php if($data['lastmod'   ]) { ?><li id="f-lastmod"><?php    echo $data['lastmod'   ] ?></li><?php } ?>
	  <?php if($data['viewcount' ]) { ?><li id="f-viewcount"><?php  echo $data['viewcount' ] ?></li><?php } ?>
	  <?php if($data['credits'   ]) { ?><li id="f-credits"><?php    echo $data['credits'   ] ?></li><?php } ?>
	  <?php if($data['copyright' ]) { ?><li id="f-copyright"><?php  echo $data['copyright' ] ?></li><?php } ?>
	  <?php if($data['about'     ]) { ?><li id="f-about"><?php      echo $data['about'     ] ?></li><?php } ?>
	  <?php if($data['disclaimer']) { ?><li id="f-disclaimer"><?php echo $data['disclaimer'] ?></li><?php } ?>
	</ul>
      </div>
    </div>
    <?php echo $data['reporttime'] ?>
  </body>
</html>
<?php
}

?>