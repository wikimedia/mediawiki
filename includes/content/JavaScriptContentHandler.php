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

use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Content\Transform\PreSaveTransformParams;
use MediaWiki\MainConfigNames;
use MediaWiki\MediaWikiServices;

/**
 * Content handler for JavaScript pages.
 *
 * @todo Create a ScriptContentHandler base class, do highlighting stuff there?
 *
 * @since 1.21
 * @ingroup Content
 */
class JavaScriptContentHandler extends CodeContentHandler {

	/**
	 * @param string $modelId
	 */
	public function __construct( $modelId = CONTENT_MODEL_JAVASCRIPT ) {
		parent::__construct( $modelId, [ CONTENT_FORMAT_JAVASCRIPT ] );
	}

	/**
	 * @return string
	 */
	protected function getContentClass() {
		return JavaScriptContent::class;
	}

	public function supportsRedirects() {
		return true;
	}

	/**
	 * Create a redirect that is also valid JavaScript
	 *
	 * @param Title $destination
	 * @param string $text ignored
	 * @return JavaScriptContent
	 */
	public function makeRedirectContent( Title $destination, $text = '' ) {
		// The parameters are passed as a string so the / is not url-encoded by wfArrayToCgi
		$url = $destination->getFullURL( 'action=raw&ctype=text/javascript', false, PROTO_RELATIVE );
		$class = $this->getContentClass();
		return new $class( '/* #REDIRECT */' . Xml::encodeJsCall( 'mw.loader.load', [ $url ] ) );
	}

	public function preSaveTransform(
		Content $content,
		PreSaveTransformParams $pstParams
	): Content {
		$shouldCallDeprecatedMethod = $this->shouldCallDeprecatedContentTransformMethod(
			$content,
			$pstParams
		);

		if ( $shouldCallDeprecatedMethod ) {
			return $this->callDeprecatedContentPST(
				$content,
				$pstParams
			);
		}

		'@phan-var JavascriptContent $content';

		$parserOptions = $pstParams->getParserOptions();
		// @todo Make pre-save transformation optional for script pages (T34858)
		$services = MediaWikiServices::getInstance();
		if ( !$services->getUserOptionsLookup()->getBoolOption( $pstParams->getUser(), 'pst-cssjs' ) ) {
			// Allow bot users to disable the pre-save transform for CSS/JS (T236828).
			$parserOptions = clone $parserOptions;
			$parserOptions->setPreSaveTransform( false );
		}

		$text = $content->getText();
		$pst = $services->getParserFactory()->getInstance()->preSaveTransform(
			$text,
			$pstParams->getPage(),
			$pstParams->getUser(),
			$parserOptions
		);

		$contentClass = $this->getContentClass();
		return new $contentClass( $pst );
	}

	/**
	 * Fills the provided ParserOutput object with information derived from the content.
	 * Unless $cpo->getGenerateHtml was false, this includes an HTML representation of the content.
	 *
	 * For content models listed in $wgTextModelsToParse, this method will call the MediaWiki
	 * wikitext parser on the text to extract any (wikitext) links, magic words, etc.
	 *
	 * Subclasses may override this to provide custom content processing..
	 *
	 * @stable to override
	 *
	 * @since 1.38
	 * @param Content $content
	 * @param ContentParseParams $cpoParams
	 * @param ParserOutput &$output The output object to fill (reference).
	 */
	protected function fillParserOutput(
		Content $content,
		ContentParseParams $cpoParams,
		ParserOutput &$output
	) {
		$textModelsToParse = MediaWikiServices::getInstance()->getMainConfig()->get(
			MainConfigNames::TextModelsToParse );
		'@phan-var JavaScriptContent $content';
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
			// Return JavaScript wrapped in a <pre> tag.
			$html = Html::element(
				'pre',
				[ 'class' => 'mw-code mw-js', 'dir' => 'ltr' ],
				"\n" . $content->getText() . "\n"
			) . "\n";
		} else {
			$html = null;
		}

		$output->clearWrapperDivClass();
		$output->setText( $html );
	}
}
