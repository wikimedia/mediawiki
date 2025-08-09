<?php
/**
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
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
 * PluralRulesTest
 *
 * This test class verifies the functionality of the {@see PluralRules} class.
 *
 * Covered tests include:
 *   - Returning the expected raw plural rules.
 *   - Returning the expected plural rule types.
 *
 * @since     1.45
 * @author    Doğu Abaris (abaris@null.net)
 * @license   https://www.gnu.org/copyleft/gpl.html GPL-2.0-or-later
 *
 * @covers \Wikimedia\Leximorph\Provider\PluralRules
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

	/**
	 * Clears the static plural data cache after each test.
	 */
	protected function tearDown(): void {
		$this->setPluralData( null );
		parent::tearDown();
	}

	/**
	 * Tests that getPluralRules() returns the expected rules when data exists.
	 *
	 * @since 1.45
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
	 *
	 * @since 1.45
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
	 *
	 * @since 1.45
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
