<?php

namespace MediaWiki\Storage;

use DBError;
use InvalidArgumentException;
use LoadBalancer;
use Wikimedia\Assert\Assert;

/**
 * NameInterner implemented based on a tale in a SQL based DBMS.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class SqlTableNameInterner implements NameInterner {

	/**
	 * @var LoadBalancer
	 */
	private $dbLoadBalancer;
	/**
	 * @var
	 */
	private $table;
	/**
	 * @var
	 */
	private $fieldPrefix;

	/**
	 * RevisionContentStore constructor.
	 *
	 * @param LoadBalancer $dbLoadBalancer
	 * @param string $table
	 * @param string $fieldPrefix
	 */
	public function __construct( LoadBalancer $dbLoadBalancer, $table, $fieldPrefix ) {
		Assert::parameterType( 'string', $table, '$table' );
		Assert::parameterType( 'string', $fieldPrefix, '$fieldPrefix' );

		$this->dbLoadBalancer = $dbLoadBalancer;
		$this->table = $table;
		$this->fieldPrefix = $fieldPrefix;
	}

	private function field( $name ) {
		return $this->fieldPrefix . $name;
	}

	/**
	 * @param int $internalId
	 *
	 * @throws InvalidArgumentException
	 * @return string
	 */
	public function getName( $internalId ) {
		Assert::parameterType( 'int', $internalId, '$internalId' );

		$nameField = $this->field( 'name' );
		$idField = $this->field( 'id' );

		$db = $this->dbLoadBalancer->getConnection( DB_SLAVE );
		$value = $db->selectField( $this->table, $nameField, [ $idField => $internalId ], __METHOD__ );
		$this->dbLoadBalancer->reuseConnection( $db );

		if ( $value === false ) {
			throw new UnknownNameException( 'Unmapped internal id: ' . $internalId );
		}

		return $value;
	}

	/**
	 * Returns an internal ID for the given name.
	 *
	 * @param string $name
	 *
	 * @throws InvalidArgumentException if $name is not a string or not a valid name
	 * @throws UnknownNameException if $name has not been assigned an ID
	 * @return int
	 */
	public function getInternalId( $name ) {
		Assert::parameterType( 'string', $name, '$name' );

		$nameField = $this->field( 'name' );
		$idField = $this->field( 'id' );

		$db = $this->dbLoadBalancer->getConnection( DB_SLAVE );
		$value = $db->selectField( $this->table, $idField, [ $nameField => $name ], __METHOD__ );
		$this->dbLoadBalancer->reuseConnection( $db );

		if ( $value === false ) {
			throw new UnknownNameException( 'Unknown name: ' . $name );
		}

		return $value;
	}

	/**
	 * Returns an internal ID for the given name, attempting to create a permanent
	 * mapping if none is known.
	 *
	 * @param string $name
	 *
	 * @throws InvalidArgumentException if $name is not a string or not a valid name
	 * @throws NameMappingFailedException if no mapping for $name could be created.
	 * @return int
	 */
	public function acquireInternalId( $name ) {
		Assert::parameterType( 'string', $name, '$name' );

		if ( !preg_match( '/^\w+$/', $name ) ) {
			throw new InvalidArgumentException( 'Bad name: ' . $name );
		}

		try {
			return $this->getInternalId( $name );
		} catch ( UnknownNameException $ex ) {
			return $this->makeMapping( $name );
		}
	}

	/**
	 * @param string $name
	 *
	 * @throws NameMappingFailedException
	 * @return int
	 */
	private function makeMapping( $name ) {
		$nameField = $this->field( 'name' );

		try {
			$db = $this->dbLoadBalancer->getConnection( DB_MASTER );
			$db->insert( $this->table, [ $nameField => $name ], __METHOD__ );
			$newId = $db->insertId();
			$this->dbLoadBalancer->reuseConnection( $db );

			return $newId;
		} catch ( DBError $ex ) {
			throw new NameMappingFailedException( 'Failed to create mapping for $name', 0, $ex );
		}
	}

	/**
	 * Lists all known internal IDs by name.
	 *
	 * @return array An associative array mapping names to internal IDs
	 */
	public function listInternalIds() {
		$nameField = $this->field( 'name' );
		$idField = $this->field( 'id' );

		$db = $this->dbLoadBalancer->getConnection( DB_SLAVE );
		$result = $db->select( $this->table, $idField, '', __METHOD__ );

		$ids = [];
		foreach ( $result as $row ) {
			$ids[$row->$nameField] = $row->$idField;
		}

		return $ids;
	}
}
