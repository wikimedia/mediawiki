<?php

/**
 * @since 1.23
 */
class XMLRCFeedFormatter extends MachineReadableRCFeedFormatter {

	protected function formatArray( array $packet ) {
		return ApiFormatXml::recXmlPrint( 'recentchange', $packet, 0 );
	}
}
