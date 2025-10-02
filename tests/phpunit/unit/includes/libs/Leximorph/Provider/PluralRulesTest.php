<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace Wikimedia\Tests\Leximorph\Provider;

use CLDRPluralRuleParser\Evaluator;
use PHPUnit\Framework\TestCase;
use Psr\Log\NullLogger;
use ReflectionClass;
use Wikimedia\Leximorph\Provider;
use Wikimedia\Leximorph\Provider\PluralRules;
use Wikimedia\Leximorph\Util\XmlLoader;

/**
 * This test class verifies the functionality of the {@see PluralRules} class.
 *
 * Covered tests include:
 *   - Returning the expected raw plural rules.
 *   - Returning the expected plural rule types.
 *
 * @covers \Wikimedia\Leximorph\Provider\PluralRules
 * @author Doğu Abaris (abaris@null.net)
 */
class PluralRulesTest extends TestCase {

	/**
	 * Resets the static plural data cache to the provided value.
	 *
	 * @param array<string, list<array{type: string, rule: string}>>|null $data The dummy plural data to set.
	 */
	private function setPluralData( ?array $data ): void {
		$reflection = new ReflectionClass( PluralRules::class );
		$property = $reflection->getProperty( 'pluralData' );
		$property->setValue( null, $data );
	}

	protected function tearDown(): void {
		// Clears the static plural data cache
		$this->setPluralData( null );
		parent::tearDown();
	}

	/**
	 * Tests that getPluralRules() returns the expected rules when data exists.
	 */
	public function testGetPluralRulesWithData(): void {
		$dummyData = [ 'xx' => [ [ 'type' => 'one', 'rule' => 'n is 1', ],
			[ 'type' => 'other', 'rule' => 'n != 1', ], ], ];
		$this->setPluralData( $dummyData );

		$logger = new NullLogger();
		$langCode = 'xx';

		$provider = new Provider( $langCode, $logger );
		$evaluator = new Evaluator();
		$xmlLoader = new XmlLoader( $logger );

		$pluralRules = new PluralRules( $langCode, $evaluator, $provider, $logger, $xmlLoader );

		$rules = $pluralRules->getPluralRules();

		$this->assertSame( [ 'n is 1', 'n != 1', ], $rules,
			"Raw plural rules for language 'xx' do not match expected values." );
	}

	/**
	 * Tests that getPluralRules() returns null when no data exists.
	 */
	public function testGetPluralRulesWithNoData(): void {
		// No data set
		$this->setPluralData( [] );

		$logger = new NullLogger();
		$langCode = 'yy';

		$provider = new Provider( $langCode, $logger );
		$evaluator = new Evaluator();
		$xmlLoader = new XmlLoader( $logger );

		$pluralRules = new PluralRules( $langCode, $evaluator, $provider, $logger, $xmlLoader );

		$rules = $pluralRules->getPluralRules();

		$this->assertNull( $rules, "Expected null plural rules for language 'yy' when no data exists." );
	}

	/**
	 * Tests that getPluralRuleTypes() returns the expected types when data exists.
	 */
	public function testGetPluralRuleTypes(): void {
		$dummyData = [ 'xx' => [ [ 'type' => 'one', 'rule' => 'n is 1', ],
			[ 'type' => 'other', 'rule' => 'n != 1', ], ], ];
		$this->setPluralData( $dummyData );

		$logger = new NullLogger();
		$langCode = 'xx';

		$provider = new Provider( $langCode, $logger );
		$evaluator = new Evaluator();
		$xmlLoader = new XmlLoader( $logger );

		$pluralRules = new PluralRules( $langCode, $evaluator, $provider, $logger, $xmlLoader );

		$types = $pluralRules->getPluralRuleTypes();

		$this->assertSame( [ 'one', 'other', ], $types,
			"Plural rule types for language 'xx' do not match expected values." );
	}
}
