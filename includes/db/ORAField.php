<?php

use Wikimedia\Rdbms\Field;

class ORAField implements Field {
	private $name, $tablename, $default, $max_length, $nullable,
		$is_pk, $is_unique, $is_multiple, $is_key, $type;

	function __construct( $info ) {
		$this->name = $info['column_name'];
		$this->tablename = $info['table_name'];
		$this->default = $info['data_default'];
		$this->max_length = $info['data_length'];
		$this->nullable = $info['not_null'];
		$this->is_pk = isset( $info['prim'] ) && $info['prim'] == 1 ? 1 : 0;
		$this->is_unique = isset( $info['uniq'] ) && $info['uniq'] == 1 ? 1 : 0;
		$this->is_multiple = isset( $info['nonuniq'] ) && $info['nonuniq'] == 1 ? 1 : 0;
		$this->is_key = ( $this->is_pk || $this->is_unique || $this->is_multiple );
		$this->type = $info['data_type'];
	}

	function name() {
		return $this->name;
	}

	function tableName() {
		return $this->tablename;
	}

	function defaultValue() {
		return $this->default;
	}

	function maxLength() {
		return $this->max_length;
	}

	function isNullable() {
		return $this->nullable;
	}

	function isKey() {
		return $this->is_key;
	}

	function isMultipleKey() {
		return $this->is_multiple;
	}

	function type() {
		return $this->type;
	}
}
