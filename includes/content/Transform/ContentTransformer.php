<?php
namespace MediaWiki\Content\Transform;

use Content;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Page\PageReference;
use MediaWiki\User\UserIdentity;
use ParserOptions;

/**
 * A service to transform content.
 *
 * @since 1.37
 */
class ContentTransformer {
	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/**
	 * @param IContentHandlerFactory $contentHandlerFactory
	 */
	public function __construct( IContentHandlerFactory $contentHandlerFactory ) {
		$this->contentHandlerFactory = $contentHandlerFactory;
	}

	/**
	 * Returns a Content object with pre-save transformations applied (or $content
	 * if no transformations apply).
	 *
	 * @param Content $content
	 * @param PageReference $page
	 * @param UserIdentity $user
	 * @param ParserOptions $parserOptions
	 *
	 * @return Content
	 */
	public function preSaveTransform(
		Content $content,
		PageReference $page,
		UserIdentity $user,
		ParserOptions $parserOptions
	): Content {
		$contentHandler = $this->contentHandlerFactory->getContentHandler( $content->getModel() );
		$pstParams = new PreSaveTransformParamsValue( $page, $user, $parserOptions );

		return $contentHandler->preSaveTransform( $content, $pstParams );
	}
}
