<?php

// @codeCoverageIgnoreStart
require_once __DIR__ . '/Maintenance.php';
// @codeCoverageIgnoreEnd

use MediaWiki\Maintenance\Maintenance;

/**
 * Syncs the local copies in maintenance/external-schemas/ with the published schemas.
 * The copy of https://example.org/a/b.json is maintenance/external-schemas/example.org/a/b.json.
 *
 * @ingroup Maintenance
 */
class SyncExternalSchemas extends Maintenance {

	private string $dir = MW_INSTALL_PATH . '/maintenance/external-schemas';

	public function __construct() {
		parent::__construct();
		$this->addDescription( 'Sync the local copies in maintenance/external-schemas/ with the published schemas' );
		$this->addOption( 'check', 'Only list the outdated copies, failing if there are any' );
		$this->addArg( 'url',
			'https:// URL of a schema to add or update (default: all the local copies)', false, true );
	}

	public function execute() {
		$http = $this->getServiceContainer()->getHttpRequestFactory();
		$check = $this->hasOption( 'check' );
		$outdated = false;

		foreach ( $this->getArgs() ?: $this->findCopies() as $url ) {
			if ( !str_starts_with( $url, 'https://' ) ) {
				$this->fatalError( "Not an https:// URL: $url" );
			}
			if ( !self::isSchema( $url ) ) {
				$this->fatalError( "Not a .json or .yaml URL: $url" );
			}
			$published = $http->get( $url, [], __METHOD__ );
			if ( $published === null ) {
				$this->fatalError( "Failed to download $url" );
			}
			$file = $this->dir . '/' . substr( $url, strlen( 'https://' ) );
			if ( is_file( $file ) && file_get_contents( $file ) === $published ) {
				continue;
			}
			$outdated = true;
			if ( !$check ) {
				wfMkdirParents( dirname( $file ) );
				file_put_contents( $file, $published );
			}
			$this->output( ( $check ? 'Outdated' : 'Updated' ) . " $url\n" );
		}

		if ( !$outdated ) {
			$this->output( "All local copies are up to date\n" );
		} elseif ( $check ) {
			$this->fatalError( 'Run syncExternalSchemas without --check to update them' );
		}
	}

	/** @return string[] */
	private function findCopies(): array {
		$urls = [];
		$dir = new RecursiveDirectoryIterator( $this->dir, FilesystemIterator::SKIP_DOTS );
		foreach ( new RecursiveIteratorIterator( $dir ) as $file ) {
			$path = $file->getPathname();
			// Skip the README and anything else that isn't a schema, e.g. editor backups
			if ( self::isSchema( $path ) ) {
				$urls[] = 'https://' . substr( $path, strlen( $this->dir ) + 1 );
			}
		}
		return $urls;
	}

	private static function isSchema( string $path ): bool {
		return str_ends_with( $path, '.json' ) || str_ends_with( $path, '.yaml' );
	}
}

// @codeCoverageIgnoreStart
$maintClass = SyncExternalSchemas::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
