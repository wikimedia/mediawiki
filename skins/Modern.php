<?php
/**
 * Modern skin, derived from monobook template.
 *
 * @todo document
 * @file
 * @ingroup Skins
 */

if( !defined( 'MEDIAWIKI' ) )
	die( -1 );

require( dirname(__FILE__) . '/MonoBook.php' );

/**
 * Inherit main code from SkinTemplate, set the CSS and template filter.
 * @todo document
 * @ingroup Skins
 */
class SkinModern extends SkinTemplate {
	var $skinname = 'modern', $stylename = 'modern',
		$template = 'ModernTemplate', $useHeadElement = true;

	function setupSkinUserCss( OutputPage $out ){
		parent::setupSkinUserCss( $out );
		$out->addModuleStyles ('skins.modern');
	}
}

/**
 * @todo document
 * @ingroup Skins
 */
class ModernTemplate extends MonoBookTemplate {
	var $skin;
	/**
	 * Template filter callback for Modern skin.
	 * Takes an associative array of data set from a SkinTemplate-based
	 * class, and a wrapper for MediaWiki's localization database, and
	 * outputs a formatted page.
	 *
	 * @access private
	 */
	function execute() {
		global $wgRequest;
		$this->skin = $skin = $this->data['skin'];
		$action = $wgRequest->getText( 'action' );

		// Suppress warnings to prevent notices about missing indexes in $this->data
		wfSuppressWarnings();

		$this->html( 'headelement' );
?>

	<!-- heading -->
	<div id="mw_header"><h1 id="firstHeading"><?php $this->html('title') ?></h1></div>

	<div id="mw_main">
	<div id="mw_contentwrapper">
	<!-- navigation portlet -->
<?php $this->cactions( $skin ); ?>

	<!-- content -->
	<div id="mw_content">
	<!-- contentholder does nothing by default, but it allows users to style the text inside
	     the content area without affecting the meaning of 'em' in #mw_content, which is used
	     for the margins -->
	<div id="mw_contentholder" <?php $this->html("specialpageattributes") ?>>
		<div class='mw-topboxes'>
			<div id="mw-js-message" style="display:none;"<?php $this->html('userlangattributes')?>></div>
			<div class="mw-topbox" id="siteSub"><?php $this->msg('tagline') ?></div>
			<?php if($this->data['newtalk'] ) {
				?><div class="usermessage mw-topbox"><?php $this->html('newtalk')  ?></div>
			<?php } ?>
			<?php if($this->data['sitenotice']) {
				?><div class="mw-topbox" id="siteNotice"><?php $this->html('sitenotice') ?></div>
			<?php } ?>
		</div>

		<div id="contentSub"<?php $this->html('userlangattributes') ?>><?php $this->html('subtitle') ?></div>

		<?php if($this->data['undelete']) { ?><div id="contentSub2"><?php     $this->html('undelete') ?></div><?php } ?>
		<?php if($this->data['showjumplinks']) { ?><div id="jump-to-nav"><?php $this->msg('jumpto') ?> <a href="#mw_portlets"><?php $this->msg('jumptonavigation') ?></a>, <a href="#searchInput"><?php $this->msg('jumptosearch') ?></a></div><?php } ?>

		<?php $this->html('bodytext') ?>
		<div class='mw_clear'></div>
		<?php if($this->data['catlinks']) { $this->html('catlinks'); } ?>
		<?php $this->html ('dataAfterContent') ?>
	</div><!-- mw_contentholder -->
	</div><!-- mw_content -->
	</div><!-- mw_contentwrapper -->

	<div id="mw_portlets"<?php $this->html("userlangattributes") ?>>

	<!-- portlets -->
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

	</div><!-- mw_portlets -->


	</div><!-- main -->

	<div class="mw_clear"></div>

	<!-- personal portlet -->
	<div class="portlet" id="p-personal">
		<h5><?php $this->msg('personaltools') ?></h5>
		<div class="pBody">
			<ul>
<?php		foreach($this->getPersonalTools() as $key => $item) { ?>
				<?php echo $this->makeListItem($key, $item); ?>

<?php		} ?>
			</ul>
		</div>
	</div>


	<!-- footer -->
	<div id="footer"<?php $this->html('userlangattributes') ?>>
			<ul id="f-list">
<?php
		foreach( $this->getFooterLinks("flat") as $aLink ) {
			if( isset( $this->data[$aLink] ) && $this->data[$aLink] ) {
?>				<li id="<?php echo$aLink?>"><?php $this->html($aLink) ?></li>
<?php 		}
		}
?>
			</ul>
<?php
		foreach ( $this->getFooterIcons("nocopyright") as $blockName => $footerIcons ) { ?>
			<div id="mw_<?php echo htmlspecialchars($blockName); ?>">
<?php
			foreach ( $footerIcons as $icon ) { ?>
				<?php echo $this->skin->makeFooterIcon( $icon, 'withoutImage' ); ?>

<?php
			} ?>
			</div>
<?php
		}
?>
	</div>

	<?php $this->printTrail(); ?>
</body></html>
<?php
	wfRestoreWarnings();
	} // end of execute() method
} // end of class


