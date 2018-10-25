<?php
/**
 * Output handler for the web installer.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
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
	 * @var string
	 */
	private $contents = '';

	/**
	 * Has the header (or short header) been output?
	 * @var bool
	 */
	private $headerDone = false;

	/**
	 * @var string
	 */
	public $redirectTarget;

	/**
	 * Does the current page need to allow being used as a frame?
	 * If not, X-Frame-Options will be output to forbid it.
	 *
	 * @var bool
	 */
	public $allowFrames = false;

	/**
	 * Whether to use the limited header (used during CC license callbacks)
	 * @var bool
	 */
	private $useShortHeader = false;

	/**
	 * @param WebInstaller $parent
	 */
	public function __construct( WebInstaller $parent ) {
		$this->parent = $parent;
	}

	/**
	 * @param string $html
	 */
	public function addHTML( $html ) {
		$this->contents .= $html;
		$this->flush();
	}

	/**
	 * @param string $text
	 * @deprecated since 1.32; use addWikiTextAsInterface instead
	 */
	public function addWikiText( $text ) {
		wfDeprecated( __METHOD__, '1.32' );
		$this->addWikiTextAsInterface( $text );
	}

	/**
	 * @param string $text
	 * @since 1.32
	 */
	public function addWikiTextAsInterface( $text ) {
		$this->addHTML( $this->parent->parse( $text ) );
	}

	/**
	 * @param string $html
	 */
	public function addHTMLNoFlush( $html ) {
		$this->contents .= $html;
	}

	/**
	 * @param string $url
	 *
	 * @throws MWException
	 */
	public function redirect( $url ) {
		if ( $this->headerDone ) {
			throw new MWException( __METHOD__ . ' called after sending headers' );
		}
		$this->redirectTarget = $url;
	}

	public function output() {
		$this->flush();

		if ( !$this->redirectTarget ) {
			$this->outputFooter();
		}
	}

	/**
	 * Get the stylesheet of the MediaWiki skin.
	 *
	 * @return string
	 */
	public function getCSS() {
		global $wgStyleDirectory;

		$moduleNames = [
			// Based on Skin::getDefaultModules
			'mediawiki.legacy.shared',
			// Based on Vector::setupSkinUserCss
			'mediawiki.skinning.interface',
		];

		$resourceLoader = new ResourceLoader();

		if ( file_exists( "$wgStyleDirectory/Vector/skin.json" ) ) {
			// Force loading Vector skin if available as a fallback skin
			// for whatever ResourceLoader wants to have as the default.
			$registry = new ExtensionRegistry();
			$data = $registry->readFromQueue( [
				"$wgStyleDirectory/Vector/skin.json" => 1,
			] );
			if ( isset( $data['globals']['wgResourceModules'] ) ) {
				$resourceLoader->register( $data['globals']['wgResourceModules'] );
			}

			$moduleNames[] = 'skins.vector.styles';
		}

		$moduleNames[] = 'mediawiki.legacy.config';

		$rlContext = new ResourceLoaderContext( $resourceLoader, new FauxRequest( [
				'debug' => 'true',
				'lang' => $this->getLanguageCode(),
				'only' => 'styles',
		] ) );

		$styles = [];
		foreach ( $moduleNames as $moduleName ) {
			/** @var ResourceLoaderFileModule $module */
			$module = $resourceLoader->getModule( $moduleName );
			if ( !$module ) {
				// T98043: Don't fatal, but it won't look as pretty.
				continue;
			}

			// Based on: ResourceLoaderFileModule::getStyles (without the DB query)
			$styles = array_merge( $styles, ResourceLoader::makeCombinedStyles(
				$module->readStyleFiles(
					$module->getStyleFiles( $rlContext ),
					$module->getFlip( $rlContext ),
					$rlContext
			) ) );
		}

		return implode( "\n", $styles );
	}

	/**
	 * "<link>" to index.php?css=1 for the "<head>"
	 *
	 * @return string
	 */
	private function getCssUrl() {
		return Html::linkedStyle( $_SERVER['PHP_SELF'] . '?css=1' );
	}

	public function useShortHeader( $use = true ) {
		$this->useShortHeader = $use;
	}

	public function allowFrames( $allow = true ) {
		$this->allowFrames = $allow;
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

	/**
	 * @return string
	 */
	public function getDir() {
		global $wgLang;

		return is_object( $wgLang ) ? $wgLang->getDir() : 'ltr';
	}

	/**
	 * @return string
	 */
	public function getLanguageCode() {
		global $wgLang;

		return is_object( $wgLang ) ? $wgLang->getCode() : 'en';
	}

	/**
	 * @return string[]
	 */
	public function getHeadAttribs() {
		return [
			'dir' => $this->getDir(),
			'lang' => LanguageCode::bcp47( $this->getLanguageCode() ),
		];
	}

	/**
	 * Get whether the header has been output
	 *
	 * @return bool
	 */
	public function headerDone() {
		return $this->headerDone;
	}

	public function outputHeader() {
		$this->headerDone = true;
		$this->parent->request->response()->header( 'Content-Type: text/html; charset=utf-8' );

		if ( !$this->allowFrames ) {
			$this->parent->request->response()->header( 'X-Frame-Options: DENY' );
		}

		if ( $this->redirectTarget ) {
			$this->parent->request->response()->header( 'Location: ' . $this->redirectTarget );

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
	<?php echo $this->getCssUrl() . "\n"; ?>
	<?php echo $this->getJQuery() . "\n"; ?>
	<?php echo Html::linkedScript( 'config.js' ) . "\n"; ?>
</head>

<?php echo Html::openElement( 'body', [ 'class' => $this->getDir() ] ) . "\n"; ?>
<div id="mw-page-base"></div>
<div id="mw-head-base"></div>
<div id="content" class="mw-body">
<div id="bodyContent" class="mw-body-content">

<h1><?php $this->outputTitle(); ?></h1>
<?php
	}

	public function outputFooter() {
		if ( $this->useShortHeader ) {
			echo Html::closeElement( 'body' ) . Html::closeElement( 'html' );

			return;
		}
?>

</div></div>

<div id="mw-panel">
	<div class="portal" id="p-logo">
		<a style="background-image: url(images/installer-logo.png);"
			href="https://www.mediawiki.org/"
			title="Main Page"></a>
	</div>
<?php
	$message = wfMessage( 'config-sidebar' )->plain();
	foreach ( explode( '----', $message ) as $section ) {
		echo '<div class="portal"><div class="body">';
		echo $this->parent->parse( $section, true );
		echo '</div></div>';
	}
?>
</div>

<?php
		echo Html::closeElement( 'body' ) . Html::closeElement( 'html' );
	}

	public function outputShortHeader() {
?>
<?php echo Html::htmlHeader( $this->getHeadAttribs() ); ?>
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<meta name="robots" content="noindex, nofollow" />
	<title><?php $this->outputTitle(); ?></title>
	<?php echo $this->getCssUrl() . "\n"; ?>
	<?php echo $this->getJQuery(); ?>
	<?php echo Html::linkedScript( 'config.js' ); ?>
</head>

<body style="background-image: none">
<?php
	}

	public function outputTitle() {
		global $wgVersion;
		echo wfMessage( 'config-title', $wgVersion )->escaped();
	}

	/**
	 * @return string
	 */
	public function getJQuery() {
		return Html::linkedScript( "../resources/lib/jquery/jquery.js" );
	}

}
