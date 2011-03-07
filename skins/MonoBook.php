<?php
/**
 * MonoBook nouveau
 *
 * Translated from gwicke's previous TAL template version to remove
 * dependency on PHPTAL.
 *
 * @todo document
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @ingroup Skins
 */
class SkinMonoBook extends SkinTemplate {
	/** Using monobook. */
	var $skinname = 'monobook', $stylename = 'monobook',
		$template = 'MonoBookTemplate', $useHeadElement = true;

	function setupSkinUserCss( OutputPage $out ) {
		global $wgHandheldStyle;
		parent::setupSkinUserCss( $out );

		$out->addModuleStyles( 'skins.monobook' );
		
		// Ugh. Can't do this properly because $wgHandheldStyle may be a URL
		if( $wgHandheldStyle ) {
			// Currently in testing... try 'chick/main.css'
			$out->addStyle( $wgHandheldStyle, 'handheld' );
		}
		
		// TODO: Migrate all of these
		//$out->addStyle( 'monobook/IE50Fixes.css', 'screen', 'lt IE 5.5000' );
		//$out->addStyle( 'monobook/IE55Fixes.css', 'screen', 'IE 5.5000' );
		//$out->addStyle( 'monobook/IE60Fixes.css', 'screen', 'IE 6' );
		//$out->addStyle( 'monobook/IE70Fixes.css', 'screen', 'IE 7' );

		// TODO: migrate
		//$out->addStyle( 'monobook/rtl.css', 'screen', '', 'rtl' );

	}
}

/**
 * @todo document
 * @ingroup Skins
 */
class MonoBookTemplate extends BaseTemplate {

	/**
	 * @var Skin
	 */
	var $skin;

	/**
	 * Template filter callback for MonoBook skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 *
	 * @access private
	 */
	function execute() {
		$this->skin = $skin = $this->data['skin'];

		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();

		$this->html( 'headelement' );
?><div id="globalWrapper">
<div id="column-content"><div id="content"<?php $this->html("specialpageattributes") ?>>
	<a id="top"></a>
	<?php if($this->data['sitenotice']) { ?><div id="siteNotice"><?php $this->html('sitenotice') ?></div><?php } ?>

	<h1 id="firstHeading" class="firstHeading"><?php $this->html('title') ?></h1>
	<div id="bodyContent">
		<div id="siteSub"><?php $this->msg('tagline') ?></div>
		<div id="contentSub"<?php $this->html('userlangattributes') ?>><?php $this->html('subtitle') ?></div>
<?php if($this->data['undelete']) { ?>
		<div id="contentSub2"><?php $this->html('undelete') ?></div>
<?php } ?><?php if($this->data['newtalk'] ) { ?>
		<div class="usermessage"><?php $this->html('newtalk')  ?></div>
<?php } ?><?php if($this->data['showjumplinks']) { ?>
		<div id="jump-to-nav"><?php $this->msg('jumpto') ?> <a href="#column-one"><?php $this->msg('jumptonavigation') ?></a>, <a href="#searchInput"><?php $this->msg('jumptosearch') ?></a></div>
<?php } ?>
		<!-- start content -->
<?php $this->html('bodytext') ?>
		<?php if($this->data['catlinks']) { $this->html('catlinks'); } ?>
		<!-- end content -->
		<?php if($this->data['dataAfterContent']) { $this->html ('dataAfterContent'); } ?>
		<div class="visualClear"></div>
	</div>
</div></div>
<div id="column-one"<?php $this->html('userlangattributes')  ?>>
<?php $this->cactions( $skin ); ?>
	<div class="portlet" id="p-personal">
		<h5><?php $this->msg('personaltools') ?></h5>
		<div class="pBody">
			<ul<?php $this->html('userlangattributes') ?>>
<?php		foreach($this->getPersonalTools() as $key => $item) { ?>
				<?php echo $this->makeListItem($key, $item); ?>

<?php		} ?>
			</ul>
		</div>
	</div>
	<div class="portlet" id="p-logo">
		<?php
			$logoAttribs = array() + $skin->tooltipAndAccesskeyAttribs('p-logo');
			$logoAttribs['style'] = "background-image: url({$this->data['logopath']});";
			$logoAttribs['href'] = $this->data['nav_urls']['mainpage']['href'];
			echo Html::element( 'a', $logoAttribs );
		?>

	</div>
	<script type="<?php $this->text('jsmimetype') ?>"> if (window.isMSIE55) fixalpha(); </script>
<?php
		$sidebar = $this->data['sidebar'];
		if ( !isset( $sidebar['SEARCH'] ) ) $sidebar['SEARCH'] = true;
		if ( !isset( $sidebar['TOOLBOX'] ) ) $sidebar['TOOLBOX'] = true;
		if ( !isset( $sidebar['LANGUAGES'] ) ) $sidebar['LANGUAGES'] = true;
		foreach ($sidebar as $boxName => $cont) {
			if ( $boxName == 'SEARCH' ) {
				$this->searchBox();
			} elseif ( $boxName == 'TOOLBOX' ) {
				$this->toolbox();
			} elseif ( $boxName == 'LANGUAGES' ) {
				$this->languageBox();
			} else {
				$this->customBox( $boxName, $cont );
			}
		}
?>
</div><!-- end of the left (by default at least) column -->
<div class="visualClear"></div>
<div id="footer"<?php $this->html('userlangattributes') ?>>
<?php foreach ( $this->getFooterIcons("icononly") as $blockName => $footerIcons ) { ?>
	<div id="f-<?php echo htmlspecialchars($blockName); ?>ico">
<?php foreach ( $footerIcons as $icon ) { ?>
		<?php echo $this->skin->makeFooterIcon( $icon ); ?>

<?php }
?>
	</div>
<?php }

		// Generate additional footer links
		$validFooterLinks = $this->getFooterLinks("flat");
		if ( count( $validFooterLinks ) > 0 ) {
?>	<ul id="f-list">
<?php
			foreach( $validFooterLinks as $aLink ) { ?>
		<li id="<?php echo $aLink ?>"><?php $this->html($aLink) ?></li>

<?php
			}
?>
	</ul>
<?php	}
?>
</div>
</div>
<?php
		$this->printTrail();
		echo Html::closeElement( 'body' );
		echo Html::closeElement( 'html' );
		wfRestoreWarnings();
	} // end of execute() method

