<?php

namespace MediaWiki\Installer\Task;

use MediaWiki\Status\Status;
use Wikimedia\Message\MessageSpecifier;

/**
 * An installer task which calls a callback function
 *
 * @internal For use by the installer
 */
class CallbackTask extends Task {
	/** @var string */
	private $name;
	/** @var MessageSpecifier|string|null */
	private $description;
	/** @var callable */
	private $callback;
	/** @var string|string[] */
	private $dependencies;
	/** @var string|string[] */
	private $aliases;
	/** @var bool */
	private $postInstall;

	/**
	 * @see \MediaWiki\Installer\Task\TaskFactory::create
	 * @param array $spec
	 */
	public function __construct( $spec ) {
		$this->name = $spec['name'];
		$this->description = $spec['description'] ?? null;
		$this->callback = $spec['callback'];
		$this->dependencies = $spec['after'] ?? [];
		$this->aliases = $spec['aliases'] ?? [];
		$this->postInstall = $spec['postInstall'] ?? false;
	}

	/** @inheritDoc */
	public function getName() {
		return $this->name;
	}

	/** @inheritDoc */
	public function getDescription() {
		if ( $this->description !== null ) {
			return $this->description;
		}
		return parent::getDescription();
	}

	/** @inheritDoc */
	public function getAliases() {
		return $this->aliases;
	}

	/** @inheritDoc */
	public function getDependencies() {
		return $this->dependencies;
	}

	/** @inheritDoc */
	public function isPostInstall() {
		return $this->postInstall;
	}

	public function execute(): Status {
		return ( $this->callback )( $this->getContext() );
	}
}
