<?php

namespace Wikimedia\Rdbms;

class PostgresField implements Field {
	private $name, $tablename, $type, $nullable, $max_length, $deferred, $deferrable, $conname,
		$has_default, $default;

	/**
	 * @param DatabasePostgres $db
	 * @param string $table
	 * @param string $field
	 * @return null|PostgresField
	 */
	static function fromText( DatabasePostgres $db, $table, $field ) {
		$q = <<<SQL
SELECT
 attnotnull, attlen, conname AS conname,
 atthasdef,
 adsrc,
 COALESCE(condeferred, 'f') AS deferred,
 COALESCE(condeferrable, 'f') AS deferrable,
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

		$table = $db->remappedTableName( $table );
		$res = $db->query(
			sprintf( $q,
				$db->addQuotes( $db->getCoreSchema() ),
				$db->addQuotes( $table ),
				$db->addQuotes( $field )
			)
		);
		$row = $db->fetchObject( $res );
		if ( !$row ) {
			return null;
		}
		$n = new PostgresField;
		$n->type = $row->typname;
		$n->nullable = ( $row->attnotnull == 'f' );
		$n->name = $field;
		$n->tablename = $table;
		$n->max_length = $row->attlen;
		$n->deferrable = ( $row->deferrable == 't' );
		$n->deferred = ( $row->deferred == 't' );
		$n->conname = $row->conname;
		$n->has_default = ( $row->atthasdef === 't' );
		$n->default = $row->adsrc;

		return $n;
	}

	function name() {
		return $this->name;
	}

	function tableName() {
		return $this->tablename;
	}

	function type() {
		return $this->type;
	}

	function isNullable() {
		return $this->nullable;
	}

	function maxLength() {
		return $this->max_length;
	}

	function is_deferrable() {
		return $this->deferrable;
	}

	function is_deferred() {
		return $this->deferred;
	}

	function conname() {
		return $this->conname;
	}

	/**
	 * @since 1.19
	 * @return bool|mixed
	 */
	function defaultValue() {
		if ( $this->has_default ) {
			return $this->default;
		} else {
			return false;
		}
	}
}
