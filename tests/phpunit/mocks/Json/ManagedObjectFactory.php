<?php
namespace MediaWiki\Tests\Mocks\Json;

use Wikimedia\JsonCodec\JsonClassCodec;

/**
 * Managed object factory which also handles serialization/deserialization
 * of the objects it manages.
 *
 * @implements JsonClassCodec<ManagedObject>
 */
class ManagedObjectFactory implements JsonClassCodec {
	/** @var array<string,ManagedObject> Fake database */
	private $storage = [];

	/**
	 * Create and store an object with $name and $value in the database.
	 * @param string $name
	 * @param int $value
	 * @return ManagedObject
	 */
	public function create( string $name, int $value ) {
		if ( isset( $this->storage[$name] ) ) {
			throw new \Error( "duplicate name" );
		}
		$this->storage[$name] = $o = new ManagedObject( $name, $value );
		return $o;
	}

	/**
	 * Lookup $name in the database.
	 * @param string $name
	 * @return ManagedObject
	 */
	public function lookup( string $name ): ManagedObject {
		if ( !isset( $this->storage[$name] ) ) {
			throw new \Error( "not found" );
		}
		return $this->storage[$name];
	}

	/** @inheritDoc */
	public function toJsonArray( $obj ): array {
		'@phan-var ManagedObject $obj';
		// Not necessary to serialize all the properties, since they
		// will be reloaded from the "database" during deserialization
		return [ 'name' => $obj->name ];
	}

	/** @inheritDoc */
	public function newFromJsonArray( string $className, array $json ): ManagedObject {
		// @phan-suppress-next-line PhanTypeMismatchReturn template limitations
		return $this->lookup( $json['name'] );
	}

	/** @inheritDoc */
	public function jsonClassHintFor( string $className, string $key ): ?string {
		// no hints
		return null;
	}
}
