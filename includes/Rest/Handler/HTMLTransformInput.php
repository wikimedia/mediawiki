<?php

namespace MediaWiki\Rest\Handler;

use Composer\Semver\Semver;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use LogicException;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\DOM\Document;
use Wikimedia\Parsoid\DOM\Element;
use Wikimedia\Parsoid\Parsoid;
use Wikimedia\Parsoid\Utils\ContentUtils;
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

	/**
	 * Whether $this->doc has had any necessary processing applied,
	 * such as injecting data-parsoid attributes from a PageBundle.
	 * @var bool
	 */
	private $docHasBeenProcessed = false;

	/** @var ?Document */
	private $doc = null;

	/** @var ?string */
	private $docVersion = null;

	/** @var ?array */
	private $modifiedDataMW = null;

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
		if ( $this->originalPageBundle ) {
			throw new LogicException( 'setOriginalData must be called only once.' );
		}

		if ( $this->doc ) {
			throw new LogicException( 'setOriginalData() cannot be called after' .
				' getModifiedDocument()' );
		}

		$this->original = $originalData;

		$vOriginal = ParsoidFormatHelper::parseContentTypeHeader(
			$originalData['html']['headers']['content-type'] ?? ''
		);

		if ( $vOriginal === null && isset( $this->original['html']['body'] ) ) {
			throw new ClientError(
				'Content-type of original html is missing.'
			);
		}

		// NOTE: This may be a "partial" PageBundle with only the HTML and version set,
		//       or a "full" PageBundle that includes data-parsoid and data-mw.
		$this->originalPageBundle = new PageBundle(
			$this->original['html']['body'] ?? '',
			$this->original['data-parsoid']['body'] ?? null,
			$this->original['data-mw']['body'] ?? null,
			$vOriginal
		);

		$errorMessage = '';

		// If we have a full page bundle, validate it.
		if ( $this->originalPageBundle->parsoid !== null || $this->originalPageBundle->mw !== null ) {
			if ( !$vOriginal ) {
				// NOTE: can't call getSchemaVersion, since that relies on getOriginalPageBundle.
				$vOriginal = $this->docVersion ?: Parsoid::defaultHTMLVersion();
			}

			if ( !$this->originalPageBundle->validate( $vOriginal, $errorMessage ) ) {
				throw new ClientError( $errorMessage );
			}
		}
	}

	/**
	 * The size of the modified HTML in characters.
	 *
	 * @return int
	 */
	public function getModifiedHtmlSize(): int {
		return mb_strlen( $this->html );
	}

	private function getModifiedDocumentRaw(): Document {
		if ( !$this->doc ) {
			$this->doc = $this->parseHTML( $this->html, true );
			$this->docVersion = DOMUtils::extractInlinedContentVersion( $this->doc );
		}

		return $this->doc;
	}

	public function getModifiedDocument(): Document {
		$doc = $this->getModifiedDocumentRaw();

		if ( !$this->docHasBeenProcessed ) {
			$vEdited = $this->docVersion ?: Parsoid::defaultHTMLVersion();
			$pb = $this->getPageBundleForModifiedDocument( $vEdited );

			PageBundle::apply( $doc, $pb );
			$this->docHasBeenProcessed = true;
		}

		return $doc;
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
	 * @return bool
	 */
	public function hasOriginalDataParsoid(): bool {
		return isset( $this->originalPageBundle->parsoid );
	}

	/**
	 * NOTE: The return value of this method depends on
	 *    setOriginalData() having been called first.
	 *
	 * @return string
	 */
	public function getOriginalHtml(): string {
		// TODO: keep in a field
		return $this->hasOriginalHtml() ? ContentUtils::toXML( $this->getOriginalBody() ) : '';
	}

	/**
	 * @param string $html
	 * @param bool $validateXMLNames
	 *
	 * @return Document
	 * @throws ClientError
	 */
	protected function parseHTML( string $html, bool $validateXMLNames = false ): Document {
		return DOMUtils::parseHTML( $html, $validateXMLNames );
	}

	/**
	 * NOTE: The return value of this method depends on
	 *    setOriginalData() having been called first.
	 *
	 * @return Element
	 * @throws ClientError
	 */
	public function getOriginalBody(): Element {
		if ( !$this->hasOriginalHtml() ) {
			throw new LogicException(
				'No original data supplied, call hasOriginalHtml() first.'
			);
		}

		if ( !$this->originalBody && $this->hasOriginalHtml() ) {
			$this->downgradeOriginalData( $this->getSchemaVersion() );

			$pb = $this->getOriginalPageBundle();
			$doc = $this->parseHTML( $pb->html );

			PageBundle::apply( $doc, $pb );
			$this->originalBody = DOMCompat::getBody( $doc );
		}

		return $this->originalBody;
	}

	public function getOriginalSchemaVersion(): ?string {
		return $this->originalPageBundle ? $this->originalPageBundle->version : null;
	}

	/**
	 * Returns a PageBundle representing the original data.
	 *
	 * @note This may be a full page bundle with data-parsoid and data-mw,
	 * or a partial page bundle with just html, or it may be
	 * entirely empty.
	 *
	 * @return PageBundle
	 * @throws ClientError
	 */
	public function getOriginalPageBundle(): PageBundle {
		if ( !$this->originalPageBundle ) {
			throw new LogicException(
				'No original data supplied, call hasOriginalHtml' .
				'or hasOriginalDataParsoid first.'
			);
		}

		// Verify that the top-level parsoid object either doesn't contain
		// offsetType, or that it matches the conversion that has been
		// explicitly requested.
		if ( isset( $this->originalPageBundle->parsoid['offsetType'] ) ) {
			$offsetType = $this->getOffsetType();
			$origOffsetType = $this->originalPageBundle->parsoid['offsetType'] ?? $offsetType;
			if ( $origOffsetType !== $offsetType ) {
				throw new ClientError(
					'DSR offsetType mismatch: ' . $origOffsetType . ' vs ' . $offsetType
				);
			}
		}

		return $this->originalPageBundle;
	}

	/**
	 * NOTE: The return value of this method depends on
	 *    setOriginalData() having been called first.
	 *
	 * @return string
	 */
	public function getSchemaVersion(): string {
		if ( $this->docVersion ) {
			return $this->docVersion;
		}

		// will initialize $this->docVersion
		$this->getModifiedDocumentRaw();

		if ( !$this->docVersion ) {
			$this->docVersion = $this->getOriginalSchemaVersion();
			$this->incrementMetrics( 'html2wt.original.version.notinline' );
		}

		if ( !$this->docVersion ) {
			$this->docVersion = Parsoid::defaultHTMLVersion();
		}

		return $this->docVersion;
	}

	private function getPageBundleForModifiedDocument( string $schemaVersion ): PageBundle {
		// NOTE: we must take $schemaVersion as a param, because
		//   getSchemaVersion() would call getModifiedDocument(), and getModifiedDocument()
		//   would call getPageBundleForModifiedDocument() again, causing a stack overflow.

		if ( $this->modifiedDataMW ) {
			if ( !Semver::satisfies( $this->getSchemaVersion(), '^999.0.0' ) ) {
				throw new ClientError( 'Modified data-mw is not supported by schema version '
					. $this->getSchemaVersion() );
			}
		}

		$origPb = $this->hasOriginalDataParsoid() ? $this->getOriginalPageBundle() : null;

		$pb = new PageBundle(
			$origPb->html ?? '',
			$origPb->parsoid ?? [ 'ids' => [] ],
			$this->modifiedDataMW ?? ( $origPb->mw ?? [ 'ids' => [] ] )
		);

		$errorMessage = '';

		if ( !$pb->validate( $schemaVersion, $errorMessage ) ) {
			throw new ClientError( $errorMessage );
		}

		return $pb;
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

	private function downgradeOriginalData( string $targetSchemaVersion ) {
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
		if ( $downgrade && $this->hasOriginalDataParsoid() ) {
			$pb = $this->getOriginalPageBundle();

			$this->incrementMetrics(
				"downgrade.from.{$downgrade['from']}.to.{$downgrade['to']}"
			);
			$downgradeTiming = $this->startTiming();
			Parsoid::downgrade( $downgrade, $pb );
			$downgradeTiming->end( 'downgrade.time' );

			// XXX: Parsoid::downgrade operates on the parsed Document, would be nice
			//      if we could get that instead of getting back HTML which we have to
			//      parse again!
			// XXX: We could just set $this->originalBody to null and leave it to
			//      getOriginalBody() to parse the HTML on demand.
			$this->originalBody = DOMCompat::getBody( $this->parseHTML( $pb->html ) );
		} else {
			throw new ClientError(
				"Modified ({$targetSchemaVersion}) and original ({$vOriginal}) html are of "
				. 'different type, and no path to downgrade.'
			);
		}
	}

}
