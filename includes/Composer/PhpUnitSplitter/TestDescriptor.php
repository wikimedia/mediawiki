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
	private float $duration;

	public function __construct(
		string $className,
		array $namespace,
		?string $filename = null,
		float $duration = 0
	) {
		$this->className = $className;
		$this->namespace = $namespace;
		$this->filename = $filename;
		$this->duration = $duration;
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

	public function getDuration(): float {
		return $this->duration;
	}

}
