<?php

namespace MediaWiki\Edit;

use BagOStuff;
use FormatJson;
use MediaWiki\Parser\Parsoid\PageBundleJsonTrait;
use MediaWiki\Parser\Parsoid\ParsoidRenderID;
use Wikimedia\Parsoid\Core\PageBundle;

/**
 * @since 1.39
 * @unstable since 1.39, should be stable before release.
 */
class SimpleParsoidOutputStash implements ParsoidOutputStash {
	use PageBundleJsonTrait;

	/** @var BagOStuff */
	private $bagOfStuff;

	/**
	 * @param BagOStuff $bagOfStuff
	 */
	public function __construct( BagOStuff $bagOfStuff ) {
		$this->bagOfStuff = $bagOfStuff;
	}

	/**
	 * Before we stash, we serialize & encode into JSON the relevant
	 * parts of the data we need to construct a page bundle in the future.
	 *
	 * @param ParsoidRenderId $renderId
	 * @param PageBundle $bundle
	 *
	 * @return bool
	 */
	public function set( ParsoidRenderId $renderId, PageBundle $bundle ): bool {
		$pageBundleJson = FormatJson::encode( $this->jsonSerializePageBundle( $bundle ) );

		return $this->bagOfStuff->set( $renderId->getKey(), $pageBundleJson );
	}

	/**
	 * This will decode the JSON data and create a page bundle from it
	 * if we have something in the stash that matches a given rendering or
	 * will just return an empty array if no entry in the stash.
	 *
	 * @param ParsoidRenderId $renderId
	 *
	 * @return PageBundle|null
	 */
	public function get( ParsoidRenderId $renderId ): ?PageBundle {
		$pageBundleArray = FormatJson::decode(
				$this->bagOfStuff->get( $renderId->getKey() ),
				true
			) ?? [];
		$pageBundle = $this->newPageBundleFromJson( $pageBundleArray );

		return $pageBundle ?: null;
	}

}
