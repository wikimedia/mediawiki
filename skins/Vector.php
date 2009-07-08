<?php
/**
 * Vector - Branch of MonoBook which has many usability improvements and
 * somewhat cleaner code.
 * 
 * @todo document
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/**
 * SkinTemplate class for Vector skin
 * @ingroup Skins
 */
class SkinVector extends SkinTemplate {

	/* Functions */

	/**
	 * Initializes output page and sets up skin-specific parameters
	 * @param object $out Output page object to initialize
	 */
	public function initPage( OutputPage $out ) {
		parent::initPage( $out );
		$this->skinname  = 'vector';
		$this->stylename = 'vector';
		$this->template  = 'VectorTemplate';
	}

	/**
	 * Defines CSS files to be included
	 * @param object $out Output page to add styles to
	 */
	public function setupSkinUserCss( OutputPage $out ) {
		global $wgContLang;
		// Append to the default screen common & print styles...
		if ( $wgContLang->isRTL() ) {
			$out->addStyle( 'vector/main-rtl.css', 'screen' );
		} else {
			$out->addStyle( 'vector/main-ltr.css', 'screen' );
		}
		// Add common styles
		parent::setupSkinUserCss( $out );
	}

}

/**
 * QuickTemplate class for Vector skin
 * @ingroup Skins
 */
class VectorTemplate extends QuickTemplate {

	/* Members */

	/**
	 * @var Cached skin object
	 */
	var $skin;

	/* Functions */

