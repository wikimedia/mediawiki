<?php

namespace MediaWiki\Rest\Handler;

use Wikimedia\Parsoid\Core\ClientError;
use Wikimedia\Parsoid\Core\PageBundle;

/**
 * @unstable
 */
class TransformContext {
	/** @var array */
	private $original;

	/** @var array */
	private $opts;

	/** @var array */
	private $attribs;

	/**
	 * @param array $attribs
	 */
	public function __construct( array $attribs ) {
		$this->attribs = $attribs;
		$this->original = $attribs['opts']['original'] ?? [];
		$this->opts = $attribs['opts'] ?? [];
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
		return $this->opts['from'] === ParsoidFormatHelper::FORMAT_PAGEBUNDLE;
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
			$this->opts['data-mw']['body'] ?? null
		);

		$errorMessage = '';
		if ( !$pb->validate( $schemaVersion, $errorMessage ) ) {
			throw new ClientError( $errorMessage );
		}

		return $pb;
	}

	public function hasModifiedDataMW(): bool {
		return isset( $this->opts['data-mw'] );
	}

	public function getOldId(): ?int {
		return $this->attribs['oldid'] ?? null;
	}

	public function getContentModel(): ?string {
		return $this->opts['contentmodel'] ?? null;
	}

	public function getEnvironmentOffsetType(): string {
		return $this->opts['offsetType'] ?? 'byte';
	}
}
