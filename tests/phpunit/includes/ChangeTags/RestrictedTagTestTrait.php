<?php

namespace MediaWiki\Tests\ChangeTags;

use MediaWikiTestCaseTrait;

/**
 * Helper trait for restricted (private-prefixed) change tag test setup.
 * For use in classes extending {@link MediaWikiIntegrationTestCase}.
 *
 * @stable to use
 * @since 1.47
 * @extends \PHPUnit\Framework\TestCase
 */
trait RestrictedTagTestTrait {

	use MediaWikiTestCaseTrait;

	/**
	 * Handle the {@link ListRestrictedTagsHook} hook to map the given tags to rights.
	 *
	 * @param array<string,string|string[]> $map
	 */
	protected function setRestrictedTags( array $map ): void {
		$this->setTemporaryHook(
			'ListRestrictedTags',
			static function ( array &$restrictedTags ) use ( $map ) {
				$restrictedTags += $map;
			}
		);
	}
}
