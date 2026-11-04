<?php

declare( strict_types=1 );

namespace MediaWiki\Tests;

/**
 * Tests ExtensionServicesTestBase with an ExtensionServices class that has a wrap() method.
 *
 * @coversNothing
 * @license GPL-2.0-or-later
 */
class WrappingExtensionServicesTest extends ExtensionServicesTestBase {

	/** @inheritDoc */
	protected static string $className = WrappingExtensionServices::class;

	/** @inheritDoc */
	protected static bool $wrapsServiceContainer = true;

	/** @inheritDoc */
	protected string $serviceNamePrefix = '';

	/** @inheritDoc */
	protected array $serviceNamesByMethod = [
		'getParser' => 'TitleParser',
	];

}
