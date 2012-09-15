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
	 * Constructor.
	 *
	 * @param $parent WebInstaller
	 */
	public function __construct( WebInstaller $parent ) {
		$this->parent = $parent;
	}

	public function addHTML( $html ) {
		$this->contents .= $html;
		$this->flush();
	}

	public function addWikiText( $text ) {
		$this->addHTML( $this->parent->parse( $text ) );
	}

	public function addHTMLNoFlush( $html ) {
		$this->contents .= $html;
	}

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
	 * @param $dir String 'ltr' or 'rtl'
	 * @return String
	 */
	public function getCSS( $dir ) {
		$skinDir = dirname( dirname( __DIR__ ) ) . '/skins';

		// All these files will be concatenated in sequence and loaded
		// as one file.
		// The string 'images/' in the files' contents will be replaced
		// by '../skins/$skinName/images/', where $skinName is what appears
		// before the last '/' in each of the strings.
		$cssFileNames = array(

			// Basically the "skins.vector" ResourceLoader module styles
			'common/shared.css',
			'common/commonElements.css',
			'common/commonContent.css',
			'common/commonInterface.css',
			'vector/screen.css',

			// mw-config specific
			'common/config.css',
		);

		$css = '';

		wfSuppressWarnings();
		foreach ( $cssFileNames as $cssFileName ) {
			$fullCssFileName = "$skinDir/$cssFileName";
			$cssFileContents = file_get_contents( $fullCssFileName );
			if ( $cssFileContents ) {
				preg_match( "/^(\w+)\//", $cssFileName, $match );
				$skinName = $match[1];
				$css .= str_replace( 'images/', "../skins/$skinName/images/", $cssFileContents );
			} else {
				$css .= "/** Your webserver cannot read $fullCssFileName. Please check file permissions. */";
			}

			$css .= "\n";
		}
		wfRestoreWarnings();

		if( $dir == 'rtl' ) {
			$css = CSSJanus::transform( $css, true );
		}

		return $css;
	}

	/**
	 * "<link>" to index.php?css=foobar for the "<head>"
	 * @return String
	 */
	private function getCssUrl( ) {
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
	 * @return array
	 */
	public function getHeadAttribs() {
		return array(
			'dir' => $this->getDir(),
			'lang' => $this->getLanguageCode(),
		);
	}

	/**
	 * Get whether the header has been output
	 * @return bool
	 */
	public function headerDone() {
		return $this->headerDone;
	}

	public function outputHeader() {
		$this->headerDone = true;
		$dbTypes = $this->parent->getDBTypes();

		$this->parent->request->response()->header( 'Content-Type: text/html; charset=utf-8' );
		if (!$this->allowFrames) {
			$this->parent->request->response()->header( 'X-Frame-Options: DENY' );
		}
		if ( $this->redirectTarget ) {
			$this->parent->request->response()->header( 'Location: '.$this->redirectTarget );
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
	<?php echo Html::inlineScript(  "var dbTypes = " . Xml::encodeJsVar( $dbTypes ) ) . "\n"; ?>
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
?>
</body></html>
<?php
			return;
		}
?>

</div></div>


<div id="mw-panel">
	<div class="portal" id="p-logo">
	  <a style="background-image: url(../skins/common/images/mediawiki.png);"
		href="http://www.mediawiki.org/"
		title="Main Page"></a>
	</div>
	<div class="portal"><div class="body">
<?php
	echo $this->parent->parse( wfMessage( 'config-sidebar' )->plain(), true );
?>
	</div></div>
</div>

</body>
</html>
<?php
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

	public function getJQuery() {
		return Html::linkedScript( "../resources/jquery/jquery.js" );
	}
}
