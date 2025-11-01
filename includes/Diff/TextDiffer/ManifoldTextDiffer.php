<?php

namespace MediaWiki\Diff\TextDiffer;

use DomainException;
use InvalidArgumentException;
use MediaWiki\Language\Language;
use MediaWiki\Output\OutputPage;
use MessageLocalizer;
use UnexpectedValueException;

/**
 * A TextDiffer which acts as a container for other TextDiffers, and dispatches
 * requests to them.
 *
 * @since 1.41
 */
class ManifoldTextDiffer implements TextDiffer {
	/** @var MessageLocalizer */
	private $localizer;
	/** @var Language|null */
	private $contentLanguage;
	/** @var string|null */
	private $diffEngine;
	/** @var string|false|null */
	private $externalPath;
	/** @var TextDiffer[]|null Differs in order of priority, from highest to lowest */
	private $differs;
	/** @var TextDiffer[]|null The differ to use for each format */
	private $differsByFormat;
	/** @var array */
	private $wikidiff2Options;

	/**
	 * @internal For use by DifferenceEngine, ContentHandler
	 *
	 * @param MessageLocalizer $localizer
	 * @param Language|null $contentLanguage
	 * @param string|null $diffEngine The DiffEngine config variable
	 * @param string|false|null $externalPath The ExternalDiffEngine config variable
	 * @param array $wikidiff2Options The Wikidiff2Options config variable
	 */
	public function __construct(
		MessageLocalizer $localizer,
		?Language $contentLanguage,
		$diffEngine,
		$externalPath,
		$wikidiff2Options
	) {
		$this->localizer = $localizer;
		$this->contentLanguage = $contentLanguage;
		$this->diffEngine = $diffEngine;
		$this->externalPath = $externalPath;
		$this->wikidiff2Options = $wikidiff2Options;
	}

	public function getName(): string {
		return 'manifold';
	}

	public function getFormats(): array {
		$differs = $this->getDiffersByFormat();
		return array_keys( $differs );
	}

	public function hasFormat( string $format ): bool {
		$differs = $this->getDiffersByFormat();
		return isset( $differs[$format] );
	}

	public function render( string $oldText, string $newText, string $format ): string {
		if ( !in_array( $format, $this->getFormats(), true ) ) {
			throw new InvalidArgumentException(
				'The requested format is not supported by this engine' );
		}
		$results = $this->renderBatch( $oldText, $newText, [ $format ] );
		return reset( $results );
	}

	public function renderBatch( string $oldText, string $newText, array $formats ): array {
		$result = [];
		$differs = $this->splitBatchByDiffer( $formats );
		/** @var TextDiffer $differ */
		foreach ( $differs as [ $differ, $formatBatch ] ) {
			$result += $differ->renderBatch( $oldText, $newText, $formatBatch );
		}
		return $result;
	}

	/** @inheritDoc */
	public function getFormatContext( string $format ) {
		return $this->getDifferForFormat( $format )->getFormatContext( $format );
	}

	public function addRowWrapper( string $format, string $diffText ): string {
		return $this->getDifferForFormat( $format )->addRowWrapper( $format, $diffText );
	}

	public function addModules( OutputPage $out, string $format ): void {
		$this->getDifferForFormat( $format )->addModules( $out, $format );
	}

	public function getCacheKeys( array $formats ): array {
		$keys = [];
		$engines = [];
		$differs = $this->splitBatchByDiffer( $formats );
		/** @var TextDiffer $differ */
		foreach ( $differs as [ $differ, $formatBatch ] ) {
			$keys += $differ->getCacheKeys( $formatBatch );
			$engines[] = $differ->getName() . '=' . implode( ',', $formatBatch );
		}
		$keys['10-formats-and-engines'] = implode( ';', $engines );
		return $keys;
	}

	public function localize( string $format, string $diff, array $options = [] ): string {
		return $this->getDifferForFormat( $format )->localize( $format, $diff, $options );
	}

	public function getTablePrefixes( string $format ): array {
		return $this->getDifferForFormat( $format )->getTablePrefixes( $format );
	}

	public function getPreferredFormatBatch( string $format ): array {
		return $this->getDifferForFormat( $format )->getPreferredFormatBatch( $format );
	}

