<?php

namespace MediaWiki\Tidy;

use Wikimedia\RemexHtml\TreeBuilder\Marker;
use Wikimedia\RemexHtml\TreeBuilder\TreeBuilder;

/**
 * @internal
 */
class RemexCompatBuilder extends TreeBuilder {
	/** @inheritDoc */
	public function reconstructAFE( $sourceStart ) {
		// These checks are redundant with the parent, but here for performance
		$entry = $this->afe->getTail();
		if ( !$entry || $entry instanceof Marker || $entry->stackIndex !== null ) {
			return;
		}

		// Don't reconstruct AFE in file figures to respect the spec,
		// https://www.mediawiki.org/wiki/Specs/HTML#Media
		$len = $this->stack->length();
		while ( $len > 0 ) {
			$i = $this->stack->item( $len - 1 );
			if ( $i->htmlName === 'figcaption' ) {
				break;
			}
			if ( $i->htmlName === 'figure' ) {
				foreach ( $i->attrs->getValues() as $k => $v ) {
					if ( $k === 'typeof' && preg_match( '/\bmw:File\b/', $v ) ) {
						return;
					}
				}
				break;
			}
			$len--;
		}
		parent::reconstructAFE( $sourceStart );
	}
}
