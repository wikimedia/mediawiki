<?php

use MediaWiki\Json\FormatJson;
use MediaWiki\MainConfigNames;
use MediaWiki\Maintenance\Maintenance;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/../Maintenance.php';
// @codeCoverageIgnoreEnd

class ImportExtensionMessages extends Maintenance {
	/** @var string */
	private $extensionDir;
	/** @var string */
	private $extName;
	/** @var string[] */
	private $excludedMsgs;
	/** @var string */
	private $outDir;
	/** @var string[] */
	private $coreDataCache;

	public function __construct() {
		parent::__construct();
		$this->addArg( 'extension', 'The extension name' );
		$this->addOption( 'outdir',
			'The output directory, default $IP/languages/i18n', false, true );
	}

	public function execute() {
		$this->init();

		$this->outDir = $this->getOption( 'outdir', MW_INSTALL_PATH . '/languages/i18n' );
		if ( !file_exists( $this->outDir ) ) {
			mkdir( $this->outDir, 0777, true );
		}

		$this->extName = $this->getArg();
		$extJsonPath = $this->extensionDir . "/{$this->extName}/extension.json";
		$extJson = file_get_contents( $extJsonPath );
		if ( $extJson === false ) {
			$this->fatalError( "Unable to open \"$extJsonPath\"" );
		}
		$extData = json_decode( $extJson, JSON_THROW_ON_ERROR );

		$this->excludedMsgs = [];
		foreach ( [ 'namemsg', 'descriptionmsg' ] as $key ) {
			if ( isset( $extData[$key] ) ) {
				$this->excludedMsgs[] = $extData[$key];
			}
		}

		foreach ( $this->getMessagesDirs( $extData ) as $dir ) {
			$this->processDir( $dir );
		}
	}

	private function init() {
		$services = $this->getServiceContainer();
		$config = $services->getMainConfig();
		$this->extensionDir = $config->get( MainConfigNames::ExtensionDirectory );
	}

	private function getMessagesDirs( array $extData ): array {
		if ( isset( $extData['MessagesDirs'] ) ) {
			$messagesDirs = [];
			foreach ( $extData['MessagesDirs'] as $dirs ) {
				if ( is_array( $dirs ) ) {
					foreach ( $dirs as $dir ) {
						$messagesDirs[] = $dir;
					}
				} else {
					$messagesDirs[] = $dirs;
				}
			}
		} else {
			$messagesDirs = [ 'i18n' ];
		}
		return $messagesDirs;
	}

	private function processDir( string $dir ) {
		$path = $this->extensionDir . "/{$this->extName}/$dir";

		foreach ( new DirectoryIterator( $path ) as $file ) {
			if ( !$file->isDot() && str_ends_with( $file->getFilename(), '.json' ) ) {
				$this->processFile(
					substr( $file->getFilename(), 0, -5 ),
					$file->getPathname()
				);
			}
		}
	}

	private function processFile( string $lang, string $extI18nPath ) {
		$extJson = file_get_contents( $extI18nPath );
		if ( $extJson === false ) {
			$this->error( "Unable to read i18n file \"$extI18nPath\"" );
			return;
		}
		$extData = json_decode( $extJson, JSON_THROW_ON_ERROR );
		$coreData = $this->getCoreData( $lang );

		if ( isset( $extData['@metadata']['authors'] ) ) {
			$authors = array_unique( array_merge(
				$coreData['@metadata']['authors'] ?? [],
				$extData['@metadata']['authors']
			) );
			// Fix numeric authors
			foreach ( $authors as &$author ) {
				$author = (string)$author;
			}
			sort( $authors );
			$coreData['@metadata']['authors'] = $authors;
		}

		foreach ( $extData as $name => $value ) {
			if ( str_starts_with( $name, '@' ) ) {
				continue;
			}
			if ( in_array( $name, $this->excludedMsgs ) ) {
				continue;
			}
			$coreData[$name] = $value;
		}

		$this->setCoreData( $lang, $coreData );
	}

	/** @return mixed */
	private function getCoreData( string $lang ) {
		if ( !isset( $this->coreDataCache[$lang] ) ) {
			$corePath = MW_INSTALL_PATH . "/languages/i18n/$lang.json";
			// phpcs:ignore Generic.PHP.NoSilencedErrors.Discouraged
			$coreJson = @file_get_contents( $corePath );
			if ( $coreJson === false ) {
				$this->error( "Warning: discarding extension localisation " .
					"for language \"$lang\" not present in core" );
				// Do not write to coreDataCache -- suppress creation of the core file.
				return [];
			}
			$this->coreDataCache[$lang] = json_decode( $coreJson, JSON_THROW_ON_ERROR );
		}
		return $this->coreDataCache[$lang];
	}

	/**
	 * @param string $lang
	 * @param mixed $data
	 */
	private function setCoreData( string $lang, $data ) {
		if ( !isset( $this->coreDataCache[$lang] ) ) {
			// Non-existent file, do not create
			return;
		}

		$this->coreDataCache[$lang] = $data;
		$outPath = "{$this->outDir}/$lang.json";
		if ( !file_put_contents(
			$outPath,
			FormatJson::encode( $data, "\t", FormatJson::ALL_OK ) . "\n"
		) ) {
			$this->error( "Unable to write core i18n file \"$outPath\"" );
		}
	}
}

// @codeCoverageIgnoreStart
$maintClass = ImportExtensionMessages::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
