<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Content;

use MediaWiki\Config\Config;
use MediaWiki\Content\Renderer\ContentParseParams;
use MediaWiki\Content\Transform\PreSaveTransformParams;
use MediaWiki\Html\Html;
use MediaWiki\MainConfigNames;
use MediaWiki\Page\WikiPage;
use MediaWiki\Parser\ParserFactory;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Parser\ParserOutputFlags;
use MediaWiki\Title\Title;
use MediaWiki\User\Options\UserOptionsLookup;
use Wikimedia\Minify\CSSMin;

/**
 * Content handler for CSS pages.
 *
 * @since 1.21
 * @ingroup Content
 */
class CssContentHandler extends CodeContentHandler {

	private array $textModelsToParse;
	private ParserFactory $parserFactory;
	private UserOptionsLookup $userOptionsLookup;

	public function __construct(
		string $modelId,
		Config $config,
		ParserFactory $parserFactory,
		UserOptionsLookup $userOptionsLookup
	) {
		parent::__construct( $modelId, [ CONTENT_FORMAT_CSS ] );
		$this->textModelsToParse = $config->get( MainConfigNames::TextModelsToParse ) ?? [];
		$this->parserFactory = $parserFactory;
		$this->userOptionsLookup = $userOptionsLookup;
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
		if ( !$this->userOptionsLookup->getBoolOption( $pstParams->getUser(), 'pst-cssjs' ) ) {
			// Allow bot users to disable the pre-save transform for CSS/JS (T236828).
			$popts = clone $pstParams->getParserOptions();
			$popts->setPreSaveTransform( false );
		}

		$text = $content->getText();
		$pst = $this->parserFactory->getInstance()->preSaveTransform(
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
		'@phan-var CssContent $content';
		if ( in_array( $content->getModel(), $this->textModelsToParse ) ) {
			// parse just to get links etc into the database, HTML is replaced below.
			$output = $this->parserFactory->getInstance()
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
