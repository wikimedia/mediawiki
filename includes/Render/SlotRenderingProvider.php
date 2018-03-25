<?php

namespace MediaWiki\Render;

/**
 * FIXME Document
 *
 * @since 1.31
 * @ingroup Page
 */
interface SlotRenderingProvider {

	/**
	 * @return Rendering
	 */
	public function getRendering( $role, $generateHtml = true );
}