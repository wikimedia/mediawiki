<?php

/**
 * Skin template for the fallback skin.
 *
 * The structure is copied from the example skin (mediawiki/skins/Example).
 *
 * @since 1.24
 * @file
 */

/**
 * BaseTemplate class for the fallback skin
 */
class SkinFallbackTemplate extends BaseTemplate {
	/**
	 * @return array
	 */
	private function findInstalledSkins() {
		$styleDirectory = $this->config->get( 'StyleDirectory' ); // @todo we should inject this directly?
		// Get all subdirectories which might contains skins
		$possibleSkins = scandir( $styleDirectory );
		$possibleSkins = array_filter( $possibleSkins, function ( $maybeDir ) use ( $styleDirectory ) {
			return $maybeDir !== '.' && $maybeDir !== '..' && is_dir( "$styleDirectory/$maybeDir" );
		} );

		// Only keep the ones that contain a .php file with the same name inside
		$possibleSkins = array_filter( $possibleSkins, function ( $skinDir ) use ( $styleDirectory ) {
			return is_file( "$styleDirectory/$skinDir/$skinDir.php" );
		} );

		return $possibleSkins;
	}

	/**
	 * Inform the user why they are seeing this skin.
	 *
	 * @return string
	 */
	private function buildHelpfulInformationMessage() {
		$defaultSkin = $this->config->get( 'DefaultSkin' );
		$installedSkins = $this->findInstalledSkins();
		$enabledSkins = SkinFactory::getDefaultInstance()->getSkinNames();
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
				$defaultSkin,
				implode( "\n", $skinsInstalledText ),
				implode( "\n", $skinsInstalledSnippet )
			)->parseAsBlock();
		} else {
			return $this->getMsg( 'default-skin-not-found-no-skins' )->params(
				$defaultSkin
			)->parseAsBlock();
		}
	}

	/**
	 * Outputs the entire contents of the page. No navigation (other than search box), just the big
	 * warning message and page content.
	 */
	public function execute() {
		$this->html( 'headelement' ) ?>

		<div class="warningbox">
			<?php echo $this->buildHelpfulInformationMessage() ?>
		</div>

		<form action="<?php $this->text( 'wgScript' ) ?>">
			<input type="hidden" name="title" value="<?php $this->text( 'searchtitle' ) ?>" />
			<h3><label for="searchInput"><?php $this->msg( 'search' ) ?></label></h3>
			<?php echo $this->makeSearchInput( array( "id" => "searchInput" ) ) ?>
			<?php echo $this->makeSearchButton( 'go' ) ?>
		</form>

		<div class="mw-body" role="main">
			<h1 class="firstHeading">
				<span dir="auto"><?php $this->html( 'title' ) ?></span>
			</h1>

			<div class="mw-body-content">
				<?php $this->html( 'bodytext' ) ?>
				<?php $this->html( 'catlinks' ) ?>
			</div>
		</div>

		<?php $this->printTrail() ?>
		</body></html>

	<?php
	}
}
