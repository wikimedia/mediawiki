<?php

namespace MediaWiki\Html;

use MediaWiki\Context\IContextSource;
use MediaWiki\Parser\Sanitizer;
use Wikimedia\Message\MessageParam;
use Wikimedia\Message\MessageSpecifier;
use Wikimedia\Parsoid\Core\SectionMetadata;
use Wikimedia\Parsoid\Core\TOCData;

/**
 * Trait for special pages that generate a Table of Contents.
 *
 * It is expected that the class using this trait inherits from
 * ContextSource, which will provide the abstract ::getContext()
 * method.  Another source of context can be provided by
 * re-implementing ::getContext().
 *
 * @since 1.45
 */
trait TocGeneratorTrait {
	private ?TOCData $tocData = null;
	private int $tocIndex = 0;
	private int $tocSection = 0;
	private int $tocSubSection = 0;

	protected function getTocData(): TOCData {
		if ( $this->tocData === null ) {
			$this->tocData = new TOCData();
		}
		return $this->tocData;
	}

	/**
	 * Add a section to the table of contents. This doesn't add the
	 * heading to the actual page.
	 *
	 * @param string $id "True" value of the ID attribute for the section,
	 *  not HTML-entity escaped.
	 * @param string $msg Message key to use for the label
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$params
	 *   See Message::params()
	 */
	protected function addTocSection( string $id, string $msg, ...$params ): void {
		$context = $this->getContext();
		$this->tocIndex++;
		$this->tocSection++;
		$this->tocSubSection = 0;
		$this->getTocData()->addSection( new SectionMetadata(
			tocLevel: 1,
			hLevel: 2,
			line: $context->msg( $msg, ...$params )->escaped(),
			number: $context->getLanguage()->formatNum( $this->tocSection ),
			index: (string)$this->tocIndex,
			anchor: $id,
			linkAnchor: Sanitizer::escapeIdForLink( $id ),
		) );
	}

	/**
	 * Add a sub-section to the table of contents. This doesn't add the
	 * heading to the actual page.
	 *
	 * @param string $id "True" value of the ID attribute for the section,
	 *  not HTML-entity escaped.
	 * @param string $msg Message key to use for the label
	 * @phpcs:ignore Generic.Files.LineLength
	 * @param MessageParam|MessageSpecifier|string|int|float|list<MessageParam|MessageSpecifier|string|int|float> ...$params
	 *   See Message::params()
	 */
	protected function addTocSubSection( string $id, string $msg, ...$params ): void {
		$context = $this->getContext();
		$this->tocIndex++;
		$this->tocSubSection++;
		$this->getTocData()->addSection( new SectionMetadata(
			tocLevel: 2,
			hLevel: 3,
			line: $context->msg( $msg, ...$params )->escaped(),
			// See Parser::localizeTOC
			number: $context->getLanguage()->formatNum( $this->tocSection ) . '.' .
				$context->getLanguage()->formatNum( $this->tocSubSection ),
			index: (string)$this->tocIndex,
			anchor: $id,
			linkAnchor: Sanitizer::escapeIdForLink( $id ),
		) );
	}

	/**
	 * Returns the base IContextSource to use for this trait.
	 * @note If the using class inherits from ContextSource this
	 * method will be provided by ContextSource::getContext().
	 * @return IContextSource
	 */
	abstract public function getContext();
}
