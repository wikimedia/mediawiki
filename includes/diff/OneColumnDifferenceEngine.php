<?php
/*
 * Constant to indicate diff cache compatibility.
 * Bump this when changing the diff formatting in a way that
 * fixes important bugs or such to force cached diff views to
 * clear.
 */
define( 'MW_UNIFIED_DIFF_VERSION', '1.11a' );

/**
 * Extends the basic DifferenceEngine from core to enable inline difference view
 * using only one column instead of two column diff system.
 */
class OneColumnDifferenceEngine extends DifferenceEngine {
	protected $name = 'onecolumn';

	/**
	 * Constructor
	 * @param IContextSource $context Context to use, anything else will be ignored
	 * @param int $old Old ID we want to show and diff with.
	 * @param string|int $new Either revision ID or 'prev' or 'next'. Default: 0.
	 * @param int $rcid Deprecated, no longer used!
	 * @param bool $refreshCache If set, refreshes the diff cache
	 * @param bool $unhide If set, allow viewing deleted revs
	 */
	public function __construct( $context = null, $old = 0, $new = 0, $rcid = 0,
		$refreshCache = false, $unhide = false
	) {
		parent::__construct( $context, $old, $new, $rcid, $refreshCache, $unhide );
		if ( $context->getRequest()->getText( 'engine' ) === 'unified-plain' ) {
			$this->name = 'unified-plain';
		}
	}

	/**
	 * Add the header to a diff body
	 *
	 * @inheritdoc
	 */
	public function addHeader( $diff, $otitle, $ntitle, $multi = '', $notice = '' ) {
		$prev = $this->prevLink;
		$next = $this->nextLink;

		$header = Html::openElement( 'div', [ 'class' => 'revision-meta-data' ] );
		if ( $prev || $next ) {
			$header .= Html::openElement( 'ul',
				[ 'class' => 'hlist revision-meta-data-item revision-history-links' ] );
			if ( $prev ) {
				$header .= Html::rawElement( 'li', [], $prev );
			}
			$header .= Html::rawElement(
				'li',
				[ 'class' => 'current' ],
				$this->newRevisionHeader . ' | ' . $this->newRevisionUserTools
					. $this->markPatrolledLink()
			);
			if ( $next ) {
				$header .= Html::rawElement( 'li', [], $next );
			}
			$header .= Html::closeElement( 'ul' );
			$header .= Html::rawElement( 'div',
				[ 'class' => 'revision-meta-data-item' ], $this->newRevisionSummary );
		} else {
			$header = '';
		}
		$multi = $this->getMultiNotice();
		if ( $multi ) {
			$header .= Html::rawElement( 'div',
				[ 'class' => 'revision-meta-data-item' ], $multi );
		}
		$header .= Html::closeElement( 'div' );
		return $header . $notice;
	}

	/**
	 * Get complete diff table, including header
	 *
	 * @param string|bool $otitle Header for old text or false (unused)
	 * @param string|bool $ntitle Header for new text or false (unused)
	 * @param string $notice HTML between diff header and body
	 *
	 * @return mixed
	 */
	public function getDiff( $otitle, $ntitle, $notice = '' ) {
		$diff = $this->getDiffBody();
		return $this->addHeader( null, null, null, null, $notice ) . '<div id="minidiff">' .
			$diff .
			'</div>';
	}

	/**
	 * Creates an inline diff
	 * @param Content $otext Old content
	 * @param Content $ntext New content
	 *
	 * @return string
	 */
	function generateTextDiffBody( $otext, $ntext ) {
		global $wgContLang, $wgExternalDiffEngine;

		if ( function_exists( 'wikidiff2_inline_diff' ) && $wgExternalDiffEngine == 'wikidiff2' ) {
			$text = wikidiff2_inline_diff( $otext, $ntext, 2 );
			$text .= $this->debug( 'wikidiff2-inline' );
		} else {
			# Slow native PHP diff
			$ota = explode( "\n", $wgContLang->segmentForDiff( $otext ) );
			$nta = explode( "\n", $wgContLang->segmentForDiff( $ntext ) );
			$diffs = new Diff( $ota, $nta );
			$isPlain = $this->name === 'unified-plain';
			if ( $isPlain ) {
				$formatter = new OneColumnDiffFormatter();
			} else {
				$formatter = new UnifiedDiffFormatter();
			}
			$difftext = $wgContLang->unsegmentForDiff( $formatter->format( $diffs ) );
			if ( $isPlain ) {
				$difftext = Html::rawElement( 'pre', [], $difftext );
			}
		}

		return $difftext;
	}

	/**
	 * Reimplements getDiffBodyCacheKey from DifferenceEngine
	 * Returns the cache key for diff body text or content.
	 *
	 * @throws Exception when no mOldid and mNewid is set
	 * @see DifferenceEngine:getDiffBodyCacheKey
	 * @return string
	 */
	protected function getDiffBodyCacheKey() {
		if ( !$this->mOldid || !$this->mNewid ) {
			throw new Exception( 'mOldid and mNewid must be set to get diff cache key.' );
		}

		return wfMemcKey( 'diff', $this->name, MW_UNIFIED_DIFF_VERSION,
			'oldid', $this->mOldid, 'newid', $this->mNewid );
	}
}
