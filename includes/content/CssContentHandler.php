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
 */

namespace MediaWiki\Content;

use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Content\Transform\PreSaveTransformParams;
use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Title\Title;
use Wikimedia\Minify\CSSMin;

/**
 * Content handler for CSS pages.
 *
 * @since 1.21
 * @ingroup Content
 */
class CssContentHandler extends CodeContentHandler {

	/**
	 * @param string $modelId
	 */
	public function __construct( $modelId = CONTENT_MODEL_CSS ) {
		parent::__construct( $modelId, [ CONTENT_FORMAT_CSS ] );
	}

	/**
	 * @return class-string<CssContent>
	 */
	protected function getContentClass() {
		return CssContent::class;
	}

	/** @inheritDoc */
	public function supportsRedirects() {
		return true;
	}

	/**
	 * Create a redirect that is also valid CSS
	 *
	 * @param Title $destination
	 * @param string $text ignored
	 *
	 * @return CssContent
	 */
	public function makeRedirectContent( Title $destination, $text = '' ) {
		// The parameters are passed as a string so the / is not url-encoded by wfArrayToCgi
		$url = $destination->getFullURL( 'action=raw&ctype=text/css', false, PROTO_RELATIVE );
		$class = $this->getContentClass();

		return new $class( '/* #REDIRECT */@import ' . CSSMin::buildUrlValue( $url ) . ';' );
	}

	public function preSaveTransform(
		Content $content,
		PreSaveTransformParams $pstParams
	): Content {
		'@phan-var CssContent $content';

		// @todo Make pre-save transformation optional for script pages (T34858)
		$services = MediaWikiServices::getInstance();
		if ( !$services->getUserOptionsLookup()->getBoolOption( $pstParams->getUser(), 'pst-cssjs' ) ) {
			// Allow bot users to disable the pre-save transform for CSS/JS (T236828).
			$popts = clone $pstParams->getParserOptions();
			$popts->setPreSaveTransform( false );
		}

		$text = $content->getText();
		$pst = $services->getParserFactory()->getInstance()->preSaveTransform(
			$text,
			$pstParams->getPage(),
			$pstParams->getUser(),
			$pstParams->getParserOptions()
		);

		$class = $this->getContentClass();
		return new $class( $pst );
	}

	/**
	 * @inheritDoc
	 */
	protected function fillParserOutput(
		Content $content,
		ContentParseParams $cpoParams,
		ParserOutput &$output
	) {
		$textModelsToParse = MediaWikiServices::getInstance()->getMainConfig()
			->get( MainConfigNames::TextModelsToParse );
		'@phan-var CssContent $content';
		if ( in_array( $content->getModel(), $textModelsToParse ) ) {
			// parse just to get links etc into the database, HTML is replaced below.
			$output = MediaWikiServices::getInstance()->getParserFactory()->getInstance()
				->parse(
					$content->getText(),
					$cpoParams->getPage(),
					WikiPage::makeParserOptionsFromTitleAndModel(
						$cpoParams->getPage(),
						$content->getModel(),
						'canonical'
					),
					true,
					true,
					$cpoParams->getRevId()
				);
		}

		if ( $cpoParams->getGenerateHtml() ) {
			// Return CSS wrapped in a <pre> tag.
			$html = Html::element(
				'pre',
				[ 'class' => [ 'mw-code', 'mw-css' ], 'dir' => 'ltr' ],
				"\n" . $content->getText() . "\n"
			) . "\n";
		} else {
			$html = null;
		}

		$output->clearWrapperDivClass();
		$output->setRawText( $html );
		// Suppress the TOC (T307691)
		$output->setOutputFlag( ParserOutputFlags::NO_TOC );
		$output->setSections( [] );
	}
}
/** @deprecated class alias since 1.43 */
class_alias( CssContentHandler::class, 'CssContentHandler' );
