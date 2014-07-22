<?php
/**
 * Skin file for the fallback skin.
 *
 * The structure is copied from the example skin (mediawiki/skins/Example).
 *
 * @since 1.24
 * @file
 */

/**
 * SkinTemplate class for the fallback skin
 */
class SkinFallback extends SkinTemplate {
	var $skinname = 'fallback', $template = 'SkinFallbackTemplate';

	/**
	 * Add CSS via ResourceLoader
	 *
	 * @param $out OutputPage
	 */
	function setupSkinUserCss( OutputPage $out ) {
		parent::setupSkinUserCss( $out );
		$out->addModuleStyles( 'mediawiki.skinning.interface' );
	}
}

/**
 * BaseTemplate class for the fallback skin
 */
class SkinFallbackTemplate extends BaseTemplate {
	/**
	 * Outputs a single sidebar portlet of any kind.
	 */
	private function outputPortlet( $box ) {
		if ( !$box['content'] ) {
			return;
		}

		?>
		<div
			role="navigation"
			class="mw-portlet"
			id="<?php echo Sanitizer::escapeId( $box['id'] ) ?>"
			<?php echo Linker::tooltip( $box['id'] ) ?>
		>
			<h3>
				<?php
				if ( isset( $box['headerMessage'] ) ) {
					$this->msg( $box['headerMessage'] );
				} else {
					echo htmlspecialchars( $box['header'] );
				}
				?>
			</h3>

			<?php
			if ( is_array( $box['content'] ) ) {
				echo '<ul>';
				foreach ( $box['content'] as $key => $item ) {
					echo $this->makeListItem( $key, $item );
				}
				echo '</ul>';
			} else {
				echo $box['content'];
			}?>
		</div>
		<?php
	}

	private function findInstalledSkins() {
		global $wgStyleDirectory;

		// Get all subdirectories which might contains skins
		$possibleSkins = scandir( $wgStyleDirectory );
		$possibleSkins = array_filter( $possibleSkins, function ( $maybeDir ) {
			global $wgStyleDirectory;
			return $maybeDir !== '.' && $maybeDir !== '..' && is_dir( "$wgStyleDirectory/$maybeDir" );
		} );

		// Only keep the ones that contain a .php file with the same name inside
		$possibleSkins = array_filter( $possibleSkins, function ( $skinDir ) {
			global $wgStyleDirectory;
			return is_file( "$wgStyleDirectory/$skinDir/$skinDir.php" );
		} );

		return $possibleSkins;
	}

	/**
	 * Inform the user why they are seeing this.
	 *
	 * @return string
	 */
	private function buildHelpfulInformationMessage() {
		global $wgDefaultSkin, $wgValidSkinNames;

		$installedSkins = $this->findInstalledSkins();
		$enabledSkins = $wgValidSkinNames;
		$enabledSkins = array_change_key_case( $enabledSkins, CASE_LOWER );

		if ( $installedSkins ) {
			$skinsInstalledText = array();
			$skinsInstalledSnippet = array();

			foreach ( $installedSkins as $skin ) {
				$normalizedKey = strtolower( $skin );
				$isEnabled = array_key_exists( $normalizedKey, $enabledSkins );
				if ( $isEnabled ) {
					$skinsInstalledText[] = $this->getMsg( 'default-skin-not-found-row-enabled' )
						->params( $normalizedKey, $skin )->plain();
				} else {
					$skinsInstalledText[] = $this->getMsg( 'default-skin-not-found-row-disabled' )
						->params( $normalizedKey, $skin )->plain();
					$skinsInstalledSnippet[] = "require_once \"\$IP/skins/$skin/$skin.php\";";
				}
			}

			return $this->getMsg( 'default-skin-not-found' )->params(
				$wgDefaultSkin,
				implode( "\n", $skinsInstalledText ),
				implode( "\n", $skinsInstalledSnippet )
			)->parseAsBlock();
		} else {
			return $this->getMsg( 'default-skin-not-found-no-skins' )->params(
				$wgDefaultSkin
			)->parseAsBlock();
		}
	}

