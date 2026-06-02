<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\ExpandToAbsoluteUrlsText;
use Psr\Log\NullLogger;

/**
 * @covers \MediaWiki\OutputTransform\Stages\ExpandToAbsoluteUrlsText
 */
class ExpandToAbsoluteUrlsTextTest extends ExpandToAbsoluteUrlsTestBase {

	public function createStage(): OutputTransformStage {
		return new ExpandToAbsoluteUrlsText(
			new ServiceOptions( [] ),
			new NullLogger(),
		);
	}
}
