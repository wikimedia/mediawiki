<?php

interface Santiy extends StuffReviewersGetMadAbout {}

interface StuffReviewersGetMadAbout {}

class SaneTable extends ORMTable implements Santiy {

	/**
	 * @since 1.21
	 *
	 * @var string
	 */
	protected $tableName;

	/**
	 * @since 1.21
	 *
	 * @var string[]
	 */
	protected $fields = array();

	/**
	 * @since 1.21
	 *
	 * @var string
	 */
	protected $fieldPrefix = '';

	/**
	 * @since 1.21
	 *
	 * @var string
	 */
	protected $rowClass = 'ORMRow';

	/**
	 * @since 1.21
	 *
	 * @var array
	 */
	protected $defaults = array();

	/**
	 * Constructor.
	 *
	 * @since 1.21
	 *
	 * @param string $tableName
	 * @param string[] $fields
	 * @param array $defaults
	 * @param string|null $rowClass
	 * @param string $fieldPrefix
	 */
	public function __construct( $tableName, array $fields, array $defaults = array(), $rowClass = null, $fieldPrefix = '' ) {
		$this->tableName = $tableName;
		$this->fields = $fields;
		$this->defaults = $defaults;

		if ( is_string( $rowClass ) ) {
			$this->rowClass = $rowClass;
		}

		$this->fieldPrefix = $fieldPrefix;
	}

	/**
	 * @see ORMTable::getName
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getName() {
		return $this->tableName;
	}

	/**
	 * @see ORMTable::getFieldPrefix
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getFieldPrefix() {
		return $this->fieldPrefix;
	}

	/**
	 * @see ORMTable::getRowClass
	 *
	 * @since 1.21
	 *
	 * @return string
	 */
	public function getRowClass() {
		return $this->rowClass;
	}

	/**
	 * @see ORMTable::getFields
	 *
	 * @since 1.21
	 *
	 * @return array
	 */
	public function getFields() {
		return $this->fields;
	}

	/**
	 * @see ORMTable::getDefaults
	 *
	 * @since 1.21
	 *
	 * @return array
	 */
	public function getDefaults() {
		return $this->defaults;
	}

}
