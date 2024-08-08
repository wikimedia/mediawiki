<?php
namespace MediaWiki\Content\Renderer;

use MediaWiki\Content\Content;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Page\PageReference;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Revision\RevisionRecord;
use ParserOptions;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * A service to render content.
 *
 * @since 1.38
 */
class ContentRenderer {
	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	private GlobalIdGenerator $globalIdGenerator;

	/**
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param GlobalIdGenerator $globalIdGenerator
	 */
	public function __construct(
		IContentHandlerFactory $contentHandlerFactory,
		GlobalIdGenerator $globalIdGenerator
	) {
		$this->contentHandlerFactory = $contentHandlerFactory;
		$this->globalIdGenerator = $globalIdGenerator;
	}

	/**
	 * Returns a ParserOutput object containing information derived from this content.
	 *
	 * @param Content $content
	 * @param PageReference $page
	 * @param RevisionRecord|null $revision
	 * @param ParserOptions|null $parserOptions
	 * @param bool $generateHtml
	 *
	 * @return ParserOutput
	 * @note Passing an integer as $rev was deprecated in MW 1.42
	 */
	public function getParserOutput(
		Content $content,
		PageReference $page,
		$revision = null,
		?ParserOptions $parserOptions = null,
		bool $generateHtml = true
	): ParserOutput {
		$revId = null;
		$revTimestamp = null;
		if ( is_int( $revision ) ) {
			wfDeprecated( __METHOD__ . ' with integer revision id', '1.42' );
			$revId = $revision;
		} elseif ( $revision !== null ) {
			$revId = $revision->getId();
			$revTimestamp = $revision->getTimestamp();
		}
		$cacheTime = wfTimestampNow();
		$contentHandler = $this->contentHandlerFactory->getContentHandler( $content->getModel() );
		$cpoParams = new ContentParseParams( $page, $revId, $parserOptions, $generateHtml );

		$parserOutput = $contentHandler->getParserOutput( $content, $cpoParams );
		// Set the cache parameters, if not previously set.
		//
		// It is expected that this will be where most are set for the first
		// time, but a ContentHandler can (for example) use a content-based
		// hash for the render id by setting it inside
		// ContentHandler::getParserOutput(); any such custom render id
		// will not be overwritten here.  Similarly, a ContentHandler can
		// continue to use the semi-documented feature of ::setCacheTime(-1)
		// to indicate "not cacheable", and that will not be overwritten
		// either.
		if ( !$parserOutput->hasCacheTime() ) {
			$parserOutput->setCacheTime( $cacheTime );
		}
		if ( $parserOutput->getRenderId() === null ) {
			$parserOutput->setRenderId( $this->globalIdGenerator->newUUIDv1() );
		}
		// Revision ID and Revision Timestamp are set here so that we don't
		// have to load the revision row on view.
		if ( $parserOutput->getCacheRevisionId() === null && $revId !== null ) {
			$parserOutput->setCacheRevisionId( $revId );
		}
		if ( $parserOutput->getRevisionTimestamp() === null && $revTimestamp !== null ) {
			$parserOutput->setRevisionTimestamp( $revTimestamp );
		}
		return $parserOutput;
	}
}
