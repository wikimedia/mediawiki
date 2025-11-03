<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\RCFeed;

use MediaWiki\Api\ApiFormatXml;

/**
 * @since 1.23
 * @ingroup RecentChanges
 */
class XMLRCFeedFormatter extends MachineReadableRCFeedFormatter {

	/** @inheritDoc */
	protected function formatArray( array $packet ) {
		return ApiFormatXml::recXmlPrint( 'recentchange', $packet, 0 );
	}
}
/** @deprecated class alias since 1.43 */
class_alias( XMLRCFeedFormatter::class, 'XMLRCFeedFormatter' );
