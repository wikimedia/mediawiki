<?php

namespace MediaWiki\Rest\Module;

/**
 * Encapsulates resolved information about a REST module.
 *
 * @since 1.47
 */
class ModuleInfo {
	private string $id;
	private ModuleMode $availability;
	private bool $isExternal;
	private ?string $title;
	private ?string $description;
	private ?string $version;
	private ?string $localSpecPath;
	private array $groups;
	private ?string $externalBaseUrl;
	private ?string $externalSpecUrl;

	/**
	 * @param string $id The module ID (e.g., 'site/v1' or '' for prefix-less)
	 * @param ModuleMode $availability The module availability
	 * @param bool $isExternal Whether the module is external or local
	 * @param ?string $title The display name of the module
	 * @param ?string $description The description of the module
	 * @param ?string $version The version string
	 * @param ?string $oadSpecPath Module-relative OAD spec path (e.g. '/openapi.json'), or, null
	 * @param string[] $groups The audience designation groups
	 * @param ?string $externalBaseUrl The external base URL, or null for local modules
	 * @param ?string $externalSpecUrl The external spec URL, or null for local modules
	 */
	public function __construct(
		string $id,
		ModuleMode $availability,
		bool $isExternal,
		?string $title,
		?string $description,
		?string $version,
		?string $oadSpecPath,
		array $groups,
		?string $externalBaseUrl = null,
		?string $externalSpecUrl = null
	) {
		$this->id = $id;
		$this->availability = $availability;
		$this->isExternal = $isExternal;
		$this->title = $title;
		$this->description = $description;
		$this->version = $version;
		$this->localSpecPath = $oadSpecPath;
		$this->groups = $groups;
		$this->externalBaseUrl = $externalBaseUrl;
		$this->externalSpecUrl = $externalSpecUrl;
	}

	public function getId(): string {
		return $this->id;
	}

	public function getAvailability(): ModuleMode {
		return $this->availability;
	}

	public function isExternal(): bool {
		return $this->isExternal;
	}

	public function getTitle(): ?string {
		return $this->title;
	}

	public function getDescription(): ?string {
		return $this->description;
	}

	public function getVersion(): ?string {
		return $this->version;
	}

	/**
	 * Gets the relative path to the module's OpenAPI OAD spec.
	 *
	 * Null for external modules or local modules using the default generated description.
	 *
	 * @return string|null
	 */
	public function getLocalDescriptionSpecPath(): ?string {
		return $this->localSpecPath;
	}

	/**
	 * @return string[]
	 */
	public function getGroups(): array {
		return $this->groups;
	}

	/**
	 * Gets the configured base URL for an external module.
	 *
	 * Null for local modules.
	 *
	 * @return string|null
	 */
	public function getExternalBaseUrl(): ?string {
		return $this->externalBaseUrl;
	}

	/**
	 * Gets the configured spec URL for an external module.
	 *
	 * Null for local modules.
	 *
	 * @return string|null
	 */
	public function getExternalSpecUrl(): ?string {
		return $this->externalSpecUrl;
	}
}
