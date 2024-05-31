<?php

declare( strict_types = 1 );

namespace MediaWiki\Composer\PhpUnitSplitter;

/**
 * @license GPL-2.0-or-later
 */
class TestDescriptor {

	private string $className;
	private array $namespace;
	private ?string $filename;

	public function __construct( string $className, array $namespace, ?string $filename = null ) {
		$this->className = $className;
		$this->namespace = $namespace;
		$this->filename = $filename;
	}

	public function getNamespace(): array {
		return $this->namespace;
	}

	public function getClassName(): string {
		return $this->className;
	}

	public function setFilename( string $filename ): void {
		$this->filename = $filename;
	}

	public function getFilename(): ?string {
		return $this->filename;
	}

	public function getFullClassname(): string {
		return implode( '\\', $this->namespace ) . '\\' . $this->className;
	}

	public function getDuration() {
		return 0;
	}

}
