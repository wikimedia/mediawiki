<?php

namespace MediaWiki\Utils;

use MediaWiki\Context\IContextSource;
use MediaWiki\Registration\ExtensionRegistry;
use MediaWiki\ResourceLoader\ForeignResourceManager;
use Wikimedia\Composer\ComposerInstalled;
use Wikimedia\Rdbms\IConnectionProvider;
use Wikimedia\Timestamp\ConvertibleTimestamp;
use Wikimedia\Timestamp\TimestampFormat;
use Wikimedia\UUID\GlobalIdGenerator;

/**
 * @since 1.46
 */
class SBOMGenerator {

	public function __construct(
		private readonly IConnectionProvider $connectionProvider,
		private readonly ExtensionRegistry $extensionRegistry,
		private readonly GlobalIdGenerator $globalIdGenerator,
	) {
	}

	/**
	 * Generate a Software Bill of Materials (SBOM) for MediaWiki and all related software,
	 * libraries and extensions.
	 * @unstable
	 * @see https://cyclonedx.org/docs/1.6/json/
	 * @return array SBOM data in the CycloneDX 1.6 format
	 */
	public function generateCdxSBOM( IContextSource $context ): array {
		return [
			'$schema' => 'http://cyclonedx.org/schema/bom-1.6.schema.json',
			'bomFormat' => 'CycloneDX',
			'specVersion' => '1.6',
			'serialNumber' => 'urn:uuid:' . $this->globalIdGenerator->newUUIDv4(),
			'version' => 1,
			'metadata' => [
				'timestamp' => ConvertibleTimestamp::now( TimestampFormat::ISO_8601 ),
			],
			'components' => array_merge(
				$this->getPlatformComponents(),
				$this->getMediaWikiComponent(),
				$this->getExtensionComponents( $this->extensionRegistry->getAllThings(), $context ),
			),
		];
	}

	/**
	 * @return array Components for PHP, ICU and the Database
	 */
	private function getPlatformComponents(): array {
		$dbr = $this->connectionProvider->getReplicaDatabase();
		return [
			[
				'type' => 'platform',
				'name' => 'PHP',
				'version' => PHP_VERSION,
				'components' => $this->getPHPExtensionComponents( get_loaded_extensions() ),
			],
			[
				'type' => 'library',
				'name' => 'ICU',
				'version' => INTL_ICU_VERSION,
			],
			[
				'type' => 'platform',
				'name' => $dbr->getType(),
				'version' => $dbr->getServerVersion(),
			],
		];
	}

	/**
	 * @param array $extensions string[] A list of extensions that are loaded by PHP
	 * @return array Components for all installed PHP extensions
	 */
	private function getPHPExtensionComponents( array $extensions ): array {
		$extensions = array_filter( $extensions, static fn ( $name ) => $name !== 'Core' );
		return array_map(
			static fn ( $extension ) => [
				'type' => 'library',
				'name' => $extension,
				'version' => phpversion( $extension ),
			],
			array_values( $extensions )
		);
	}

	/**
	 * @return array{0: array} An array with a single component for MediaWiki core and its dependencies
	 */
	private function getMediaWikiComponent(): array {
		$subComponents = array_merge(
			$this->getComposerComponents( MW_INSTALL_PATH ),
			$this->getForeignResources( MW_INSTALL_PATH . '/resources/lib' ),
		);
		return [ [
			'type' => 'application',
			'name' => 'MediaWiki',
			'components' => $subComponents,
			'version' => MW_VERSION,
		] ];
	}

	/**
	 * @param array $extensions Information about all extensions and skins
	 * @param IContextSource $context
	 * @return array Components for the installed extensions and skins along with their declared foreign resources
	 */
	private function getExtensionComponents( array $extensions, IContextSource $context ): array {
		ksort( $extensions, SORT_STRING );
		$foreignResourcesDirs = $this->extensionRegistry->getAttribute( 'ForeignResourcesDir' );

		$components = [];
		foreach ( $extensions as $name => $extension ) {
			$componentData = [
				'type' => 'application',
				'name' => $name,
			];

			if ( array_key_exists( 'author', $extension ) ) {
				$authors = $extension['author'];
				$componentData['authors'] = array_map(
					static fn ( $author ) => [
						'name' => $author
					],
					is_array( $authors ) ? $authors : [ $authors ],
				);
			}

			if ( array_key_exists( 'version', $extension ) ) {
				$componentData['version'] = (string)$extension['version'];
			}

			if ( array_key_exists( 'license-name', $extension ) ) {
				$componentData['licences'] = [ $extension['license-name'] ];
			}

			if ( array_key_exists( 'url', $extension ) ) {
				$componentData['url'] = $extension['url'];
			}

			if ( array_key_exists( 'descriptionmsg', $extension ) ) {
				$componentData['description'] = $context->msg( $extension['descriptionmsg'] )->text();
			} elseif ( array_key_exists( 'description', $extension ) ) {
				$componentData['description'] = $extension['description'];
			}

			$subComponents = [];
			$path = $extension['path'] ?? null;
			if ( $path !== null ) {
				$subComponents = $this->getComposerComponents( dirname( $path ) );
			}
			$foreignResourcesDir = $foreignResourcesDirs[$name] ?? null;
			if ( $foreignResourcesDir !== null ) {
				$subComponents = array_merge(
					$subComponents,
					$this->getForeignResources( $foreignResourcesDir )
				);
			}
			if ( $subComponents ) {
				$componentData['components'] = $subComponents;
			}
			$components[] = $componentData;
		}

		return $components;
	}

	/**
	 * @return array A list of components that were parsed from a foreign-resources.yaml
	 * file in the given registry directory.
	 */
	private function getForeignResources( string $registryDir ): array {
		$foreignResourceManager = new ForeignResourceManager(
			"$registryDir/foreign-resources.yaml",
			$registryDir,
		);
		return $foreignResourceManager->generateCdxData()['components'] ?? [];
	}

	/**
	 * @return array Components for all installed composer libraries
	 */
	private function getComposerComponents( string $path ): array {
		$installedFile = $path . '/vendor/composer/installed.json';
		if ( !file_exists( $installedFile ) ) {
			return [];
		}

		$installed = new ComposerInstalled( $installedFile );

		$components = [];
		foreach ( $installed->getInstalledDependencies() as $name => $info ) {
			if ( str_starts_with( $info['type'], 'mediawiki-' ) ) {
				// Skip extensions and skins installed via Composer
				continue;
			}
			$componentData = [
				'name' => $name,
				'version' => $info['version'],
				'type' => 'library',
				'licences' => $info['licences'] ?? [],
				'authors' => $info['authors'] ?? [],
				'description' => $info['description'],
			];

			$components[] = $componentData;
		}
		return $components;
	}
}
