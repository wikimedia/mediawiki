<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\ExpandToAbsoluteUrls;
use Psr\Log\NullLogger;

/**
 * @covers \MediaWiki\OutputTransform\Stages\ExpandToAbsoluteUrls
 */
class ExpandToAbsoluteUrlsTest extends ExpandToAbsoluteUrlsTestBase {

	public function createStage(): OutputTransformStage {
		return new ExpandToAbsoluteUrls(
			new ServiceOptions( [] ),
			new NullLogger()
		);
	}
}
