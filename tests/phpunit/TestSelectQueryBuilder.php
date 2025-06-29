<?php

use PHPUnit\Framework\Assert;
use Wikimedia\Rdbms\SelectQueryBuilder;

class TestSelectQueryBuilder extends SelectQueryBuilder {
	/** @inheritDoc */
	public function fields( $fields ) {
		$this->orderBy( $fields );
		return parent::fields( $fields );
	}

	/** @inheritDoc */
	public function field( $field, $alias = null ) {
		$this->orderBy( $field );
		return parent::field( $field, $alias );
	}

	/**
	 * Asserts that the current database query yields the rows given by $expectedRows.
	 * The expected rows should be given as indexed (not associative) arrays, with
	 * the values given in the order of the columns in the $fields parameter.
	 * Note that the rows are sorted by the columns given in $fields.
	 *
	 * @param array $expectedRows
	 */
	public function assertResultSet( $expectedRows ) {
		$res = $this->fetchResultSet();

		$i = 0;

		foreach ( $expectedRows as $expected ) {
			$r = $res->fetchRow();
			MediaWikiIntegrationTestCase::stripStringKeys( $r );

			$i++;
			Assert::assertNotFalse( $r, "row #$i missing" );

			Assert::assertEquals( $expected, $r, "row #$i mismatches" );
		}

		$r = $res->fetchRow();
		MediaWikiIntegrationTestCase::stripStringKeys( $r );

		Assert::assertFalse( $r, "found extra row (after #$i)" );
	}

	public function assertEmptyResult() {
		$res = $this->fetchResultSet();
		Assert::assertSame( 0, $res->numRows(), "Result set should be empty" );
	}

	/**
	 * Execute the query, and assert that it returns a single row with a single
	 * field with the given value.
	 *
	 * Unlike fetchField(), LIMIT 1 is not automatically added.
	 *
	 * @param mixed $expectedValue
	 */
	public function assertFieldValue( $expectedValue ) {
		$res = $this->fetchResultSet();
		Assert::assertSame( 1, $res->numRows(),
			"There should be one row in the result set" );
		$row = (array)$res->fetchObject();
		Assert::assertCount( 1, $row,
			"There should be one field in the result set" );
		$value = reset( $row );
		Assert::assertEquals( $expectedValue, $value,
			"The field value should match" );
	}

	/**
	 * Execute the query, and assert that it returns the given values in $expectedValues.
	 *
	 * This method only can be used if you selected one field in the query.
	 *
	 * @since 1.43
	 * @param array $expectedValues
	 */
	public function assertFieldValues( array $expectedValues ) {
		Assert::assertSame( $expectedValues, $this->fetchFieldValues() );
	}

	/**
	 * Execute the query, and assert that it returns a single row with the given value.
	 *
	 * Unlike fetchRow(), LIMIT 1 is not automatically added.
	 *
	 * @since 1.43
	 * @param array $expectedRow
	 */
	public function assertRowValue( array $expectedRow ) {
		$res = $this->fetchResultSet();
		Assert::assertSame( 1, $res->numRows(), "There should be one row in the result set" );
		$row = $res->fetchRow();
		MediaWikiIntegrationTestCase::stripStringKeys( $row );
		Assert::assertEquals( $expectedRow, $row, "The row should match" );
	}
}
