<?php

namespace MediaWiki\Edit;

use BagOStuff;
use MediaWiki\Parser\Parsoid\ParsoidRenderID;
use Wikimedia\Parsoid\Core\PageBundle;

/**
 * @since 1.39
 * @unstable since 1.39, should be stable before release.
 */
class SimpleParsoidOutputStash implements ParsoidOutputStash {

	/** @var BagOStuff */
	private $bagOfStuff;

	/**
	 * @param BagOStuff $bagOfStuff
	 */
	public function __construct( BagOStuff $bagOfStuff ) {
		$this->bagOfStuff = $bagOfStuff;
	}

	/**
	 * @inheritDoc
	 */
	public function set( ParsoidRenderID $renderId, PageBundle $bundle ): bool {
		return $this->bagOfStuff->set( $renderId->getKey(), $bundle );
		// TODO: Don't use automatic PHP serialization.
	}

	/**
	 * @inheritDoc
	 */
	public function get( ParsoidRenderID $renderId ): ?PageBundle {
		return $this->bagOfStuff->get( $renderId->getKey() ) ?: null;
	}

}
