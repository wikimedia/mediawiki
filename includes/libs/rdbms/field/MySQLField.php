<?php

namespace Wikimedia\Rdbms;

class MySQLField implements Field {
	private $name, $tablename, $default, $max_length, $nullable,
		$is_pk, $is_unique, $is_multiple, $is_key, $type, $binary,
		$is_numeric, $is_blob, $is_unsigned, $is_zerofill;

	function __construct( $info ) {
		$this->name = $info->name;
		$this->tablename = $info->table;
		$this->default = $info->def;
		$this->max_length = $info->max_length;
		$this->nullable = !$info->not_null;
		$this->is_pk = $info->primary_key;
		$this->is_unique = $info->unique_key;
		$this->is_multiple = $info->multiple_key;
		$this->is_key = ( $this->is_pk || $this->is_unique || $this->is_multiple );
		$this->type = $info->type;
		$this->binary = isset( $info->binary ) ? $info->binary : false;
		$this->is_numeric = isset( $info->numeric ) ? $info->numeric : false;
		$this->is_blob = isset( $info->blob ) ? $info->blob : false;
		$this->is_unsigned = isset( $info->unsigned ) ? $info->unsigned : false;
		$this->is_zerofill = isset( $info->zerofill ) ? $info->zerofill : false;
	}

	/**
	 * @return string
	 */
	function name() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	function tableName() {
		return $this->tablename;
	}

	/**
	 * @return string
	 */
	function type() {
		return $this->type;
	}

	/**
	 * @return bool
	 */
	function isNullable() {
		return $this->nullable;
	}

	function defaultValue() {
		return $this->default;
	}

	/**
	 * @return bool
	 */
	function isKey() {
		return $this->is_key;
	}

	/**
	 * @return bool
	 */
	function isMultipleKey() {
		return $this->is_multiple;
	}

	/**
	 * @return bool
	 */
	function isBinary() {
		return $this->binary;
	}

	/**
	 * @return bool
	 */
	function isNumeric() {
		return $this->is_numeric;
	}

	/**
	 * @return bool
	 */
	function isBlob() {
		return $this->is_blob;
	}

	/**
	 * @return bool
	 */
	function isUnsigned() {
		return $this->is_unsigned;
	}

	/**
	 * @return bool
	 */
	function isZerofill() {
		return $this->is_zerofill;
	}
}