	/**
	 * Outputs the entire contents of the XHTML page
	 */
	public function execute() {
		global $wgRequest, $wgUseTwoButtonsSearchForm;

		$this->skin = $this->data['skin'];
		$action = $wgRequest->getText( 'action' );

		// Suppress warnings to prevent notices about missing indexes in
		// $this->data (is this really the best way to handle this?)
		wfSuppressWarnings();

		// Build additional attributes for navigation urls
		$nav = $this->skin->buildNavigationUrls();
		foreach ( $nav as $section => $links ) {
			foreach ( $links as $key => $link ) {
				$xmlID = $key;
				if ( isset( $link['context'] ) && $link['context'] == 'subject' ) {
					$xmlID = 'ca-nstab-' . $xmlID;
				} else if ( isset( $link['context'] ) && $link['context'] == 'talk' ) {
					$xmlID = 'ca-talk';
				} else {
					$xmlID = 'ca-' . $xmlID;
				}
				$nav[$section][$key]['attributes'] =
					' id="' . Sanitizer::escapeId( $xmlID ) . '"';
			 	if ( $nav[$section][$key]['class'] ) {
					$nav[$section][$key]['attributes'] .=
						' class="' . htmlspecialchars( $link['class'] ) . '"';
					unset( $nav[$section][$key]['class'] );
			 	}
			 	
				// We don't want to give the watch tab an accesskey if the page is
				// being edited, because that conflicts with the accesskey on the
				// watch checkbox.  We also don't want to give the edit tab an
				// accesskey, because that's fairly superfluous and conflicts with
				// an accesskey (Ctrl-E) often used for editing in Safari.
			 	if (
					in_array( $action, array( 'edit', 'submit' ) ) &&
					in_array( $key, array( 'edit', 'watch', 'unwatch' ) )
				) {
			 		$nav[$section][$key]['key'] =
						$this->skin->tooltip( $xmlID );
			 	} else {
			 		$nav[$section][$key]['key'] =
						$this->skin->tooltipAndAccesskey( $xmlID );
			 	}
			}
		}
		$this->data['namespace_urls'] = $nav['namespaces'];
		$this->data['view_urls'] = $nav['views'];
		$this->data['action_urls'] = $nav['actions'];
		$this->data['variant_urls'] = $nav['variants'];
		
		// Build additional attributes for personal_urls
		foreach ( $this->data['personal_urls'] as $key => $item) {
			$this->data['personal_urls'][$key]['attributes'] = 
				' id="' . Sanitizer::escapeId( "pt-$key" ) . '"';
			if ( $item['active'] ) {
				$this->data['personal_urls'][$key]['attributes'] .=
					' class="active"';
			}
			$this->data['personal_urls'][$key]['key'] =
				$this->skin->tooltipAndAccesskey('pt-'.$key);
		}

		// Generate additional footer links
		$footerlinks = array(
			'info' => array(
				'lastmod',
				'viewcount',
				'numberofwatchingusers',
				'credits',
				'copyright',
				'tagline',
			),
			'places' => array(
				'privacy',
				'about',
				'disclaimer',
			),
		);

		// Build list of valid footer links
		$validFooterLinks = array();
		foreach( $footerlinks as $category => $links ) {
			$validFooterLinks[$category] = array();
			foreach( $links as $link ) {
				if( isset( $this->data[$link] ) && $this->data[$link] ) {
					$validFooterLinks[$category][] = $link;
				}
			}
		}

		// Begin content output
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="<?php $this->text('xhtmldefaultnamespace') ?>" <?php foreach($this->data['xhtmlnamespaces'] as $tag => $ns): ?>xmlns:<?php echo "{$tag}=\"{$ns}\" "; ?><?php endforeach ?>xml:lang="<?php $this->text('lang') ?>" lang="<?php $this->text('lang') ?>" dir="<?php $this->text('dir') ?>">
	<head>
		<meta http-equiv="Content-Type" content="<?php $this->text('mimetype') ?>; charset=<?php $this->text('charset') ?>" />
		<title><?php $this->text('pagetitle') ?></title>
		<!-- headlinks -->
		<?php $this->html('headlinks') ?>
		<!-- /headlinks -->
		<!-- csslinks -->
		<?php $this->html('csslinks') ?>
		<!-- /csslinks -->
		<!-- IEFixes -->
		<!--[if lt IE 7]><script type="<?php $this->text('jsmimetype') ?>" src="<?php $this->text('stylepath') ?>/common/IEFixes.js?<?php echo $GLOBALS['wgStyleVersion'] ?>"></script>
		<meta http-equiv="imagetoolbar" content="no" /><![endif]-->
		<style type="text/css">body{behavior:url("<?php $this->text('stylepath') ?>/vector/csshover.htc")}</style>
		<!-- /IEFixes -->
		<!-- globalVariablesScript -->
		<?php echo Skin::makeGlobalVariablesScript( $this->data ); ?>
		<!-- /globalVariablesScript -->
		<!-- wikibits -->
		<script type="<?php $this->text('jsmimetype') ?>" src="<?php $this->text('stylepath' ) ?>/common/wikibits.js?<?php echo $GLOBALS['wgStyleVersion'] ?>"><!-- wikibits js --></script>
		<!-- /wikibits -->
		<!-- headscripts -->
		<?php $this->html('headscripts') ?>
		<!-- /headscripts -->
		<?php if($this->data['jsvarurl']): ?>
		<!-- jsvarurl -->
		<script type="<?php $this->text('jsmimetype') ?>" src="<?php $this->text('jsvarurl') ?>"><!-- site js --></script>
		<!-- /jsvarurl -->
		<?php endif; ?>
		<?php if($this->data['pagecss']): ?>
		<!-- pagecss -->
		<style type="text/css"><?php $this->html('pagecss') ?></style>
		<!-- /pagecss -->
		<?php endif; ?>
		<?php if($this->data['usercss']): ?>
		<!-- usercss -->
		<style type="text/css"><?php $this->html('usercss') ?></style>
		<!-- /usercss -->
		<?php endif; ?>
		<?php if($this->data['userjs']): ?>
		<!-- userjs -->
		<script type="<?php $this->text('jsmimetype') ?>" src="<?php $this->text('userjs' ) ?>"></script>
		<!-- /userjs -->
		<?php endif; ?>
		<?php if($this->data['userjsprev']): ?>
		<!-- userjsprev -->
		<script type="<?php $this->text('jsmimetype') ?>"><?php $this->html('userjsprev') ?></script>
		<!-- /userjsprev -->
		<?php endif; ?>
		<?php if($this->data['trackbackhtml']): ?>
		<!-- trackbackhtml -->
		<?php echo $this->data['trackbackhtml']; ?>
		<!-- /trackbackhtml -->
		<?php endif; ?>
	</head>
	<body<?php if($this->data['body_ondblclick']): ?> ondblclick="<?php $this->text('body_ondblclick') ?>"<?php endif; ?> <?php if($this->data['body_onload']): ?> onload="<?php $this->text('body_onload') ?>"<?php endif; ?> class="mediawiki <?php $this->text('dir') ?> <?php $this->text('pageclass') ?> <?php $this->text('skinnameclass') ?>">
		<div id="page-base" class="noprint"></div>
		<div id="head-base" class="noprint"></div>
		<!-- content -->
		<div id="content">
			<a name="top" id="top"></a>
			<div id="mw-js-message" style="display:none;"></div>
			<!-- sitenotice -->
			<?php if($this->data['sitenotice']) { ?><div id="siteNotice"><?php $this->html('sitenotice') ?></div><?php } ?>
			<!-- /sitenotice -->
			<!-- firstHeading -->
			<h1 id="firstHeading" class="firstHeading"><?php $this->html('title') ?></h1>
			<!-- /firstHeading -->
			<!-- bodyContent -->
			<div id="bodyContent">
				<!-- tagline -->
				<h3 id="siteSub"><?php $this->msg('tagline') ?></h3>
				<!-- /tagline -->
				<!-- subtitle -->
				<div id="contentSub"><?php $this->html('subtitle') ?></div>
				<!-- /subtitle -->
				<?php if($this->data['undelete']): ?>
				<!-- undelete -->
				<div id="contentSub2"><?php $this->html('undelete') ?></div>
				<!-- /undelete -->
				<?php endif; ?>
				<?php if($this->data['newtalk'] ): ?>
				<!-- newtalk -->
				<div class="usermessage"><?php $this->html('newtalk')  ?></div>
				<!-- /newtalk -->
				<?php endif; ?>
				<?php if($this->data['showjumplinks']): ?>
				<!-- jumpto -->
				<div id="jump-to-nav">
					<?php $this->msg('jumpto') ?> <a href="#head"><?php $this->msg('jumptonavigation') ?></a>,
					<a href="#search"><?php $this->msg('jumptosearch') ?></a>
				</div>
				<!-- /jumpto -->
				<?php endif; ?>
				<!-- bodytext -->
				<?php $this->html('bodytext') ?>
				<!-- /bodytext -->
				<!-- catlinks -->
				<?php if($this->data['catlinks']) { $this->html('catlinks'); } ?>
				<!-- /catlinks -->
				<!-- dataAfterContent -->
				<?php if($this->data['dataAfterContent']) { $this->html('dataAfterContent'); } ?>
				<!-- /dataAfterContent -->
				<div class="visualClear"></div>
			</div>
			<!-- /bodyContent -->
		</div>
		<!-- /content -->
		<!-- header -->
		<div id="head" class="noprint">
			<!-- personal -->
			<?php if ( count( $this->data['personal_urls'] ) > 0 ): ?>
			<div id="p-personal">
				<h5><?php $this->msg('personaltools') ?></h5>
				<ul <?php $this->html('userlangattributes') ?>>
					<?php foreach($this->data['personal_urls'] as $key => $item): ?>
						<li <?php echo $item['attributes'] ?>><a href="<?php echo htmlspecialchars($item['href']) ?>"<?php echo $item['key'] ?><?php if(!empty($item['class'])): ?> class="<?php echo htmlspecialchars($item['class']) ?>"<?php endif; ?>><?php echo htmlspecialchars($item['text']) ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
			<?php endif; ?>
			<!-- /personal -->
			<div id="left-navigation">
				<!-- namespaces -->
				<?php if ( count( $this->data['namespace_urls'] ) > 0 ): ?>
				<div id="namespaces" class="vectorTabs">
					<h5><?php $this->msg('namespaces') ?></h5>
					<ul <?php $this->html('userlangattributes') ?>>
						<?php foreach ($this->data['namespace_urls'] as $key => $link ): ?>
							<li <?php echo $link['attributes'] ?>><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><span><?php echo htmlspecialchars( $link['text'] ) ?></span></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>
				<!-- /namespaces -->
				<!-- variants -->
				<?php if ( count( $this->data['variant_urls'] ) > 0 ): ?>
				<div id="variants" class="vectorMenu">
					<h5><span><?php $this->msg('variants') ?></span><a href="#">&nbsp;</a></h5>
					<div class="menu">
						<ul <?php $this->html('userlangattributes') ?>>
							<?php foreach ($this->data['variant_urls'] as $key => $link ): ?>
								<li<?php echo $link['attributes'] ?>><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<?php endif; ?>
				<!-- /variants -->
			</div>
			<div id="right-navigation">
				<!-- views -->
				<?php if ( count( $this->data['view_urls'] ) > 0 ): ?>
				<div id="views" class="vectorTabs">
					<h5><?php $this->msg('views') ?></h5>
					<ul <?php $this->html('userlangattributes') ?>>
						<?php foreach ($this->data['view_urls'] as $key => $link ): ?>
							<li<?php echo $link['attributes'] ?>><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><span><?php echo htmlspecialchars( $link['text'] ) ?></span></a></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>
				<!-- /views -->
				<!-- actions -->
				<?php if ( count( $this->data['action_urls'] ) > 0 ): ?>
				<div id="p-cactions" class="vectorMenu">
					<h5><span><?php $this->msg('actions') ?></span><a href="#">&nbsp;</a></h5>
					<div class="menu">
						<ul <?php $this->html('userlangattributes') ?>>
							<?php foreach ($this->data['action_urls'] as $key => $link ): ?>
								<li<?php echo $link['attributes'] ?>><a href="<?php echo htmlspecialchars( $link['href'] ) ?>" <?php echo $link['key'] ?>><?php echo htmlspecialchars( $link['text'] ) ?></a></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				<?php endif; ?>
				<!-- /actions -->
				<!-- search -->
				<div id="p-search">
					<h5 <?php $this->html('userlangattributes') ?>><label for="searchInput"><?php $this->msg( 'search' ) ?></label></h5>
					<form action="<?php $this->text( 'wgScript' ) ?>" id="searchform">
						<input type='hidden' name="title" value="<?php $this->text( 'searchtitle' ) ?>"/>
						<input id="searchInput" name="search" type="text" <?php echo $this->skin->tooltipAndAccesskey( 'search' ); ?> <?php if( isset( $this->data['search'] ) ): ?> value="<?php $this->text( 'search' ) ?>"<?php endif; ?> />
						<input type='submit' name="go" class="searchButton" id="searchGoButton"	value="<?php $this->msg( 'searcharticle' ) ?>"<?php echo $this->skin->tooltipAndAccesskey( 'search-go' ); ?> />
						<?php if ( $wgUseTwoButtonsSearchForm ): ?>
						<input type="submit" name="fulltext" class="searchButton" id="mw-searchButton" value="<?php $this->msg( 'searchbutton' ) ?>"<?php echo $this->skin->tooltipAndAccesskey( 'search-fulltext' ); ?> />
						<?php else: ?>
						<div><a href="<?php $this->text( 'searchaction' ) ?>" rel="search"><?php $this->msg( 'powersearch-legend' ) ?></a></div>
						<?php endif; ?>
					</form>
				</div>
				<!-- /search -->
			</div>
		</div>
		<!-- /header -->
		<!-- panel -->
		<div id="panel" class="noprint">
			<!-- sidebar -->
			<?php
				$sidebar = $this->data['sidebar'];
				$sidebar['TOOLBOX'] = ( !isset( $sidebar['TOOLBOX'] ) );
				$sidebar['LANGUAGES'] = ( !isset( $sidebar['LANGUAGES'] ) );
				foreach ( $sidebar as $name => $content ) {
					switch( $name ) {
						case 'SEARCH':
							break;
						case 'TOOLBOX':
							$this->toolBox();
							break;
						case 'LANGUAGES':
							$this->languageBox();
							break;
						default:
							$this->customBox( $name, $content );
							break;
					}
				}
			?>
			<!-- /sidebar -->
		</div>
		<!-- /panel -->
		<div class="break"></div>
		<!-- foot -->
		<div id="foot">
			<?php foreach( $validFooterLinks as $category => $links ): ?>
				<?php if ( count( $links ) > 0 ): ?>
				<ul id="foot-<?php echo $category ?>">
					<?php foreach( $links as $link ): ?>
						<?php if( isset( $this->data[$link] ) && $this->data[$link] ): ?>
						<li id="foot-<?php echo $category ?>-<?php echo $link ?>"><?php $this->html( $link ) ?></li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			<?php endforeach; ?>
			<ul id="foot-icons" class="noprint">
				<?php if( $this->data['poweredbyico'] ): ?>
				<li id="foot-icon-poweredby"><?php $this->html( 'poweredbyico' ) ?></li>
				<?php endif; ?>
				<?php if( $this->data['copyrightico'] ): ?>
				<li id="foot-icon-copyright"><?php $this->html( 'copyrightico' ) ?></li>
				<?php endif; ?>
			</ul>
			<div style="clear:both"></div>
		</div>
		<!-- /foot -->
		<!-- logo -->
		<div id="p-logo">
			<a style="background-image: url(<?php $this->text('logopath') ?>);" href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>" <?php echo $this->skin->tooltipAndAccesskey('p-logo') ?>></a>
		</div>
		<!-- /logo -->
		<!-- fixalpha -->
		<script type="<?php $this->text('jsmimetype') ?>"> if (window.isMSIE55) fixalpha( 'p-logo' ); </script>
		<!-- /fixalpha -->
		<?php $this->html( 'bottomscripts' ); /* JS call to runBodyOnloadHook */ ?>
		<?php $this->html( 'reporttime' ) ?>
		<?php if ( $this->data['debug'] ): ?>
		<!-- Debug output:
		<?php $this->text( 'debug' ); ?>
		-->
		<?php endif; ?>
	</body>
</html>
<?php
		// We're done with abusing arrays now...
		wfRestoreWarnings();
	}

