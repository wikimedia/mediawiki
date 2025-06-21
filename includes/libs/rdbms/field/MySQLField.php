<?php

namespace Wikimedia\Rdbms;

use stdClass;

class MySQLField implements Field {
	private string $name;
	private string $tablename;
	/** @var mixed */
	private $default;
	private bool $nullable;
	private bool $is_pk;
	private bool $is_unique;
	private bool $is_multiple;
	private bool $is_key;
	private string $type;
	private bool $binary;
	private bool $is_numeric;
	private bool $is_blob;
	private bool $is_unsigned;
	private bool $is_zerofill;

	public function __construct( stdClass $info ) {
		$this->name = $info->name;
		$this->tablename = $info->table;
		$this->default = $info->def;
		$this->nullable = !$info->not_null;
		$this->is_pk = $info->primary_key;
		$this->is_unique = $info->unique_key;
		$this->is_multiple = $info->multiple_key;
		$this->is_key = ( $this->is_pk || $this->is_unique || $this->is_multiple );
		$this->type = $info->type;
		$this->binary = $info->binary ?? false;
		$this->is_numeric = $info->numeric ?? false;
		$this->is_blob = $info->blob ?? false;
		$this->is_unsigned = $info->unsigned ?? false;
		$this->is_zerofill = $info->zerofill ?? false;
	}

	/**
	 * @return string
	 */
	public function name() {
		return $this->name;
	}

	/**
	 * @return string
	 */
	public function tableName() {
		return $this->tablename;
	}

	/**
	 * @return string
	 */
	public function type() {
		return $this->type;
	}

	/**
	 * @return bool
	 */
	public function isNullable() {
		return $this->nullable;
	}

	public function defaultValue() {
		return $this->default;
	}

	/**
	 * @return bool
	 */
	public function isKey() {
		return $this->is_key;
	}

	/**
	 * @return bool
	 */
	public function isMultipleKey() {
		return $this->is_multiple;
	}

	/**
	 * @return bool
	 */
	public function isBinary() {
		return $this->binary;
	}

	/**
	 * @return bool
	 */
	public function isNumeric() {
		return $this->is_numeric;
	}

	/**
	 * @return bool
	 */
	public function isBlob() {
		return $this->is_blob;
	}

	/**
	 * @return bool
	 */
	public function isUnsigned() {
		return $this->is_unsigned;
	}

	/**
	 * @return bool
	 */
	public function isZerofill() {
		return $this->is_zerofill;
	}
}
