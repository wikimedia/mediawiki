<?php

namespace MediaWiki\Edit;

use MediaWiki\Content\IContentHandlerFactory;
use Wikimedia\ObjectCache\BagOStuff;
use Wikimedia\Parsoid\Core\HtmlPageBundle;

/**
 * @internal
 * @since 1.39
 */
class SimpleParsoidOutputStash implements ParsoidOutputStash {

	/** @var BagOStuff */
	private $bagOfStuff;

	/** @var int */
	private $duration;

	/** @var IContentHandlerFactory */
	private $contentHandlerFactory;

	/**
	 * @param IContentHandlerFactory $contentHandlerFactory
	 * @param BagOStuff $bagOfStuff storage backend
	 * @param int $duration cache duration in seconds
	 */
	public function __construct( IContentHandlerFactory $contentHandlerFactory, BagOStuff $bagOfStuff, int $duration ) {
		$this->bagOfStuff = $bagOfStuff;
		$this->duration = $duration;
		$this->contentHandlerFactory = $contentHandlerFactory;
	}

	private function makeCacheKey( ParsoidRenderID $renderId ): string {
		return $this->bagOfStuff->makeKey( 'ParsoidOutputStash', $renderId->getKey() );
	}

	/**
	 * Before we stash, we serialize & encode into JSON the relevant
	 * parts of the data we need to construct a page bundle in the future.
	 *
	 * @param ParsoidRenderID $renderId Combination of revision ID and revision's time ID
	 * @param SelserContext $selserContext
	 *
	 * @return bool
	 */
	public function set( ParsoidRenderID $renderId, SelserContext $selserContext ): bool {
		$jsonic = $this->selserContextToJsonArray( $selserContext );

		$key = $this->makeCacheKey( $renderId );
		return $this->bagOfStuff->set( $key, $jsonic, $this->duration );
	}

	/**
	 * This will decode the JSON data and create a page bundle from it
	 * if we have something in the stash that matches a given rendering or
	 * will just return an empty array if no entry in the stash.
	 *
	 * @param ParsoidRenderID $renderId
	 *
	 * @return SelserContext|null
	 */
	public function get( ParsoidRenderID $renderId ): ?SelserContext {
		$key = $this->makeCacheKey( $renderId );
		$jsonic = $this->bagOfStuff->get( $key ) ?? [];

		if ( !is_array( $jsonic ) ) {
			// Defend against old stashed data.
			// Only needed for a couple of days after this code has been deployed.
			return null;
		}

		$selserContext = $this->newSelserContextFromJson( $jsonic );
		return $selserContext ?: null;
	}

	private function newSelserContextFromJson( array $json ): ?SelserContext {
		if ( !isset( $json['pb'] ) ) {
			return null;
		}

		// TODO: should use proper JsonCodec for this
		$pb = HtmlPageBundle::newFromJsonArray( $json['pb'] );

		$revId = (int)$json['revId'];

		if ( isset( $json['content'] ) ) {
			$contentHandler = $this->contentHandlerFactory->getContentHandler( $json['content']['model'] );
			$content = $contentHandler->unserializeContent( $json['content']['data'] );
		} else {
			$content = null;
		}

		return new SelserContext( $pb, $revId, $content );
	}

	private function selserContextToJsonArray( SelserContext $selserContext ): array {
		$json = [
			'revId' => $selserContext->getRevisionID(),
		];

		// TODO: should use proper JsonCodec for this
		$json['pb'] = $selserContext->getPageBundle()->toJsonArray();

		$content = $selserContext->getContent();
		if ( $content ) {
			$json['content'] = [
				'model' => $content->getModel(),
				'data' => $content->serialize()
			];
		}

		return $json;
	}

}