	/**
	 * Outputs a box with a list of tools
	 */
	private function toolBox() {
?>
	<div class="portal" id="p-tb">
		<h5 <?php $this->html('userlangattributes') ?>><?php $this->msg( 'toolbox' ) ?></h5>
		<div class="body">
			<ul>
			<?php if( $this->data['notspecialpage'] ): ?>
				<li id="t-whatlinkshere"><a href="<?php echo htmlspecialchars( $this->data['nav_urls']['whatlinkshere']['href'] ) ?>"<?php echo $this->skin->tooltipAndAccesskey( 't-whatlinkshere' ) ?>><?php $this->msg( 'whatlinkshere' ) ?></a></li>
				<?php if( $this->data['nav_urls']['recentchangeslinked'] ): ?>
				<li id="t-recentchangeslinked"><a href="<?php echo htmlspecialchars( $this->data['nav_urls']['recentchangeslinked']['href'] ) ?>"<?php echo $this->skin->tooltipAndAccesskey( 't-recentchangeslinked' ) ?>><?php $this->msg( 'recentchangeslinked-toolbox' ) ?></a></li>
				<?php endif; ?>
			<?php endif; ?>
			<?php if( isset( $this->data['nav_urls']['trackbacklink'] ) ): ?>
			<li id="t-trackbacklink"><a href="<?php echo htmlspecialchars( $this->data['nav_urls']['trackbacklink']['href'] ) ?>"<?php echo $this->skin->tooltipAndAccesskey( 't-trackbacklink' ) ?>><?php $this->msg( 'trackbacklink' ) ?></a></li>
			<?php endif; ?>
			<?php if( $this->data['feeds']): ?>
			<li id="feedlinks">
				<?php foreach( $this->data['feeds'] as $key => $feed ): ?>
				<a id="<?php echo Sanitizer::escapeId( "feed-$key" ) ?>" href="<?php echo htmlspecialchars( $feed['href'] ) ?>" rel="alternate" type="application/<?php echo $key ?>+xml" class="feedlink"<?php echo $this->skin->tooltipAndAccesskey( 'feed-' . $key ) ?>><?php echo htmlspecialchars( $feed['text'] ) ?></a>
				<?php endforeach; ?>
			</li>
			<?php endif; ?>
			<?php foreach( array( 'contributions', 'log', 'blockip', 'emailuser', 'upload', 'specialpages' ) as $special ): ?>
				<?php if( $this->data['nav_urls'][$special]): ?>
				<li id="t-<?php echo $special ?>"><a href="<?php echo htmlspecialchars( $this->data['nav_urls'][$special]['href'] ) ?>"<?php echo $this->skin->tooltipAndAccesskey( 't-' . $special ) ?>><?php $this->msg( $special ) ?></a></li>
				<?php endif; ?>
			<?php endforeach; ?>
			<?php if( !empty( $this->data['nav_urls']['print']['href'] ) ): ?>
			<li id="t-print"><a href="<?php echo htmlspecialchars( $this->data['nav_urls']['print']['href'] ) ?>" rel="alternate"<?php echo $this->skin->tooltipAndAccesskey( 't-print' ) ?>><?php $this->msg( 'printableversion' ) ?></a></li>
			<?php endif; ?>
			<?php if (  !empty(  $this->data['nav_urls']['permalink']['href'] ) ): ?>
			<li id="t-permalink"><a href="<?php echo htmlspecialchars( $this->data['nav_urls']['permalink']['href'] ) ?>"<?php echo $this->skin->tooltipAndAccesskey( 't-permalink' ) ?>><?php $this->msg( 'permalink' ) ?></a></li>
			<?php elseif ( $this->data['nav_urls']['permalink']['href'] === '' ): ?>
			<li id="t-ispermalink"<?php echo $this->skin->tooltip( 't-ispermalink' ) ?>><?php $this->msg( 'permalink' ) ?></li>
			<?php endif; ?>
			<?php wfRunHooks( 'VectorTemplateToolboxEnd', array( &$this ) ); ?>
			<?php wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this ) ); ?>
			</ul>
		</div>
	</div>
