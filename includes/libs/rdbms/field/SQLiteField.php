<?php

namespace Wikimedia\Rdbms;

use stdClass;

class SQLiteField implements Field {
	private stdClass $info;
	private string $tableName;

	public function __construct( stdClass $info, string $tableName ) {
		$this->info = $info;
		$this->tableName = $tableName;
	}

	/** @inheritDoc */
	public function name() {
		return $this->info->name;
	}

	/** @inheritDoc */
	public function tableName() {
		return $this->tableName;
	}

	/**
	 * @return string
	 */
	public function defaultValue() {
		if ( is_string( $this->info->dflt_value ) ) {
			// Typically quoted
			if ( preg_match( '/^\'(.*)\'$/', $this->info->dflt_value, $matches ) ) {
				return str_replace( "''", "'", $matches[1] );
			}
		}

		return $this->info->dflt_value;
	}

	/**
	 * @return bool
	 */
	public function isNullable() {
		return !$this->info->notnull;
	}

	/** @inheritDoc */
	public function type() {
		return $this->info->type;
	}
}
