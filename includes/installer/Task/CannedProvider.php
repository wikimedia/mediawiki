<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\Status\Status;

/**
 * A scheduled provider which simply provides data given to it through its
 * constructor.
 *
 * The point of a scheduled provider is to defer data construction, which this
 * isn't doing. This is instead used to replace the scheduled provider in the
 * regular installer, instead providing data that's available from the start in
 * installPreConfigured.php.
 *
 * @internal
 */
class CannedProvider extends Task {
	/** @var string */
	private $name;
	/** @var array */
	private $provisions;

	/**
	 * @param string $name
	 * @param array $provisions
	 */
	public function __construct( $name, $provisions ) {
		$this->name = $name;
		$this->provisions = $provisions;
	}

	/** @inheritDoc */
	public function getName() {
		return $this->name;
	}

	/** @inheritDoc */
	public function getProvidedNames() {
		return array_keys( $this->provisions );
	}

	public function execute(): Status {
		foreach ( $this->provisions as $name => $value ) {
			$this->getContext()->provide( $name, $value );
		}
		return Status::newGood();
	}
}
