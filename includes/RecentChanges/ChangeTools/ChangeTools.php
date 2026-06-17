<?php

namespace MediaWiki\RecentChanges\ChangeTools;

use MediaWiki\Html\Html;

/**
 * A set of tools for a change, e.g. a revision in the recent changes list.
 *
 * @since 1.47
 */
class ChangeTools {

	/**
	 * @param string[] $tools HTML elements
	 * @param bool $preventClickjacking Whether the page using the tools should enable clickjacking protection.
	 */
	public function __construct(
		private readonly array $tools,
		private readonly bool $preventClickjacking,
	) {
	}

	/**
	 * Concatenate the tools into a single HTML string and wrap it in a span.
	 * @return string HTML
	 */
	public function toHtml(): string {
		$html = '';
		foreach ( $this->tools as $tool ) {
			$html .= Html::rawElement( 'span', [], $tool );
		}
		if ( $html ) {
			return ' ' . Html::rawElement(
				'span',
				[ 'class' => [ 'mw-changeslist-links', 'mw-pager-tools', 'mw-change-tools' ] ],
				$html
			);
		}
		return $html;
	}

	/**
	 * @return bool Whether the page using the tools should enable clickjacking protection.
	 */
	public function shouldPreventClickjacking(): bool {
		return $this->preventClickjacking;
	}

}
