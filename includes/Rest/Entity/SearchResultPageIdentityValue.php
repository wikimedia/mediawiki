<?php
namespace MediaWiki\Rest\Entity;

/**
 * Lightweight value class representing a page identity
 *
 * @unstable
 * @note This class is temorary solution. It will be replaced by the one from:
 * https://phabricator.wikimedia.org/T208776
 */
class SearchResultPageIdentityValue implements SearchResultPageIdentity {

	/**
	 * @var int
	 */
	private $id = 0;

	/**
	 * @var string
	 */
	private $dbKey = '';

	/**
	 * @var int
	 */
	private $namespace = null;

	public function __construct( int $id, int $namespace, string $dbKey ) {
		$this->id = $id;
		$this->namespace = $namespace;
		$this->dbKey = $dbKey;
	}

	public function getId(): int {
		return $this->id;
	}

	public function getNamespace(): int {
		return $this->namespace;
	}

	public function getDBkey(): string {
		return $this->dbKey;
	}

}
