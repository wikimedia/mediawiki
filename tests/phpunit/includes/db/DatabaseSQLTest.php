<?php

/**
 * Test the abstract database layer
 * Using Mysql for the sql
 *
 * @group Database
 */
class DatabaseSQLTest extends MediaWikiTestCase {

	/**
	 * @dataProvider dataSQL
	 */
	function testSQL( $sql, $sqlText ) {
		$this->assertEquals( trim( $this->db->selectSQLText(
			isset( $sql['tables'] ) ? $sql['tables'] : array(),
			isset( $sql['fields'] ) ? $sql['fields'] : array(),
			isset( $sql['conds'] ) ? $sql['conds'] : array(),
			__METHOD__,
			isset( $sql['options'] ) ? $sql['options'] : array(),
			isset( $sql['join_conds'] ) ? $sql['join_conds'] : array()
		) ), $sqlText );
	}

	function dataSQL() {
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
		);
	}
	
}