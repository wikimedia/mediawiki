<?php
namespace MediaWiki\Tests\Json;

use Psr\Container\ContainerInterface;
use Wikimedia\JsonCodec\JsonClassCodec;
use Wikimedia\JsonCodec\JsonCodecable;
use Wikimedia\JsonCodec\JsonCodecInterface;

/**
 * Managed object which uses a factory in a service.
 */
class ManagedObject implements JsonCodecable {

	/** @var string */
	public string $name;
	/** @var int */
	public int $data;

	/**
	 * Create a new ManagedObject which stores $property.  This constructor
	 * shouldn't be invoked directly by anyone except ManagedObjectFactory.
	 *
	 * @param string $name
	 * @param int $data
	 * @internal
	 */
	public function __construct( string $name, int $data ) {
		$this->name = $name;
		$this->data = $data;
	}

	// Implement JsonCodecable by delegating serialization/deserialization
	// to the 'ManagedObjectFactory' service.

	/** @inheritDoc */
	public static function jsonClassCodec(
		JsonCodecInterface $codec, ContainerInterface $serviceContainer
	): JsonClassCodec {
		return $serviceContainer->get( 'ManagedObjectFactory' );
	}
}