	/**
	 * Outputs the entire contents of the page
	 */
	public function execute() {
		$this->html( 'headelement' ) ?>
		<div id="mw-wrapper">
			<a
				id="p-logo"
				role="banner"
				href="<?php echo htmlspecialchars( $this->data['nav_urls']['mainpage']['href'] ) ?>"
				<?php echo Xml::expandAttributes( Linker::tooltipAndAccesskeyAttribs( 'p-logo' ) ) ?>
			>
				<img
					src="<?php $this->text( 'logopath' ) ?>"
					alt="<?php $this->text( 'sitename' ) ?>"
				/>
			</a>


			<div class="warningbox">
				<?php echo $this->buildHelpfulInformationMessage() ?>
			</div>


			<div class="mw-body" role="main">
				<?php if ( $this->data['sitenotice'] ) { ?>
					<div id="siteNotice"><?php $this->html( 'sitenotice' ) ?></div>
				<?php } ?>

				<?php if ( $this->data['newtalk'] ) { ?>
					<div class="usermessage"><?php $this->html( 'newtalk' ) ?></div>
				<?php } ?>

				<h1 class="firstHeading">
					<span dir="auto"><?php $this->html( 'title' ) ?></span>
				</h1>

				<div id="siteSub"><?php $this->msg( 'tagline' ) ?></div>

				<div class="mw-body-content">
					<div id="contentSub">
						<?php if ( $this->data['subtitle'] ) { ?>
							<p><?php $this->html( 'subtitle' ) ?></p>
						<?php } ?>
						<?php if ( $this->data['undelete'] ) { ?>
							<p><?php $this->html( 'undelete' ) ?></p>
						<?php } ?>
					</div>

					<?php $this->html( 'bodytext' ) ?>

					<?php $this->html( 'catlinks' ) ?>

					<?php $this->html( 'dataAfterContent' ); ?>

				</div>
			</div>


			<div id="mw-navigation">
				<h2><?php $this->msg( 'navigation-heading' ) ?></h2>

				<form
					action="<?php $this->text( 'wgScript' ) ?>"
					role="search"
					class="mw-portlet"
					id="p-search"
				>
					<input type="hidden" name="title" value="<?php $this->text( 'searchtitle' ) ?>" />

					<h3><label for="searchInput"><?php $this->msg( 'search' ) ?></label></h3>

					<?php echo $this->makeSearchInput( array( "id" => "searchInput" ) ) ?>
					<?php echo $this->makeSearchButton( 'go' ) ?>

				</form>

				<?php

				$this->outputPortlet( array(
					'id' => 'p-personal',
					'headerMessage' => 'personaltools',
					'content' => $this->getPersonalTools(),
				) );

				$this->outputPortlet( array(
					'id' => 'p-namespaces',
					'headerMessage' => 'namespaces',
					'content' => $this->data['content_navigation']['namespaces'],
				) );
				$this->outputPortlet( array(
					'id' => 'p-variants',
					'headerMessage' => 'variants',
					'content' => $this->data['content_navigation']['variants'],
				) );
				$this->outputPortlet( array(
					'id' => 'p-views',
					'headerMessage' => 'views',
					'content' => $this->data['content_navigation']['views'],
				) );
				$this->outputPortlet( array(
					'id' => 'p-actions',
					'headerMessage' => 'actions',
					'content' => $this->data['content_navigation']['actions'],
				) );

				foreach ( $this->getSidebar() as $boxName => $box ) {
					$this->outputPortlet( $box );
				}

				?>
			</div>

			<div id="mw-footer">
				<?php foreach ( $this->getFooterLinks() as $category => $links ) { ?>
					<ul role="contentinfo">
						<?php foreach ( $links as $key ) { ?>
							<li><?php $this->html( $key ) ?></li>
						<?php } ?>
					</ul>
				<?php } ?>

				<ul role="contentinfo">
					<?php foreach ( $this->getFooterIcons( 'icononly' ) as $blockName => $footerIcons ) { ?>
						<li>
							<?php
							foreach ( $footerIcons as $icon ) {
								echo $this->getSkin()->makeFooterIcon( $icon );
							}
							?>
						</li>
					<?php } ?>
				</ul>
			</div>
		</div>

		<?php $this->printTrail() ?>
		</body></html>

		<?php
	}
}