	/*************************************************************************************************/
	function searchBox() {
		global $wgUseTwoButtonsSearchForm;
?>
	<div id="p-search" class="portlet">
		<h5><label for="searchInput"><?php $this->msg('search') ?></label></h5>
		<div id="searchBody" class="pBody">
			<form action="<?php $this->text('wgScript') ?>" id="searchform">
				<input type='hidden' name="title" value="<?php $this->text('searchtitle') ?>"/>
				<?php echo $this->makeSearchInput(array( "id" => "searchInput" )); ?>

				<?php echo $this->makeSearchButton("go", array( "id" => "searchGoButton", "class" => "searchButton" ));
				if ($wgUseTwoButtonsSearchForm): ?>&#160;
				<?php echo $this->makeSearchButton("fulltext", array( "id" => "mw-searchButton", "class" => "searchButton" ));
				else: ?>

				<div><a href="<?php $this->text('searchaction') ?>" rel="search"><?php $this->msg('powersearch-legend') ?></a></div><?php
				endif; ?>

			</form>
		</div>
	</div>
<?php
	}

	/**
	 * Prints the cactions bar.
	 * Shared between MonoBook and Modern
	 */
	function cactions( Skin $skin ) {
?>
	<div id="p-cactions" class="portlet">
		<h5><?php $this->msg('views') ?></h5>
		<div class="pBody">
			<ul><?php
				foreach($this->data['content_actions'] as $key => $tab) {
					$linkAttribs = array( 'href' => $tab['href'] );
					
				 	if( isset( $tab["tooltiponly"] ) && $tab["tooltiponly"] ) {
						$title = $skin->titleAttrib( "ca-$key" );
						if ( $title !== false ) {
							$linkAttribs['title'] = $title;
						}
				 	} else {
						$linkAttribs += $skin->tooltipAndAccesskeyAttribs( "ca-$key" );
				 	}
				 	$linkHtml = Html::element( 'a', $linkAttribs, $tab['text'] );
				 	
				 	/* Surround with a <li> */
				 	$liAttribs = array( 'id' => Sanitizer::escapeId( "ca-$key" ) );
					if( $tab['class'] ) {
						$liAttribs['class'] = $tab['class'];
					}
				 	echo '
				' . Html::rawElement( 'li', $liAttribs, $linkHtml );
				} ?>

			</ul>
		</div>
	</div>
<?php
	}
	/*************************************************************************************************/
	function toolbox() {
?>
	<div class="portlet" id="p-tb">
		<h5><?php $this->msg('toolbox') ?></h5>
		<div class="pBody">
			<ul>
<?php
		foreach ( $this->getToolbox() as $key => $tbitem ) { ?>
				<?php echo $this->makeListItem($key, $tbitem); ?>

<?php
		}
		wfRunHooks( 'MonoBookTemplateToolboxEnd', array( &$this ) );
		wfRunHooks( 'SkinTemplateToolboxEnd', array( &$this ) );
?>
			</ul>
		</div>
	</div>
<?php
	}

	/*************************************************************************************************/
	function languageBox() {
		if( $this->data['language_urls'] ) {
?>
	<div id="p-lang" class="portlet">
		<h5<?php $this->html('userlangattributes') ?>><?php $this->msg('otherlanguages') ?></h5>
		<div class="pBody">
			<ul>
<?php		foreach($this->data['language_urls'] as $key => $langlink) { ?>
				<?php echo $this->makeListItem($key, $langlink); ?>

<?php		} ?>
			</ul>
		</div>
	</div>
<?php
		}
	}

	/*************************************************************************************************/
	function customBox( $bar, $cont ) {
		$portletAttribs = array( 'class' => 'generated-sidebar portlet', 'id' => Sanitizer::escapeId( "p-$bar" ) );
		$tooltip = $this->skin->titleAttrib( "p-$bar" );
		if ( $tooltip !== false ) {
			$portletAttribs['title'] = $tooltip;
		}
		echo '	' . Html::openElement( 'div', $portletAttribs );
?>

		<h5><?php $out = wfMsg( $bar ); if (wfEmptyMsg($bar)) echo htmlspecialchars($bar); else echo htmlspecialchars($out); ?></h5>
		<div class='pBody'>
<?php   if ( is_array( $cont ) ) { ?>
			<ul>
<?php 			foreach($cont as $key => $val) { ?>
				<?php echo $this->makeListItem($key, $val); ?>

<?php			} ?>
			</ul>
<?php   } else {
			# allow raw HTML block to be defined by extensions
			print $cont;
		}
?>
		</div>
	</div>
<?php
	}
} // end of class