<?php
	}

	/**
	 * Outputs a box with a list of alternative languages for this page
	 */
	private function languageBox() {
		if( $this->data['language_urls'] ) {
?>
<div class="portal" id="p-lang">
	<h5 <?php $this->html('userlangattributes') ?>><?php $this->msg( 'otherlanguages' ) ?></h5>
	<div class="body">
		<ul>
		<?php foreach ( $this->data['language_urls'] as $langlink ): ?>
			<li class="<?php echo htmlspecialchars(  $langlink['class'] ) ?>"><a href="<?php echo htmlspecialchars( $langlink['href'] ) ?>"><?php echo $langlink['text'] ?></a></li>
		<?php endforeach; ?>
		</ul>
	</div>
</div>
<?php
		}
	}

	/**
	 * Outputs a box with a custom list of items or HTML content
	 * @param string $bar Message name for title of box
	 * @param mixed $content HTML or array of items to build a list from
	 */
	private function customBox( $bar, $content ) {
?>
<div class="portal" id='<?php echo Sanitizer::escapeId( "p-$bar" ) ?>'<?php echo $this->skin->tooltip( 'p-' . $bar ) ?>>
	<h5 <?php $this->html('userlangattributes') ?>><?php $out = wfMsg( $bar ); if ( wfEmptyMsg( $bar, $out ) ) echo htmlspecialchars( $bar ); else echo htmlspecialchars( $out ); ?></h5>
	<div class="body">
		<?php if ( is_array( $content ) ): ?>
		<ul>
		<?php foreach( $content as $key => $val ): ?>
			<li id="<?php echo Sanitizer::escapeId( $val['id'] ) ?>"<?php if ( $val['active'] ): ?> class="active" <?php endif; ?>><a href="<?php echo htmlspecialchars( $val['href'] ) ?>"<?php echo $this->skin->tooltipAndAccesskey( $val['id'] ) ?>><?php echo htmlspecialchars( $val['text'] ) ?></a></li>
		<?php endforeach; ?>
		</ul>
		<?php else: ?>
		<?php echo $content; /* Allow raw HTML block to be defined by extensions */ ?>
		<?php endif; ?>
	</div>
</div>
<?php
	}
}
