<?php

use MediaWiki\Tests\Unit\Libs\Rdbms\AddQuoterMock;
use MediaWiki\Tests\Unit\Libs\Rdbms\SQLPlatformTestHelper;
use Wikimedia\Rdbms\LikeMatch;

/**
 * @covers Wikimedia\Rdbms\Platform\SQLPlatform
 * @covers Wikimedia\Rdbms\Database
 */
class SQLPlatformTest extends PHPUnit\Framework\TestCase {

	use MediaWikiCoversValidator;

	/** @var SQLPlatformTestHelper */
	private $platform;

	protected function setUp(): void {
		parent::setUp();
		$this->platform = new SQLPlatformTestHelper( new AddQuoterMock() );
	}

	/**
	 * @dataProvider provideGreatest
	 */
	public function testBuildGreatest( $fields, $values, $sqlText ) {
		$this->assertEquals(
			$sqlText,
			trim( $this->platform->buildGreatest( $fields, $values ) )
		);
	}

	public static function provideGreatest() {
		return [
			[
				'field',
				'value',
				"GREATEST(field,'value')"
			],
			[
				[ 'field' ],
				[ 'value' ],
				"GREATEST(field,'value')"
			],
			[
				[ 'field', 'field2' ],
				[ 'value', 'value2' ],
				"GREATEST(field,field2,'value','value2')"
			],
			[
				[ 'field', 'b' => 'field2 + 1' ],
				[ 'value', 'value2' ],
				"GREATEST(field,field2 + 1,'value','value2')"
			],
		];
	}

	/**
	 * @dataProvider provideLeast
	 */
	public function testBuildLeast( $fields, $values, $sqlText ) {
		$this->assertEquals(
			$sqlText,
			trim( $this->platform->buildLeast( $fields, $values ) )
		);
	}

	public static function provideLeast() {
		return [
			[
				'field',
				'value',
				"LEAST(field,'value')"
			],
			[
				[ 'field' ],
				[ 'value' ],
				"LEAST(field,'value')"
			],
			[
				[ 'field', 'field2' ],
				[ 'value', 'value2' ],
				"LEAST(field,field2,'value','value2')"
			],
			[
				[ 'field', 'b' => 'field2 + 1' ],
				[ 'value', 'value2' ],
				"LEAST(field,field2 + 1,'value','value2')"
			],
		];
	}

	/**
	 * @dataProvider provideBuildComparison
	 */
	public function testBuildComparison( string $op, array $conds, string $sqlText ) {
		$this->assertEquals(
			$sqlText,
			$this->platform->buildComparison( $op, $conds )
		);
	}

	public static function provideBuildComparison() {
		return [
			"Simple '>'" => [
				'>',
				[ 'a' => 1 ],
				'a > 1',
			],
			"Simple '>='" => [
				'>=',
				[ 'a' => 1 ],
				'a >= 1',
			],
			"Simple '<'" => [
				'<',
				[ 'a' => 1 ],
				'a < 1',
			],
			"Simple '<='" => [
				'<=',
				[ 'a' => 1 ],
				'a <= 1',
			],
			"Complex '>'" => [
				'>',
				[ 'a' => 1, 'b' => 2, 'c' => 3 ],
				'a > 1 OR (a = 1 AND (b > 2 OR (b = 2 AND (c > 3))))',
			],
			"Complex '>='" => [
				'>=',
				[ 'a' => 1, 'b' => 2, 'c' => 3 ],
				'a > 1 OR (a = 1 AND (b > 2 OR (b = 2 AND (c >= 3))))',
			],
			"Complex '<'" => [
				'<',
				[ 'a' => 1, 'b' => 2, 'c' => 3 ],
				'a < 1 OR (a = 1 AND (b < 2 OR (b = 2 AND (c < 3))))',
			],
			"Complex '<='" => [
				'<=',
				[ 'a' => 1, 'b' => 2, 'c' => 3 ],
				'a < 1 OR (a = 1 AND (b < 2 OR (b = 2 AND (c <= 3))))',
			],
			"Quoting: fields are SQL identifiers, values are values" => [
				// Note that the quoting here doesn't match any real database because
				// SQLPlatformTestHelper overrides it
				'>',
				[ '`quoted\'as"field' => '`quoted\'as"value' ],
				'`quoted\'as"field > \'`quoted\\\'as"value\'',
			],
		];
	}

