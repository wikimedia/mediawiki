<?php

namespace MediaWiki\Rest\Handler;

use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use Symfony\Component\Console\Exception\LogicException;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Parsoid\Utils\DOMCompat;
use Wikimedia\Parsoid\Utils\DOMUtils;
use Wikimedia\Parsoid\Utils\Timing;

/**
 * @unstable
 */
class HTMLTransformInput {
	/** @var array */
	private $original = [];

	/** @var array */
	private $options = [];

	/** @var ?int */
	private $oldid = null;

	/** @var ?Document */
	private $doc = null;

	/** @var ?array */
	private $modifiedDataMW = null;

	/** @var string */
	private $inputFormat;

	/** @var ?Element */
	private $originalBody = null;

	/** @var ?StatsdDataFactoryInterface A statistics aggregator */
	protected $metrics = null;

	/** @var ?PageBundle */
	private $originalPageBundle = null;

	/** @var string */
	private $html;

	/**
	 * @param string $html
	 */
	public function __construct( string $html ) {
		$this->html = $html;
	}

	/**
	 * @param StatsdDataFactoryInterface $metrics
	 */
	public function setMetrics( StatsdDataFactoryInterface $metrics ): void {
		$this->metrics = $metrics;
	}

	private function startTiming(): Timing {
		return Timing::start( $this->metrics );
	}

	private function incrementMetrics( string $key ) {
		if ( $this->metrics ) {
			$this->metrics->increment( $key );
		}
	}

	public function setOptions( array $options ) {
		$this->options = $options;
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

		// Sometimes we can have [ 'original' => [ 'wikitext' => '...' ] ]
		// as input data, so just don't try to construct a page bundle if
		// we don't have HTML.
		if ( !isset( $this->original['html'] ) ) {
			return;
		}

		$vOriginal = ParsoidFormatHelper::parseContentTypeHeader(
			$originalData['html']['headers']['content-type'] ?? ''
		);

		if ( $vOriginal === null ) {
			throw new ClientError(
				'Content-type of original html is missing.'
			);
		}

		$this->originalPageBundle = new PageBundle(
			$this->original['html']['body'] ?? '',
			$this->original['data-parsoid']['body'] ?? null,
			$this->original['data-mw']['body'] ?? null,
			$vOriginal
		);

		$errorMessage = '';
		if ( !$this->originalPageBundle->validate( $vOriginal, $errorMessage ) ) {
			throw new ClientError( $errorMessage );
		}
	}

	/**
	 * The size of the modified HTML in bytes.
	 *
	 * @return int
	 */
	public function getModifiedHtmlSize(): int {
		return strlen( $this->html );
	}

	public function getModifiedDocument(): Document {
		if ( !$this->doc ) {
			$this->doc = $this->parseHTML( $this->html );
		}

		return $this->doc;
	}

	/**
	 * NOTE: The return value of this method depends on
	 *    setOriginalData() having been called first.
	 *
	 * @return bool
	 */
	public function hasOriginalHtml(): bool {
		return isset( $this->originalPageBundle->html ) &&
			$this->originalPageBundle->html !== '';
	}

	/**
	 * NOTE: The return value of this method depends on
	 *    setOriginalData() having been called first.
	 *
	 * @return string
	 */
	public function getOriginalHtml(): string {
		return $this->originalPageBundle->html;
	}

	/**
	 * @param string $html
	 * @param bool $validateXMLNames
	 *
	 * @return Document
	 * @throws ClientError
	 */
	protected function parseHTML( string $html, bool $validateXMLNames = false ): Document {
		return DOMUtils::parseHTML( $html, true );
	}

	/**
	 * NOTE: The return value of this method depends on
	 *    setOriginalData() having been called first.
	 *
	 * @return Element|null
	 * @throws ClientError
	 */
	public function getOriginalBody(): ?Element {
		if ( !$this->originalBody ) {
			$html = $this->getOriginalHtml();
			$this->originalBody = DOMCompat::getBody( $this->parseHTML( $html ) );
		}

		return $this->originalBody;
	}

	public function getOriginalSchemaVersion(): ?string {
		return $this->originalPageBundle ? $this->originalPageBundle->version : null;
	}

	public function inputIsPageBundle(): bool {
		return $this->inputFormat === ParsoidFormatHelper::FORMAT_PAGEBUNDLE;
	}

	public function getOriginalPageBundle(): PageBundle {
		return $this->originalPageBundle;
	}

	/**
	 * NOTE: The return value of this method depends on
	 *    setOriginalData() having been called first.
	 *
	 * @return string
	 */
	public function getSchemaVersion(): string {
		// Get the content version of the edited doc, if available
		$inputContentVersion = DOMUtils::extractInlinedContentVersion(
			$this->getModifiedDocument()
		);

		if ( !$inputContentVersion ) {
			$inputContentVersion = $this->getOriginalSchemaVersion();
			$this->incrementMetrics( 'html2wt.original.version.notinline' );
		}

		if ( !$inputContentVersion ) {
			$inputContentVersion = Parsoid::defaultHTMLVersion();
		}

		return $inputContentVersion;
	}

	public function getModifiedPageBundle(): PageBundle {
		$pb = new PageBundle(
			'',
			[ 'ids' => [] ],  // So it validates
			$this->modifiedDataMW ?? null
		);

		$errorMessage = '';

		if ( !$pb->validate( $this->getSchemaVersion(), $errorMessage ) ) {
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
		return $this->options['contentmodel'] ?? null;
	}

	public function getOffsetType(): string {
		return $this->options['offsetType'];
	}

	public function downgradeOriginalData( string $targetSchemaVersion ) {
		$vOriginal = $this->getOriginalSchemaVersion();

		if ( $vOriginal === null ) {
			throw new LogicException( 'This can only be called after setOriginalData()' );
		}

		if ( $targetSchemaVersion === $vOriginal ) {
			// nothing to do.
			return;
		}

		// We need to downgrade the original to match the edited doc's version.
		$downgrade = Parsoid::findDowngrade( $vOriginal, $targetSchemaVersion );

		// Downgrades are only for pagebundle
		if ( $downgrade && $this->inputIsPageBundle() ) {
			$this->incrementMetrics(
				"downgrade.from.{$downgrade['from']}.to.{$downgrade['to']}"
			);
			$downgradeTiming = $this->startTiming();
			Parsoid::downgrade( $downgrade, $this->originalPageBundle );
			$downgradeTiming->end( 'downgrade.time' );

			// XXX: Parsoid::downgrade operates on the parsed Document, would be nice
			//      if we could get that instead of getting back HTML which we have to
			//      parse again!
			// XXX: We could just set $this->originalBody to null and leave it to
			//      getOriginalBody() to parse the HTML on demand.
			$this->originalBody = DOMCompat::getBody(
				$this->parseHTML( $this->originalPageBundle->html )
			);
		} else {
			throw new ClientError(
				"Modified ({$targetSchemaVersion}) and original ({$vOriginal}) html are of "
				. 'different type, and no path to downgrade.'
			);
		}
	}

}
