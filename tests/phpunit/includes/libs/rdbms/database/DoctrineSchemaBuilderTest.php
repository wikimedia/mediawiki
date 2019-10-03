<?php

use Doctrine\DBAL\Platforms\MySqlPlatform;
use Wikimedia\Rdbms\DoctrineSchemaBuilder;

class DoctrineSchemaBuilderTest extends \PHPUnit\Framework\TestCase {

	/**
	 * @covers \Wikimedia\Rdbms\DoctrineSchemaBuilder
	 * @dataProvider provideGetResult
	 */
	public function testGetResult( $table, $expected, $platform = null ) {
		if ( $platform === null ) {
			$platform = new MySqlPlatform();
		}
		$builder = new DoctrineSchemaBuilder( $platform );
		$builder->addTable( $table );

		$actual = $builder->getSql();

		$this->assertSame(
			$expected,
			$actual
		);
	}

	public function provideGetResult() {
		return [
			[
				[
					'name' => 'actor',
					'columns' => [
						[ 'actor_id', 'bigint', [ 'Unsigned' => true, 'Notnull' => true ] ],
						[ 'actor_user', 'integer', [ 'Unsigned' => true ] ],
						[ 'actor_name', 'string', [ 'Length' => 255, 'Notnull' => true ] ],
					],
					'indexes' => [
						[ 'actor_user', [ 'actor_user' ], 'unique' => true ],
						[ 'actor_name', [ 'actor_name' ], 'unique' => true ]
					],
					'pk' => [ 'actor_id' ]
				],
				[
					'CREATE TABLE /*_*/actor (' .
					'actor_id BIGINT UNSIGNED NOT NULL, ' .
					'actor_user INT UNSIGNED NOT NULL, ' .
					'actor_name VARCHAR(255) NOT NULL, ' .
					'UNIQUE INDEX actor_user (actor_user), ' .
					'UNIQUE INDEX actor_name (actor_name), ' .
					'PRIMARY KEY(actor_id)) '
				],
			],
		];
	}

}
