<?php

/**
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
 * @ingroup Installer
 */

namespace MediaWiki\Installer;

use LogicException;
use MediaWiki\Html\Html;
use MediaWiki\Language\Language;
use MediaWiki\MediaWikiServices;
use MediaWiki\Request\FauxRequest;
use MediaWiki\ResourceLoader as RL;
use MediaWiki\ResourceLoader\ResourceLoader;

/**
 * Output class modelled on OutputPage.
 *
 * I've opted to use a distinct class rather than derive from OutputPage here in
 * the interests of separation of concerns: if we used a subclass, there would be
 * quite a lot of things you could do in OutputPage that would break the installer,
 * that wouldn't be immediately obvious.
 *
 * @ingroup Installer
 * @since 1.17
 * @internal
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
	 * Has the header been output?
	 * @var bool
	 */
	private $headerDone = false;

	/**
	 * @var string
	 */
	public $redirectTarget;

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
	 */
	public function redirect( $url ) {
		if ( $this->headerDone ) {
			throw new LogicException( __METHOD__ . ' called after sending headers' );
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
		$resourceLoader = MediaWikiServices::getInstance()->getResourceLoader();

		$rlContext = new RL\Context( $resourceLoader, new FauxRequest( [
			'debug' => 'true',
			'lang' => $this->getLanguage()->getCode(),
			'only' => 'styles',
		] ) );

		$module = new RL\SkinModule( [
			'features' => [
				'elements',
				'interface-message-box'
			],
			'styles' => [
				'mw-config/config.css',
			],
		] );
		$module->setConfig( $resourceLoader->getConfig() );

		// Based on MediaWiki\ResourceLoader\FileModule::getStyles, without the DB query
		$styles = ResourceLoader::makeCombinedStyles(
			$module->readStyleFiles(
				$module->getStyleFiles( $rlContext ),
				$rlContext
		) );

		return implode( "\n", $styles );
	}

	/**
	 * "<link>" to index.php?css=1 for the "<head>"
	 *
	 * @return string
	 */
	private function getCssUrl() {
		return Html::linkedStyle( $this->parent->getUrl( [ 'css' => 1 ] ) );
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
	 * @since 1.33
	 * @return Language
	 */
	private function getLanguage() {
		global $wgLang;

		return is_object( $wgLang ) ? $wgLang
			: MediaWikiServices::getInstance()->getLanguageFactory()->getLanguage( 'en' );
	}

	/**
	 * @return string[]
	 */
	public function getHeadAttribs() {
		return [
			'dir' => $this->getLanguage()->getDir(),
			'lang' => $this->getLanguage()->getHtmlCode(),
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
		$this->parent->request->response()->header( 'X-Frame-Options: DENY' );

		$cspPolicy = "default-src 'self'; style-src 'self' 'unsafe-inline'; object-src 'none';" .
			" script-src 'self' 'nonce-" . $this->getCSPNonce() . "';" .
			" img-src 'self'; frame-src 'self'; base-uri 'none'";

		$this->parent->request->response()->header( 'Content-Security-Policy: ' . $cspPolicy );

		if ( $this->redirectTarget ) {
			$this->parent->request->response()->header( 'Location: ' . $this->redirectTarget );

			return;
		}
?>
<?php echo Html::htmlHeader( $this->getHeadAttribs() ); ?>

<head>
	<meta name="robots" content="noindex, nofollow" />
	<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
	<title><?php $this->outputTitle(); ?></title>
	<?php echo $this->getCodex() . "\n"; ?>
	<?php echo $this->getCssUrl() . "\n"; ?>
	<?php echo $this->getJQuery() . "\n"; ?>
	<?php echo Html::linkedScript( 'config.js' ) . "\n"; ?>
</head>

<?php echo Html::openElement( 'body', [ 'class' => $this->getLanguage()->getDir() ] ) . "\n"; ?>
<div id="mw-page-base"></div>
<div id="mw-head-base"></div>
<div id="content" class="mw-body" role="main">
<div id="bodyContent" class="mw-body-content">

<h1><?php $this->outputTitle(); ?></h1>
<?php
	}

	public function outputFooter() {
?>

</div></div>

<aside id="mw-panel">
	<div class="portal" id="p-logo">
		<a href="https://www.mediawiki.org/" title="Main Page"></a>
	</div>
<?php
		// @phpcs:disable Generic.WhiteSpace.ScopeIndent.IncorrectExact
		$message = wfMessage( 'config-sidebar' )->plain();
		// Section 1: External links
		// @todo FIXME: Migrate to plain link label messages (T227297).
		foreach ( explode( '----', $message ) as $section ) {
			echo '<div class="portal"><div class="body">';
			echo $this->parent->parse( $section, true );
			echo '</div></div>';
		}
		// Section 2: Installer pages
		echo '<div class="portal"><div class="body"><ul>';
		foreach ( [
			'config-sidebar-relnotes' => 'ReleaseNotes',
			'config-sidebar-license' => 'Copying',
			'config-sidebar-upgrade' => 'UpgradeDoc',
		] as $msgKey => $pageName ) {
			echo $this->parent->makeLinkItem(
				$this->parent->getDocUrl( $pageName ),
				wfMessage( $msgKey )->text()
			);
		}
		echo '</ul></div></div>';
		// @phpcs:enable
?>
</aside>

<?php
		echo Html::closeElement( 'body' ) . Html::closeElement( 'html' );
	}

	public function outputTitle() {
		echo wfMessage( 'config-title', MW_VERSION )->escaped();
	}

	/**
	 * @return string
	 */
	public function getJQuery() {
		return Html::linkedScript( "../resources/lib/jquery/jquery.js" );
	}

	/**
	 * @return string
	 */
	public function getCodex() {
		return Html::linkedStyle( "../resources/lib/codex/codex.style.css" );
	}

	/**
	 * Get the nonce for use with inline scripts
	 *
	 * @since 1.45
	 * @return string
	 */
	public function getCSPNonce() {
		static $nonce;
		if ( $nonce === null ) {
			// Spec says at least 16 bytes. Do 18 so it encodes evenly in base64
			$nonce = base64_encode( random_bytes( 18 ) );
		}
		return $nonce;
	}
}
