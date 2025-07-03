<?php

namespace Wikimedia\Rdbms;

class PostgresField implements Field {
	private string $name;
	private string $tablename;
	private string $type;
	private bool $nullable;
	/** @var int */
	private $max_length;
	private bool $deferred;
	private bool $deferrable;
	private ?string $conname;
	private bool $has_default;
	/** @var mixed */
	private $default;

	/**
	 * @param DatabasePostgres $db
	 * @param string $table
	 * @param string $field
	 * @return null|PostgresField
	 */
	public static function fromText( DatabasePostgres $db, $table, $field ) {
		$q = <<<SQL
SELECT
 attnotnull, attlen, conname AS conname,
 atthasdef,
 pg_get_expr(adbin, adrelid) AS adsrc,
 COALESCE(condeferred, FALSE) AS deferred,
 COALESCE(condeferrable, FALSE) AS deferrable,
 CASE WHEN typname = 'int2' THEN 'smallint'
  WHEN typname = 'int4' THEN 'integer'
  WHEN typname = 'int8' THEN 'bigint'
  WHEN typname = 'bpchar' THEN 'char'
 ELSE typname END AS typname
FROM pg_class c
JOIN pg_namespace n ON (n.oid = c.relnamespace)
JOIN pg_attribute a ON (a.attrelid = c.oid)
JOIN pg_type t ON (t.oid = a.atttypid)
LEFT JOIN pg_constraint o ON (o.conrelid = c.oid AND a.attnum = ANY(o.conkey) AND o.contype = 'f')
LEFT JOIN pg_attrdef d on c.oid=d.adrelid and a.attnum=d.adnum
WHERE relkind = 'r'
AND nspname=%s
AND relname=%s
AND attname=%s;
SQL;

		foreach ( $db->getCoreSchemas() as $schema ) {
			$res = $db->query(
				sprintf( $q,
					$db->addQuotes( $schema ),
					$db->addQuotes( $db->tableName( $table, 'raw' ) ),
					$db->addQuotes( $field )
				),
				__METHOD__
			);
			$row = $res->fetchObject();
			if ( !$row ) {
				continue;
			}
			$n = new PostgresField;
			$n->type = $row->typname;
			$n->nullable = !$row->attnotnull;
			$n->name = $field;
			$n->tablename = $table;
			$n->max_length = $row->attlen;
			$n->deferrable = (bool)$row->deferrable;
			$n->deferred = (bool)$row->deferred;
			$n->conname = $row->conname;
			$n->has_default = (bool)$row->atthasdef;
			$n->default = $row->adsrc;

			return $n;
		}

		return null;
	}

	public function name() {
		return $this->name;
	}

	public function tableName() {
		return $this->tablename;
	}

	public function type() {
		return $this->type;
	}

	public function isNullable() {
		return $this->nullable;
	}

	/**
	 * @return int
	 */
	public function maxLength() {
		return $this->max_length;
	}

	/**
	 * @return bool
	 */
	public function is_deferrable() {
		return $this->deferrable;
	}

	/**
	 * @return bool
	 */
	public function is_deferred() {
		return $this->deferred;
	}

	/**
	 * @return string|null
	 */
	public function conname() {
		return $this->conname;
	}

	/**
	 * @since 1.19
	 * @return mixed|false
	 */
	public function defaultValue() {
		if ( $this->has_default ) {
			return $this->default;
		} else {
			return false;
		}
	}
}
