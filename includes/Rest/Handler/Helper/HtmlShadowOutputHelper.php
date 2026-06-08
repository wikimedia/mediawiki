<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */
namespace MediaWiki\Rest\Handler\Helper;

use MediaWiki\Page\PageIdentity;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Rest\LocalizedHttpException;
use MediaWiki\Rest\ResponseInterface;
use MediaWiki\ShadowPage\ShadowPageLoader;
use MediaWiki\Title\TitleFormatter;
use Wikimedia\Message\MessageValue;
use Wikimedia\Parsoid\Core\HtmlPageBundle;
use Wikimedia\Parsoid\Utils\ContentUtils;
use Wikimedia\Parsoid\Utils\DOMUtils;

/**
 * @since 1.47
 * @unstable
 */
class HtmlShadowOutputHelper implements HtmlOutputHelper {

	private PageIdentity $page;
	private ?ParserOutput $parserOutput = null;

	public function __construct(
		private ShadowPageLoader $shadowPageLoader,
		private TitleFormatter $titleFormatter,
		private ParserOptions $parserOptions,
		PageIdentity $page
	) {
		$this->page = $page;
	}

	/**
	 * @inheritDoc
	 */
	public function getHtml(): ParserOutput {
		$out = $this->maybeGetParserOutput();
		if ( !$out ) {
			// This can be reached if a provider returns null from getView()
			// despite previously returning true from hasPreloadContent().
			// No known provider does this, but it is allowed by the interface.
			// Act as if PageContentHelper::hasContent() returned false.
			throw new LocalizedHttpException(
				MessageValue::new( 'rest-nonexistent-title' )
					->plaintextParams( $this->titleFormatter->getPrefixedDBkey( $this->page ) ),
				404
			);
		}
		return $out;
	}

	private function maybeGetParserOutput(): ?ParserOutput {
		if ( $this->parserOutput === null ) {
			$this->parserOutput = $this->shadowPageLoader->get( $this->page )
				?->getView( $this->parserOptions )->getParserOutput();
		}
		return $this->parserOutput;
	}

	/**
	 * @inheritDoc
	 *
	 * Builds the full document directly as a page bundle: the body fragment
	 * from the shadow page wrapped in a document with an injected
	 * content-language <meta> in the <head>. (getHtml()'s ParserOutput stays
	 * body-only, per the ContentHolder invariant.)
	 */
	public function getPageBundle(): HtmlPageBundle {
		// T429391: This is creating full-document output (and a PageBundle)
		// without being Parsoid HTML.  This should probably be replaced
		// by a call to
		// PageBundleParserOutputConverter::htmlPageBundleFromParserOutput()
		$output = $this->getHtml();
		$language = $output->getLanguage()->toBcp47Code();
		$messageDom = DOMUtils::parseHTML( $output->getContentHolderText() );
		DOMUtils::appendToHead( $messageDom, 'meta', [
			'http-equiv' => 'content-language',
			'content' => $language,
		] );
		return new HtmlPageBundle(
			html: ContentUtils::toXML( $messageDom ),
			headers: [ 'content-language' => $language ],
		);
	}

	/**
	 * @inheritDoc
	 */
	public function getETag( string $suffix = '' ): ?string {
		$output = $this->maybeGetParserOutput();
		if ( $output ) {
			// Hash the full served document.
			return '"message/' . sha1( $this->getPageBundle()->html ) . '/' . $suffix . '"';
		}
		return null;
	}

	/**
	 * @inheritDoc
	 *
	 * @note This is guaranteed to always return NULL since
	 *   proper system messages (with no DB entry) have no
	 *   revision, so they should have no last modified time.
	 */
	public function getLastModified(): ?string {
		return null;
	}

	/**
	 * @inheritDoc
	 */
	public static function getParamSettings(): array {
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public function setVariantConversionLanguage(
		$targetLanguage,
		$sourceLanguage = null
	): void {
		// TODO: Set language in the response headers.
	}

	public function putHeaders(
		ResponseInterface $response,
		bool $forHtml = true
	): void {
		// TODO: Set language in the response headers.
	}

}
