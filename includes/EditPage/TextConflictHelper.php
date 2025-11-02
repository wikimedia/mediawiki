<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\EditPage;

use MediaWiki\Content\Content;
use MediaWiki\Content\ContentHandler;
use MediaWiki\Content\IContentHandlerFactory;
use MediaWiki\Exception\MWUnknownContentModelException;
use MediaWiki\Html\Html;
use MediaWiki\Output\OutputPage;
use MediaWiki\Title\Title;
use MediaWiki\User\User;
use Wikimedia\Stats\IBufferingStatsdDataFactory;
use Wikimedia\Stats\StatsFactory;

/**
 * Helper for displaying edit conflicts in text content models to users
 *
 * @since 1.31
 * @author Kunal Mehta <legoktm@debian.org>
 */
class TextConflictHelper {

	/**
	 * @var Title
	 */
	protected $title;

	/**
	 * @var null|string
	 */
	public $contentModel;

	/**
	 * @var null|string
	 */
	public $contentFormat;

	/**
	 * @var OutputPage
	 */
	protected $out;

	/**
	 * @var IBufferingStatsdDataFactory|StatsFactory
	 */
	protected $stats;

	/**
	 * @var string Message key for submit button's label
	 */
	protected $submitLabel;

	/**
	 * @var string
	 */
	protected $yourtext = '';

	/**
	 * @var string
	 */
	protected $storedversion = '';

	/**
	 * @var IContentHandlerFactory
	 */
	private $contentHandlerFactory;

	/**
	 * @param Title $title
	 * @param OutputPage $out
	 * @param IBufferingStatsdDataFactory|StatsFactory $stats
	 * @param string $submitLabel
	 * @param IContentHandlerFactory $contentHandlerFactory Required param with legacy support
	 *
	 * @throws MWUnknownContentModelException
	 */
	public function __construct(
		Title $title, OutputPage $out, $stats, $submitLabel,
		IContentHandlerFactory $contentHandlerFactory
	) {
		$this->title = $title;
		$this->out = $out;
		$this->stats = $stats;
		$this->submitLabel = $submitLabel;
		$this->contentModel = $title->getContentModel();
		$this->contentHandlerFactory = $contentHandlerFactory;

		$this->contentFormat = $this->contentHandlerFactory
			->getContentHandler( $this->contentModel )
			->getDefaultFormat();
	}

	/**
	 * @param string $yourtext
	 * @param string $storedversion
	 */
	public function setTextboxes( $yourtext, $storedversion ) {
		$this->yourtext = $yourtext;
		$this->storedversion = $storedversion;
	}

	/**
	 * @param string $contentModel
	 */
	public function setContentModel( $contentModel ) {
		$this->contentModel = $contentModel;
	}

	/**
	 * @param string $contentFormat
	 */
	public function setContentFormat( $contentFormat ) {
		$this->contentFormat = $contentFormat;
	}

	/**
	 * Record a user encountering an edit conflict
	 * @param User|null $user
	 */
	public function incrementConflictStats( ?User $user = null ) {
		$namespace = 'n/a';
		$userBucket = 'n/a';
		$statsdMetrics = [ 'edit.failures.conflict' ];

		// Only include 'standard' namespaces to avoid creating unknown numbers of statsd metrics
		if (
			$this->title->getNamespace() >= NS_MAIN &&
			$this->title->getNamespace() <= NS_CATEGORY_TALK
		) {
			// getNsText() returns empty string if getNamespace() === NS_MAIN
			$namespace = $this->title->getNsText() ?: 'Main';
			$statsdMetrics[] = 'edit.failures.conflict.byNamespaceId.' . $this->title->getNamespace();
		}
		if ( $user ) {
			$userBucket = $this->getUserBucket( $user->getEditCount() );
			$statsdMetrics[] = 'edit.failures.conflict.byUserEdits.' . $userBucket;
		}
		if ( $this->stats instanceof StatsFactory ) {
			$this->stats->getCounter( 'edit_failure_total' )
				->setLabel( 'cause', 'conflict' )
				->setLabel( 'namespace', $namespace )
				->setLabel( 'user_bucket', $userBucket )
				->copyToStatsdAt( $statsdMetrics )
				->increment();
		}

		if ( $this->stats instanceof IBufferingStatsdDataFactory ) {
			foreach ( $statsdMetrics as $metric ) {
				$this->stats->increment( $metric );
			}
		}
	}

