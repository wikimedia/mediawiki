<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\OutputTransformStage;
use MediaWiki\OutputTransform\Stages\ExpandToAbsoluteUrlsDOM;
use Psr\Log\NullLogger;

/**
 * @covers \MediaWiki\OutputTransform\Stages\ExpandToAbsoluteUrlsDOM
 */
class ExpandToAbsoluteUrlsDOMTest extends ExpandToAbsoluteUrlsTestBase {

	public function createStage(): OutputTransformStage {
		return new ExpandToAbsoluteUrlsDOM(
			new ServiceOptions( [] ),
			new NullLogger(),
			$this->getServiceContainer()->getUrlUtils(),
		);
	}
}
