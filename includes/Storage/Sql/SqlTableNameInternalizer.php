<?php

namespace MediaWiki\Storage;

use InvalidArgumentException;
use LoadBalancer;
use Wikimedia\Assert\Assert;

/**
 * NameInternalizer implemented based on a tale in a SQL based DBMS.
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class SqlTableNameInternalizer implements NameInternalizer {

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
	private $prefix;

	/**
	 * RevisionContentStore constructor.
	 *
	 * @param LoadBalancer $dbLoadBalancer
	 * @param string $table
	 * @param string $prefix
	 */
	public function __construct( LoadBalancer $dbLoadBalancer, $table, $prefix ) {
		Assert::parameterType( 'string', $table, '$table' );
		Assert::parameterType( 'string', $prefix, '$prefix' );

		$this->dbLoadBalancer = $dbLoadBalancer;
		$this->table = $table;
		$this->prefix = $prefix;
	}

	private function field( $name ) {
		return $this->prefix . $name;
	}

	/**
	 * @param int $internalId
	 *
	 * @throws InvalidArgumentException
	 * @return string
	 */
	public function getName( $internalId ) {
		$nameField = $this->field( 'name' );
		$idField = $this->field( 'id' );

		$db = $this->dbLoadBalancer->getConnection( DB_SLAVE );
		$value = $db->selectField( $this->table, $nameField, [ $idField => $internalId ], __METHOD__ );
		$this->dbLoadBalancer->reuseConnection( $db );

		if ( $value === false ) {
			throw new InvalidArgumentException( 'Unmapped internal id in table '
				. $this->table . ': ' . $internalId );
		}

		return $value;
	}

	/**
	 * Returns an internal ID for the given name.
	 *
	 * @note Implementations must make a best effort to create a persistent mapping if none
	 * exists. Only if creating such a mapping fails should this method throw an exception.
	 *
	 * @param string $name
	 *
	 * @throws InvalidArgumentException
	 * @return int
	 */
	public function getInternalId( $name ) {
		$nameField = $this->field( 'name' );
		$idField = $this->field( 'id' );

		$db = $this->dbLoadBalancer->getConnection( DB_SLAVE );
		$value = $db->selectField( $this->table, $idField, [ $nameField => $name ], __METHOD__ );
		$this->dbLoadBalancer->reuseConnection( $db );

		if ( $value === false ) {
			$value = $this->makeMapping( $name );
		}

		return $value;
	}

	private function makeMapping( $name ) {
		$nameField = $this->field( 'name' );

		$db = $this->dbLoadBalancer->getConnection( DB_SLAVE );
		$db->insert( $this->table, [ $nameField => $name ], __METHOD__ );
		$newId = $db->insertId();
		$this->dbLoadBalancer->reuseConnection( $db );

		return $newId;
	}

}
