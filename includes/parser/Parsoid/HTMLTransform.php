<?php

namespace MediaWiki\Parser\Parsoid;

use Composer\Semver\Semver;
use Liuggio\StatsdClient\Factory\StatsdDataFactoryInterface;
use LogicException;
use MediaWiki\MediaWikiServices;
use MediaWiki\Rest\Handler\ParsoidFormatHelper;
use MediaWiki\Rest\HttpException;
use Wikimedia\Parsoid\Config\PageConfig;
use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\PageBundle;
use Wikimedia\Parsoid\Core\ResourceLimitExceededException;
use Wikimedia\Parsoid\Core\SelserData;
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
class HTMLTransform {
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

	/** @var PageConfig */
	private $pageConfig;

	/** @var Parsoid */
	private $parsoid;

	/** @var array */
	private $parsoidSettings;

	/**
	 * @param string $html
	 * @param PageConfig $pageConfig
	 * @param Parsoid $parsoid
	 * @param array $parsoidSettings
	 */
	public function __construct(
		string $html,
		PageConfig $pageConfig,
		Parsoid $parsoid,
		array $parsoidSettings
	) {
		$this->html = $html;
		$this->pageConfig = $pageConfig;
		$this->parsoid = $parsoid;
		$this->parsoidSettings = $parsoidSettings;
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

	/**
	 * Get a selective serialization (selser) data object. This
	 * can be null if selser is not enabled or oldid is not available.
	 *
	 * @return SelserData|null
	 * @throws HttpException
	 */
	private function getSelserData(): ?SelserData {
		$oldhtml = $this->getOriginalHtml();

		// As per https://www.mediawiki.org/wiki/Parsoid/API#v1_API_entry_points
		//   "Both it and the oldid parameter are needed for
		//    clean round-tripping of HTML retrieved earlier with"
		// So, no oldid => no selser
		$hasOldId = ( $this->getOriginalRevisionId() !== null );

		if ( $hasOldId && !empty( $this->parsoidSettings['useSelser'] ) ) {
			if ( !$this->pageConfig->getRevisionContent() ) {
				throw new HttpException( 'Could not find previous revision. Has the page been locked / deleted?',
					409 );
			}

			// FIXME: T234548/T234549 - $pageConfig->getPageMainContent() is deprecated:
			// should use $env->topFrame->getSrcText()
			$selserData = new SelserData( $this->pageConfig->getPageMainContent(),
				$oldhtml );
		} else {
			$selserData = null;
		}

		return $selserData;
	}

	public function htmlToWikitext(): string {
		if ( $this->metrics ) {
			$metrics = $this->metrics;
		} else {
			$metrics = MediaWikiServices::getInstance()->getParsoidSiteConfig()->metrics();
		}

		// Performance Timing options
		$timing = Timing::start( $metrics );

		$doc = $this->getModifiedDocument();
		$htmlSize = $this->getModifiedHtmlSize();

		// Send input size to statsd/Graphite
		$metrics->timing( 'html2wt.size.input', $htmlSize );

		$inputContentVersion = $this->getSchemaVersion();

		$metrics->increment(
			'html2wt.original.version.' . $inputContentVersion
		);

		$selserData = $this->getSelserData();

		$timing->end( 'html2wt.init' );

		try {
			$wikitext = $this->parsoid->dom2wikitext( $this->pageConfig, $doc, [
				'inputContentVersion' => $inputContentVersion,
				'offsetType' => $this->getOffsetType(),
				'contentmodel' => $this->getContentModel(),
				'htmlSize' => $htmlSize, // used to trigger status 413 if the input is too big
			], $selserData );
		} catch ( ClientError $e ) {
			throw new HttpException( $e->getMessage(), 400 );
		} catch ( ResourceLimitExceededException $e ) {
			throw new HttpException( $e->getMessage(), 413 );
		}

		$total = $timing->end( 'html2wt.total' );
		$metrics->timing( 'html2wt.size.output', strlen( $wikitext ) );

		if ( $htmlSize ) {  // Avoid division by zero
			// NOTE: the name timePerInputKB is misleading, since $htmlSize is
			//       in characters, not bytes.
			$metrics->timing( 'html2wt.timePerInputKB', $total * 1024 / $htmlSize );
		}

		return $wikitext;
	}

}
