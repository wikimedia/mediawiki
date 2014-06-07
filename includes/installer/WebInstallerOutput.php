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
	 * @var String
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
	 */
	public function addWikiText( $text ) {
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
		$this->outputFooter();
	}

	/**
	 * Get the raw vector CSS, flipping if needed
	 *
	 * @todo Possibly get rid of this function and use ResourceLoader in the manner it was
	 *   designed to be used in, rather than just grabbing a list of filenames from it,
	 *   and not properly handling such details as media types in module definitions.
	 *
	 * @param string $dir 'ltr' or 'rtl'
	 *
	 * @return String
	 */
	public function getCSS( $dir ) {
		// All CSS files these modules reference will be concatenated in sequence
		// and loaded as one file.
		$moduleNames = array(
			'mediawiki.legacy.shared',
			'mediawiki.skinning.interface',
			'skins.vector.styles',
			'mediawiki.legacy.config',
		);

		$prepend = '';
		$css = '';

		$resourceLoader = new ResourceLoader();
		foreach ( $moduleNames as $moduleName ) {
			/** @var ResourceLoaderFileModule $module */
			$module = $resourceLoader->getModule( $moduleName );
			$cssFileNames = $module->getAllStyleFiles();

			wfSuppressWarnings();
			foreach ( $cssFileNames as $cssFileName ) {
				if ( !file_exists( $cssFileName ) ) {
					$prepend .= ResourceLoader::makeComment( "Unable to find $cssFileName." );
					continue;
				}

				if ( !is_readable( $cssFileName ) ) {
					$prepend .= ResourceLoader::makeComment( "Unable to read $cssFileName. " .
						"Please check file permissions." );
					continue;
				}

				try {

					if ( preg_match( '/\.less$/', $cssFileName ) ) {
						// Run the LESS compiler for *.less files (bug 55589)
						$compiler = ResourceLoader::getLessCompiler();
						$cssFileContents = $compiler->compileFile( $cssFileName );
					} else {
						// Regular CSS file
						$cssFileContents = file_get_contents( $cssFileName );
					}

					if ( $cssFileContents ) {
						// Rewrite URLs, though don't bother embedding images. While static image
						// files may be cached, CSS returned by this function is definitely not.
						$cssDirName = dirname( $cssFileName );
						$css .= CSSMin::remap(
							/* source */ $cssFileContents,
							/* local */ $cssDirName,
							/* remote */ '..' . str_replace(
								array( $GLOBALS['IP'], DIRECTORY_SEPARATOR ),
								array( '', '/' ),
								$cssDirName
							),
							/* embedData */ false
						);
					} else {
						$prepend .= ResourceLoader::makeComment( "Unable to read $cssFileName." );
					}
				} catch ( Exception $e ) {
					$prepend .= ResourceLoader::formatException( $e );
				}

				$css .= "\n";
			}
			wfRestoreWarnings();
		}

		$css = $prepend . $css;

		if ( $dir == 'rtl' ) {
			$css = CSSJanus::transform( $css, true );
		}

		return $css;
	}

	/**
	 * "<link>" to index.php?css=foobar for the "<head>"
	 *
	 * @return String
	 */
	private function getCssUrl() {
		return Html::linkedStyle( $_SERVER['PHP_SELF'] . '?css=' . $this->getDir() );
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
		return array(
			'dir' => $this->getDir(),
			'lang' => $this->getLanguageCode(),
		);
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
	<?php echo Html::linkedScript( '../skins/common/config.js' ) . "\n"; ?>
</head>

<?php echo Html::openElement( 'body', array( 'class' => $this->getDir() ) ) . "\n"; ?>
<div id="mw-page-base"></div>
<div id="mw-head-base"></div>
<div id="content">
<div id="bodyContent">

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
	  <a style="background-image: url(../skins/common/images/mediawiki.png);"
		href="https://www.mediawiki.org/"
		title="Main Page"></a>
	</div>
	<div class="portal"><div class="body">
<?php
	echo $this->parent->parse( wfMessage( 'config-sidebar' )->plain(), true );
?>
	</div></div>
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
	<?php echo Html::linkedScript( '../skins/common/config.js' ); ?>
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
