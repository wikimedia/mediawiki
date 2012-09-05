<?php

/**
 * Test the abstract database layer
 * Using Mysql for the sql at the moment TODO
 *
 * @group Database
 */
class DatabaseSQLTest extends MediaWikiTestCase {

	public function setUp() {
		// TODO support other DBMS or find another way to do it
		if( $this->db->getType() !== 'mysql' ) {
			$this->markTestSkipped( 'No mysql database' );
		}
	}

	/**
	 * @dataProvider dataSelectSQLText
	 */
	function testSelectSQLText( $sql, $sqlText ) {
		$this->assertEquals( trim( $this->db->selectSQLText(
			isset( $sql['tables'] ) ? $sql['tables'] : array(),
			isset( $sql['fields'] ) ? $sql['fields'] : array(),
			isset( $sql['conds'] ) ? $sql['conds'] : array(),
			__METHOD__,
			isset( $sql['options'] ) ? $sql['options'] : array(),
			isset( $sql['join_conds'] ) ? $sql['join_conds'] : array()
		) ), $sqlText );
	}

	function dataSelectSQLText() {
		return array(
			array(
				array(
					'tables' => 'table',
					'fields' => array( 'field', 'alias' => 'field2' ),
					'conds' => array( 'alias' => 'text' ),
				),
				"SELECT  field,field2 AS alias  " .
				"FROM `unittest_table`  " .
				"WHERE alias = 'text'"
			),
			array(
				array(
					'tables' => 'table',
					'fields' => array( 'field', 'alias' => 'field2' ),
					'conds' => array( 'alias' => 'text' ),
					'options' => array( 'LIMIT' => 1, 'ORDER BY' => 'field' ),
				),
				"SELECT  field,field2 AS alias  " .
				"FROM `unittest_table`  " .
				"WHERE alias = 'text'  " .
				"ORDER BY field " .
				"LIMIT 1"
			),
			array(
				array(
					'tables' => array( 'table', 't2' => 'table2' ),
					'fields' => array( 'tid', 'field', 'alias' => 'field2', 't2.id' ),
					'conds' => array( 'alias' => 'text' ),
					'options' => array( 'LIMIT' => 1, 'ORDER BY' => 'field' ),
					'join_conds' => array( 't2' => array(
						'LEFT JOIN', 'tid = t2.id'
					)),
				),
				"SELECT  tid,field,field2 AS alias,t2.id  " .
				"FROM `unittest_table` LEFT JOIN `unittest_table2` `t2` ON ((tid = t2.id))  " .
				"WHERE alias = 'text'  " .
				"ORDER BY field " .
				"LIMIT 1"
			),
			array(
				array(
					'tables' => array( 'table', 't2' => 'table2' ),
					'fields' => array( 'tid', 'field', 'alias' => 'field2', 't2.id' ),
					'conds' => array( 'alias' => 'text' ),
					'options' => array( 'LIMIT' => 1, 'GROUP BY' => 'field', 'HAVING' => 'COUNT(*) > 1' ),
					'join_conds' => array( 't2' => array(
						'LEFT JOIN', 'tid = t2.id'
					)),
				),
				"SELECT  tid,field,field2 AS alias,t2.id  " .
				"FROM `unittest_table` LEFT JOIN `unittest_table2` `t2` ON ((tid = t2.id))  " .
				"WHERE alias = 'text'  " .
				"GROUP BY field HAVING COUNT(*) > 1 " .
				"LIMIT 1"
			),
			array(
				array(
					'tables' => array( 'table', 't2' => 'table2' ),
					'fields' => array( 'tid', 'field', 'alias' => 'field2', 't2.id' ),
					'conds' => array( 'alias' => 'text' ),
					'options' => array( 'LIMIT' => 1, 'GROUP BY' => array( 'field', 'field2' ), 'HAVING' => array( 'COUNT(*) > 1', 'field' => 1 ) ),
					'join_conds' => array( 't2' => array(
						'LEFT JOIN', 'tid = t2.id'
					)),
				),
				"SELECT  tid,field,field2 AS alias,t2.id  " .
				"FROM `unittest_table` LEFT JOIN `unittest_table2` `t2` ON ((tid = t2.id))  " .
				"WHERE alias = 'text'  " .
				"GROUP BY field,field2 HAVING (COUNT(*) > 1) AND field = '1' " .
				"LIMIT 1"
			),
		);
	}

	/**
	 * @dataProvider dataConditional
	 */
	function testConditional( $sql, $sqlText ) {
		$this->assertEquals( trim( $this->db->conditional(
			$sql['conds'],
			$sql['true'],
			$sql['false']
		) ), $sqlText );
	}

	function dataConditional() {
		return array(
			array(
				array(
					'conds' => array( 'field' => 'text' ),
					'true' => 1,
					'false' => 'NULL',
				),
				"(CASE WHEN field = 'text' THEN 1 ELSE NULL END)"
			),
			array(
				array(
					'conds' => array( 'field' => 'text', 'field2' => 'anothertext' ),
					'true' => 1,
					'false' => 'NULL',
				),
				"(CASE WHEN field = 'text' AND field2 = 'anothertext' THEN 1 ELSE NULL END)"
			),
			array(
				array(
					'conds' => 'field=1',
					'true' => 1,
					'false' => 'NULL',
				),
				"(CASE WHEN field=1 THEN 1 ELSE NULL END)"
			),
		);
	}
}