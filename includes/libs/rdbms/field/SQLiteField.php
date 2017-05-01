<?php
class SQLiteField implements Field {
	private $info, $tableName;

	function __construct( $info, $tableName ) {
		$this->info = $info;
		$this->tableName = $tableName;
	}

	function name() {
		return $this->info->name;
	}

	function tableName() {
		return $this->tableName;
	}

	function defaultValue() {
		if ( is_string( $this->info->dflt_value ) ) {
			// Typically quoted
			if ( preg_match( '/^\'(.*)\'$', $this->info->dflt_value ) ) {
				return str_replace( "''", "'", $this->info->dflt_value );
			}
		}

		return $this->info->dflt_value;
	}

	/**
	 * @return bool
	 */
	function isNullable() {
		return !$this->info->notnull;
	}

	function type() {
		return $this->info->type;
	}
}
