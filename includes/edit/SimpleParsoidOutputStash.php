<?php

namespace MediaWiki\Edit;

use MediaWiki\Json\JsonCodec;
use Wikimedia\ObjectCache\BagOStuff;

/**
 * @internal
 * @since 1.39
 */
class SimpleParsoidOutputStash implements ParsoidOutputStash {

	public function __construct(
		private JsonCodec $jsonCodec,
		/** Storage backend */
		private BagOStuff $bagOfStuff,
		/** Cache duration in seconds */
		private int $duration,
	) {
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
		$jsonic = $this->jsonCodec->toJsonArray(
			$selserContext, SelserContext::class
		);
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
		if ( !isset( $jsonic['pb'] ) ) {
			return null;
		}
		return $this->jsonCodec->newFromJsonArray(
			$jsonic, SelserContext::class
		);
	}

}