	/**
	 * @dataProvider provideBuildLike
	 */
	public function testBuildLike( $array, $sqlText ) {
		$this->assertEquals( trim( $this->platform->buildLike(
			$array
		) ), $sqlText );
	}

	public static function provideBuildLike() {
		return [
			[
				'text',
				"LIKE 'text' ESCAPE '`'"
			],
			[
				[ 'text', new LikeMatch( '%' ) ],
				"LIKE 'text%' ESCAPE '`'"
			],
			[
				[ 'text', new LikeMatch( '%' ), 'text2' ],
				"LIKE 'text%text2' ESCAPE '`'"
			],
			[
				[ 'text', new LikeMatch( '_' ) ],
				"LIKE 'text_' ESCAPE '`'"
			],
			[
				'more_text',
				"LIKE 'more`_text' ESCAPE '`'"
			],
			[
				[ 'C:\\Windows\\', new LikeMatch( '%' ) ],
				"LIKE 'C:\\Windows\\%' ESCAPE '`'"
			],
			[
				[ 'accent`_test`', new LikeMatch( '%' ) ],
				"LIKE 'accent```_test``%' ESCAPE '`'"
			],
		];
	}

	/**
	 * @dataProvider provideUnionConditionPermutations
	 */
	public function testUnionConditionPermutations( $params, $expect ) {
		if ( isset( $params['unionSupportsOrderAndLimit'] ) ) {
			$this->platform->setUnionSupportsOrderAndLimit( $params['unionSupportsOrderAndLimit'] );
		}

		$sql = trim( $this->platform->unionConditionPermutations(
			$params['table'],
			$params['vars'],
			$params['permute_conds'],
			$params['extra_conds'] ?? '',
			'FNAME',
			$params['options'] ?? [],
			$params['join_conds'] ?? []
		) );
		$this->assertEquals( $expect, $sql );
	}

