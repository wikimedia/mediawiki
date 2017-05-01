<?php
class MssqlField implements Field {
	private $name, $tableName, $default, $max_length, $nullable, $type;

	function __construct( $info ) {
		$this->name = $info['COLUMN_NAME'];
		$this->tableName = $info['TABLE_NAME'];
		$this->default = $info['COLUMN_DEFAULT'];
		$this->max_length = $info['CHARACTER_MAXIMUM_LENGTH'];
		$this->nullable = !( strtolower( $info['IS_NULLABLE'] ) == 'no' );
		$this->type = $info['DATA_TYPE'];
	}

	function name() {
		return $this->name;
	}

	function tableName() {
		return $this->tableName;
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

	function type() {
		return $this->type;
	}
}

