<?php

namespace MediaWiki\Rest\Handler;

use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\DOM\Document;

/**
 * @unstable
 */
class HTMLTransformInput {
	/** @var array */
	private $original = [];

	/** @var array */
	private $opts = [];

	/** @var ?int */
	private $oldid = null;

	/** @var Document */
	private $doc;

	/** @var ?array */
	private $modifiedDataMW;

	/** @var string */
	private $inputFormat;

	/**
	 * @param Document $doc
	 */
	public function __construct( Document $doc ) {
		$this->doc = $doc;
	}

	public function setOptions( array $options ) {
		$this->opts = $options;
	}

	/**
	 * @param string $format Either ParsoidFormatHelper::FORMAT_HTML
	 *        or ParsoidFormatHelper::FORMAT_PAGEBUNDLE.
	 */
	public function setInputFormat( string $format ) {
		$this->inputFormat = $format;
	}

	/**
	 * @param array $dataMW
	 */
	public function setModifiedDataMW( array $dataMW ): void {
		$this->modifiedDataMW = $dataMW;
	}

	/**
	 * @param int $oldid
	 */
	public function setOriginalRevisionId( int $oldid ): void {
		$this->oldid = $oldid;
	}

	/**
	 * @param array $originalData
	 *
	 * @return void
	 */
	public function setOriginalData( array $originalData ) {
		$this->original = $originalData;
	}

	public function getModifiedDocument(): Document {
		return $this->doc;
	}

	public function hasOriginalHtml(): bool {
		return isset( $this->original['html'] );
	}

	public function getOriginalHtml(): string {
		return $this->original['html']['body'] ?? '';
	}

	public function getOriginalSchemaVersion(): string {
		// TODO: Cache this in a field to avoid parsing multiple times.
		$vOriginal = ParsoidFormatHelper::parseContentTypeHeader(
			$this->original['html']['headers']['content-type'] ?? ''
		);

		if ( $vOriginal === null ) {
			throw new ClientError(
				'Content-type of original html is missing.'
			);
		}

		return $vOriginal;
	}

	public function inputIsPageBundle(): bool {
		return $this->inputFormat === ParsoidFormatHelper::FORMAT_PAGEBUNDLE;
	}

	public function getOriginalPageBundle(): PageBundle {
		$origPb = new PageBundle(
			$this->original['html']['body'] ?? '',
			$this->original['data-parsoid']['body'] ?? null,
			$this->original['data-mw']['body'] ?? null
		);

		$errorMessage = '';
		if ( !$origPb->validate( $this->getOriginalSchemaVersion(), $errorMessage ) ) {
			throw new ClientError( $errorMessage );
		}

		return $origPb;
	}

	public function getModifiedPageBundle( string $schemaVersion ): PageBundle {
		// XXX: Ideally, we should have a getter for schemaVersion. But unfortunately,
		//      we need access to the DOM document to determine it.
		$pb = new PageBundle(
			'',
			[ 'ids' => [] ],  // So it validates
			$this->modifiedDataMW ?? null
		);

		$errorMessage = '';
		if ( !$pb->validate( $schemaVersion, $errorMessage ) ) {
			throw new ClientError( $errorMessage );
		}

		return $pb;
	}

	public function hasModifiedDataMW(): bool {
		return $this->modifiedDataMW !== null;
	}

	public function getOriginalRevisionId(): ?int {
		return $this->oldid;
	}

	public function getContentModel(): ?string {
		return $this->opts['contentmodel'] ?? null;
	}

	public function getEnvironmentOffsetType(): string {
		return $this->opts['offsetType'] ?? 'byte';
	}

}