	public static function provideUnionConditionPermutations() {
		return [
			[
				[
					'table' => [ 'table1', 'table2' ],
					'vars' => [ 'field1', 'alias' => 'field2' ],
					'permute_conds' => [
						'field3' => [ 1, 2, 3 ],
						'duplicates' => [ 4, 5, 4 ],
						'empty' => [],
						'single' => [ 0 ],
					],
					'extra_conds' => 'table2.bar > 23',
					'options' => [
						'ORDER BY' => [ 'field1', 'alias' ],
						'INNER ORDER BY' => [ 'field1', 'field2' ],
						'LIMIT' => 100,
					],
					'join_conds' => [
						'table2' => [ 'JOIN', 'table1.foo_id = table2.foo_id' ],
					],
				],
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = 1 AND duplicates = 4 AND single = 0 AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) UNION ALL " .
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = 1 AND duplicates = 5 AND single = 0 AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) UNION ALL " .
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = 2 AND duplicates = 4 AND single = 0 AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) UNION ALL " .
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = 2 AND duplicates = 5 AND single = 0 AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) UNION ALL " .
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = 3 AND duplicates = 4 AND single = 0 AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) UNION ALL " .
				"(SELECT  field1,field2 AS alias  FROM table1 JOIN table2 ON ((table1.foo_id = table2.foo_id))   WHERE field3 = 3 AND duplicates = 5 AND single = 0 AND (table2.bar > 23)  ORDER BY field1,field2 LIMIT 100  ) " .
				"ORDER BY field1,alias LIMIT 100"
			],
			[
				[
					'table' => 'foo',
					'vars' => [ 'foo_id' ],
					'permute_conds' => [
						'bar' => [ 1, 2, 3 ],
					],
					'extra_conds' => [ 'baz' => null ],
					'options' => [
						'NOTALL',
						'ORDER BY' => [ 'foo_id' ],
						'LIMIT' => 25,
					],
				],
				"(SELECT  foo_id  FROM foo    WHERE bar = 1 AND baz IS NULL  ORDER BY foo_id LIMIT 25  ) UNION " .
				"(SELECT  foo_id  FROM foo    WHERE bar = 2 AND baz IS NULL  ORDER BY foo_id LIMIT 25  ) UNION " .
				"(SELECT  foo_id  FROM foo    WHERE bar = 3 AND baz IS NULL  ORDER BY foo_id LIMIT 25  ) " .
				"ORDER BY foo_id LIMIT 25"
			],
			[
				[
					'table' => 'foo',
					'vars' => [ 'foo_id' ],
					'permute_conds' => [
						'bar' => [ 1, 2, 3 ],
					],
					'extra_conds' => [ 'baz' => null ],
					'options' => [
						'NOTALL' => true,
						'ORDER BY' => [ 'foo_id' ],
						'LIMIT' => 25,
					],
					'unionSupportsOrderAndLimit' => false,
				],
				"(SELECT  foo_id  FROM foo    WHERE bar = 1 AND baz IS NULL  ) UNION " .
				"(SELECT  foo_id  FROM foo    WHERE bar = 2 AND baz IS NULL  ) UNION " .
				"(SELECT  foo_id  FROM foo    WHERE bar = 3 AND baz IS NULL  ) " .
				"ORDER BY foo_id LIMIT 25"
			],
			[
				[
					'table' => 'foo',
					'vars' => [ 'foo_id' ],
					'permute_conds' => [],
					'extra_conds' => [ 'baz' => null ],
					'options' => [
						'ORDER BY' => [ 'foo_id' ],
						'LIMIT' => 25,
					],
				],
				"SELECT  foo_id  FROM foo    WHERE baz IS NULL  ORDER BY foo_id LIMIT 25"
			],
			[
				[
					'table' => 'foo',
					'vars' => [ 'foo_id' ],
					'permute_conds' => [
						'bar' => [],
					],
					'extra_conds' => [ 'baz' => null ],
					'options' => [
						'ORDER BY' => [ 'foo_id' ],
						'LIMIT' => 25,
					],
				],
				"SELECT  foo_id  FROM foo    WHERE baz IS NULL  ORDER BY foo_id LIMIT 25"
			],
			[
				[
					'table' => 'foo',
					'vars' => [ 'foo_id' ],
					'permute_conds' => [
						'bar' => [ 1 ],
					],
					'options' => [
						'ORDER BY' => [ 'foo_id' ],
						'LIMIT' => 25,
						'OFFSET' => 150,
					],
				],
				"SELECT  foo_id  FROM foo    WHERE bar = 1  ORDER BY foo_id LIMIT 150,25"
			],
			[
				[
					'table' => 'foo',
					'vars' => [ 'foo_id' ],
					'permute_conds' => [],
					'extra_conds' => [ 'baz' => null ],
					'options' => [
						'ORDER BY' => [ 'foo_id' ],
						'LIMIT' => 25,
						'OFFSET' => 150,
						'INNER ORDER BY' => [ 'bar_id' ],
					],
				],
				"(SELECT  foo_id  FROM foo    WHERE baz IS NULL  ORDER BY bar_id LIMIT 175  ) ORDER BY foo_id LIMIT 150,25"
			],
			[
				[
					'table' => 'foo',
					'vars' => [ 'foo_id' ],
					'permute_conds' => [],
					'extra_conds' => [ 'baz' => null ],
					'options' => [
						'ORDER BY' => [ 'foo_id' ],
						'LIMIT' => 25,
						'OFFSET' => 150,
						'INNER ORDER BY' => [ 'bar_id' ],
					],
					'unionSupportsOrderAndLimit' => false,
				],
				"SELECT  foo_id  FROM foo    WHERE baz IS NULL  ORDER BY foo_id LIMIT 150,25"
			],
		];
		// phpcs:enable
	}

	/**
	 * @param string $query
	 * @param bool $res
	 * @dataProvider provideIsWriteQuery
	 */
	public function testIsWriteQuery( string $query, bool $res ) {
		$this->assertSame( $res, $this->platform->isWriteQuery( $query, 0 ) );
	}

	/**
	 * @return array
	 */
	public function provideIsWriteQuery(): array {
		return [
			[ 'SELECT foo', false ],
			[ '  SELECT foo FROM bar', false ],
			[ 'BEGIN', false ],
			[ 'SHOW EXPLAIN FOR 12;', false ],
			[ 'USE foobar', false ],
			[ '(SELECT 1)', false ],
			[ 'INSERT INTO foo', true ],
			[ 'TRUNCATE bar', true ],
			[ 'DELETE FROM baz', true ],
			[ 'CREATE TABLE foobar', true ]
		];
	}

}