	/**
	 * @return TextDiffer[]
	 */
	private function getDiffersByFormat() {
		if ( $this->differsByFormat === null ) {
			$differs = [];
			foreach ( $this->getDiffers() as $differ ) {
				foreach ( $differ->getFormats() as $format ) {
					// getDiffers() is in order of priority -- don't overwrite
					$differs[$format] ??= $differ;
				}
			}
			$this->differsByFormat = $differs;
		}
		return $this->differsByFormat;
	}

	/**
	 * @param string $format
	 * @return TextDiffer
	 */
	private function getDifferForFormat( $format ) {
		$differs = $this->getDiffersByFormat();
		if ( !isset( $differs[$format] ) ) {
			throw new InvalidArgumentException(
				"Unknown format \"$format\""
			);
		}
		return $differs[$format];
	}

	/**
	 * Disable text differs apart from the one with the given name.
	 */
	public function setEngine( string $name ) {
		$this->diffEngine = $name;
		$this->differs = null;
		$this->differsByFormat = null;
	}

	/**
	 * Get the text differ name which will be used for the specified format
	 *
	 * @param string $format
	 * @return string|null
	 */
	public function getEngineForFormat( string $format ) {
		return $this->getDifferForFormat( $format )->getName();
	}

	/**
	 * Get differs in a numerically indexed array. When a format is requested,
	 * the first TextDiffer in this array which can handle the format will be
	 * used.
	 *
	 * @return TextDiffer[]
	 */
	private function getDiffers() {
		if ( $this->differs === null ) {
			$differs = [];
			if ( $this->diffEngine === null ) {
				$differNames = [ 'external', 'wikidiff2', 'php' ];
			} else {
				$differNames = [ $this->diffEngine ];
			}
			$failureReason = '';
			foreach ( $differNames as $name ) {
				$differ = $this->maybeCreateDiffer( $name, $failureReason );
				if ( $differ ) {
					$this->injectDeps( $differ );
					$differs[] = $differ;
				}
			}
			if ( !$differs ) {
				throw new UnexpectedValueException(
					"Cannot use diff engine '{$this->diffEngine}': $failureReason" );
			}
			// TODO: add a hook here, allowing extensions to add differs
			$this->differs = $differs;
		}
		return $this->differs;
	}

	/**
	 * Initialize an object which may be a subclass of BaseTextDiffer, passing
	 * down injected dependencies.
	 */
	public function injectDeps( TextDiffer $differ ) {
		if ( $differ instanceof BaseTextDiffer ) {
			$differ->setLocalizer( $this->localizer );
		}
	}

	/**
	 * Create a TextDiffer by engine name. If it can't be created due to a
	 * configuration or platform issue, return null and set $failureReason.
	 *
	 * @param string $engine
	 * @param string &$failureReason Out param which will be set to the failure reason
	 * @return TextDiffer|null
	 */
	private function maybeCreateDiffer( $engine, &$failureReason ) {
		switch ( $engine ) {
			case 'external':
				if ( is_string( $this->externalPath ) ) {
					if ( is_executable( $this->externalPath ) ) {
						return new ExternalTextDiffer(
							$this->externalPath
						);
					}
					$failureReason = 'ExternalDiffEngine config points to a non-executable';
				} elseif ( $this->externalPath ) {
					$failureReason = 'ExternalDiffEngine config is set to a non-string value';
				} else {
					return null;
				}
				wfWarn( "$failureReason, ignoring" );
				return null;

			case 'wikidiff2':
				if ( Wikidiff2TextDiffer::isInstalled() ) {
					return new Wikidiff2TextDiffer(
						$this->wikidiff2Options
					);
				}
				$failureReason = 'wikidiff2 is not available';
				return null;

			case 'php':
				// Always available.
				return new PhpTextDiffer(
					$this->contentLanguage
				);

			default:
				throw new DomainException( 'Invalid value for $wgDiffEngine: ' . $engine );
		}
	}

	/**
	 * Given an array of formats, break it down by the TextDiffer object which
	 * will handle each format. Each element of the result array is a list in
	 * which the first element is the TextDiffer object, and the second element
	 * is the list of formats which the TextDiffer will handle.
	 *
	 * @param array $formats
	 * @return array|array{0:TextDiffer,1:string[]}
	 */
	private function splitBatchByDiffer( $formats ) {
		$result = [];
		foreach ( $formats as $format ) {
			$differ = $this->getDifferForFormat( $format );
			$name = $differ->getName();
			if ( isset( $result[$name] ) ) {
				$result[$name][1][] = $format;
			} else {
				$result[$name] = [ $differ, [ $format ] ];
			}
		}
		return array_values( $result );
	}
}
