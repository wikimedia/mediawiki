<?php

trait SafeSerializable {

	private $serializationId;

	public function __construct() {
		$this->serializationId = $this->getSerializationId();
	}

	public function __wakeup() {
		if ( $this->serializationId !== $this->getSerializationId() ) {
			throw new SerializationChangedException( static::class );
		}
	}

	public function getSerializationId() {
		static $cachedId;

		if ( $cachedId === null ) {
			$this->serializationId = $cachedId = $this->generateSerializationId();
		}

		return $this->serializationId;
	}

	private function generateSerializationId() {
		$className = static::class;
		$classNameLen = strlen( $className );

		// Create a blank copy of the current class
		$serialization = "O:$classNameLen:\"$className\":0:{}";

		$serialization = serialize( unserialize( $serialization ) );

		return md5( $serialization );
	}
}
