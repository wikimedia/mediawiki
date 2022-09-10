<?php

namespace MediaWiki\Parser\Parsoid;

use MediaWiki\Json\JsonConstants;
use Wikimedia\Parsoid\Core\PageBundle;

/**
 * Trait to aid in serializing/de-serializing PageBundle objects to
 * and from JSON. Use in lieu of PHP built in serializer.
 *
 * @since 1.39
 * @unstable since 1.39, should be removed before release.
 */
trait PageBundleJsonTrait {

	/**
	 * @param array $data
	 *
	 * @return ?PageBundle
	 */
	protected function newPageBundleFromJson( array $data ): ?PageBundle {
		if ( !$data ) {
			return null;
		}
		return new PageBundle(
			$data['html'],
			$data['parsoid'] ?? null,
			$data['mw'] ?? null,
			$data['version'] ?? null,
			$data['headers'] ?? null,
			$data['contentmodel'] ?? null
		);
	}

	/**
	 * @param PageBundle $bundle
	 *
	 * @return array
	 */
	protected function jsonSerializePageBundle( PageBundle $bundle ): array {
		return [
			JsonConstants::TYPE_ANNOTATION => get_class( $bundle ),
			'html' => $bundle->html,
			'parsoid' => $bundle->parsoid,
			'mw' => $bundle->mw,
			'version' => $bundle->version,
			'headers' => $bundle->headers,
			'contentmodel' => $bundle->contentmodel
		];
	}

}
