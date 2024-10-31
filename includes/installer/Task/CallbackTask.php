<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\Status\Status;

/**
 * An installer task which calls a callback function
 *
 * @internal For use by the installer
 */
class CallbackTask extends Task {
	/** @var string */
	private $name;
	/** @var callable */
	private $callback;
	/** @var string|string[] */
	private $dependencies;
	/** @var string|string[] */
	private $aliases;

	/**
	 * @see \MediaWiki\Installer\Task\TaskFactory::create
	 * @param array $spec
	 */
	public function __construct( $spec ) {
		$this->name = $spec['name'];
		$this->callback = $spec['callback'];
		$this->dependencies = $spec['after'] ?? [];
		$this->aliases = $spec['aliases'] ?? [];
	}

	public function getName() {
		return $this->name;
	}

	public function getAliases() {
		return $this->aliases;
	}

	public function getDependencies() {
		return $this->dependencies;
	}

	public function execute(): Status {
		return ( $this->callback )( $this->getContext() );
	}
}
