<?php

/**
 * Copyright © 2010 Bryan Tong Minh and Brooke Vibber
 *
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Api;

/**
 * @ingroup API
 */
class ApiFormatXmlRsd extends ApiFormatXml {
	public function __construct( ApiMain $main, string $format ) {
		parent::__construct( $main, $format );
		$this->setRootElement( 'rsd' );
	}

	/** @inheritDoc */
	public function getMimeType() {
		return 'application/rsd+xml';
	}

	/** @inheritDoc */
	public static function recXmlPrint( $name, $value, $indent, $attributes = [] ) {
		unset( $attributes['_idx'] );
		return parent::recXmlPrint( $name, $value, $indent, $attributes );
	}
}

/** @deprecated class alias since 1.43 */
class_alias( ApiFormatXmlRsd::class, 'ApiFormatXmlRsd' );
