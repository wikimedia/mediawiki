<?php
declare( strict_types = 1 );

namespace MediaWiki\Tests\OutputTransform\Stages;

use MediaWiki\Config\ServiceOptions;
use MediaWiki\OutputTransform\ContentTextTransformStage;
use MediaWiki\OutputTransform\Hook\OutputTransformFirstStageHook;
use MediaWiki\OutputTransform\OutputTransformStageHookTrait;
use MediaWiki\OutputTransform\Stages\ExecuteFirstStageTransformHooks;
use MediaWiki\Parser\ParserOptions;
use MediaWiki\Parser\ParserOutput;
use MediaWiki\Tests\OutputTransform\OutputTransformStageTestBase;
use Psr\Log\NullLogger;

/**
 * @covers \MediaWiki\OutputTransform\Stages\ExecuteFirstStageTransformHooks
 */
class ExecuteFirstStageTransformHooksTest extends OutputTransformStageTestBase {

	public function createStage(): ExecuteFirstStageTransformHooks {
		return new ExecuteFirstStageTransformHooks(
			new ServiceOptions( [] ),
			new NullLogger(),
			$this->getServiceContainer()->getHookContainer()
		);
	}

	public function setUp(): void {
		parent::setUp();
		$this->getServiceContainer()->getHookContainer()->register(
			'OutputTransformFirstStage',
			new class(
				new ServiceOptions( [] ),
				new NullLogger(),
				transformBodyOnly: true,
			)
			extends ContentTextTransformStage
			implements OutputTransformFirstStageHook
{
				use OutputTransformStageHookTrait;

				public function shouldRun( ParserOutput $po, ParserOptions $popts, array $options = [] ): bool {
					return !$popts->getIsPreview(); // for testing
				}

				protected function transformText(
					string $text, ParserOutput $po, ParserOptions $popts, array &$options
				): string {
					return 'executed';
				}
			}
		);
	}

	public static function provideShouldRun(): array {
		// Will always run as long as a hook is registered
		return [
			"should run" => [ new ParserOutput(), ParserOptions::newFromAnon(), [] ],
		];
	}

	public static function provideShouldNotRun(): array {
		return [];
	}

	public static function provideTransform(): array {
		$popts = ParserOptions::newFromAnon();
		$popts->setIsPreview( true );
		$text = "did not run";
		$expected = "executed";
		return [
			"did not run" => [
				new ParserOutput( $text ), $popts, [], new ParserOutput( $text )
			],
			"did run" => [
				new ParserOutput( $text ), ParserOptions::newFromAnon(), [], new ParserOutput( $expected )
			],
		];
	}
}