	/**
	 * Record when a user has resolved an edit conflict
	 * @param User|null $user
	 */
	public function incrementResolvedStats( ?User $user = null ) {
		$namespace = 'n/a';
		$userBucket = 'n/a';
		$statsdMetrics = [ 'edit.failures.conflict.resolved' ];

		// Only include 'standard' namespaces to avoid creating unknown numbers of statsd metrics
		if (
			$this->title->getNamespace() >= NS_MAIN &&
			$this->title->getNamespace() <= NS_CATEGORY_TALK
		) {
			// getNsText() returns empty string if getNamespace() === NS_MAIN
			$namespace = $this->title->getNsText() ?: 'Main';
			$statsdMetrics[] = 'edit.failures.conflict.resolved.byNamespaceId.' . $this->title->getNamespace();
		}

		if ( $user ) {
			$userBucket = $this->getUserBucket( $user->getEditCount() );
			$statsdMetrics[] = 'edit.failures.conflict.resolved.byUserEdits.' . $userBucket;
		}

		if ( $this->stats instanceof StatsFactory ) {
			$this->stats->getCounter( 'edit_failure_resolved_total' )
				->setLabel( 'cause', 'conflict' )
				->setLabel( 'namespace', $namespace )
				->setLabel( 'user_bucket', $userBucket )
				->copyToStatsdAt( $statsdMetrics )
				->increment();
		}

		if ( $this->stats instanceof IBufferingStatsdDataFactory ) {
			foreach ( $statsdMetrics as $metric ) {
				$this->stats->increment( $metric );
			}
		}
	}

	/**
	 * Retained temporarily for backwards-compatibility.
	 *
	 * This action should be moved into incrementConflictStats, incrementResolvedStats.
	 *
	 * @deprecated since 1.42, do not use
	 * @param int|null $userEdits
	 * @param string $keyPrefixBase
	 */
	protected function incrementStatsByUserEdits( $userEdits, $keyPrefixBase ) {
		if ( $this->stats instanceof IBufferingStatsdDataFactory ) {
			$this->stats->increment( $keyPrefixBase . '.byUserEdits.' . $this->getUserBucket( $userEdits ) );
		}
	}

	/**
	 * @param int|null $userEdits
	 * @return string
	 */
	protected function getUserBucket( ?int $userEdits ): string {
		if ( $userEdits === null ) {
			return 'anon';
		} elseif ( $userEdits > 200 ) {
			return 'over200';
		} elseif ( $userEdits > 100 ) {
			return 'over100';
		} elseif ( $userEdits > 10 ) {
			return 'over10';
		} else {
			return 'under11';
		}
	}

	/**
	 * @return string HTML
	 */
	public function getExplainHeader() {
		return Html::rawElement(
			'div',
			[ 'class' => 'mw-explainconflict' ],
			$this->out->msg( 'explainconflict', $this->out->msg( $this->submitLabel )->text() )->parse()
		);
	}

	/**
	 * HTML to build the textbox1 on edit conflicts
	 *
	 * @param array $customAttribs
	 * @return string HTML
	 */
	public function getEditConflictMainTextBox( array $customAttribs = [] ) {
		$builder = new TextboxBuilder();
		$classes = $builder->getTextboxProtectionCSSClasses( $this->title );

		$attribs = [
			'aria-label' => $this->out->msg( 'edit-textarea-aria-label' )->text(),
			'tabindex' => 1,
		];
		$attribs += $customAttribs;
		foreach ( $classes as $class ) {
			Html::addClass( $attribs['class'], $class );
		}

		$attribs = $builder->buildTextboxAttribs(
			'wpTextbox1',
			$attribs,
			$this->out->getUser(),
			$this->title
		);

		return Html::textarea(
			'wpTextbox1',
			$builder->addNewLineAtEnd( $this->storedversion ),
			$attribs
		);
	}

	/**
	 * Content to go in the edit form before textbox1
	 *
	 * @see EditPage::$editFormTextBeforeContent
	 * @return string HTML
	 */
	public function getEditFormHtmlBeforeContent() {
		return '';
	}

	/**
	 * Content to go in the edit form after textbox1
	 *
	 * @see EditPage::$editFormTextAfterContent
	 * @return string HTML
	 */
	public function getEditFormHtmlAfterContent() {
		return '';
	}

	/**
	 * Content to go in the edit form after the footers
	 * (templates on this page, hidden categories, limit report)
	 */
	public function showEditFormTextAfterFooters() {
		$this->out->wrapWikiMsg( '<h2>$1</h2>', "yourdiff" );

		$yourContent = $this->toEditContent( $this->yourtext );
		$storedContent = $this->toEditContent( $this->storedversion );
		$handler = $this->contentHandlerFactory->getContentHandler( $this->contentModel );
		$diffEngine = $handler->createDifferenceEngine( $this->out );

		$diffEngine->setContent( $yourContent, $storedContent );
		$diffEngine->showDiff(
			$this->out->msg( 'yourtext' )->parse(),
			$this->out->msg( 'storedversion' )->text()
		);

		$this->out->wrapWikiMsg( '<h2>$1</h2>', "yourtext" );

		$builder = new TextboxBuilder();
		$attribs = $builder->buildTextboxAttribs(
			'wpTextbox2',
			[ 'tabindex' => 6, 'readonly' ],
			$this->out->getUser(),
			$this->title
		);

		$this->out->addHTML(
			Html::textarea( 'wpTextbox2', $builder->addNewLineAtEnd( $this->yourtext ), $attribs )
		);
	}

	/**
	 * @param string $text
	 * @return Content
	 */
	private function toEditContent( $text ) {
		return ContentHandler::makeContent(
			$text,
			$this->title,
			$this->contentModel,
			$this->contentFormat
		);
	}
}
