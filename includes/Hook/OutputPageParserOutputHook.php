<?php

namespace MediaWiki\Hook;

/**
 * @stable for implementation
 * @ingroup Hooks
 */
interface OutputPageParserOutputHook {
	/**
	 * after adding a parserOutput to $wgOut
	 *
	 * @since 1.35
	 *
	 * @param ?mixed $out OutputPage instance (object)
	 * @param ?mixed $parserOutput parserOutput instance being added in $out
	 * @return bool|void This hook must not abort, it must return true or null.
	 */
	public function onOutputPageParserOutput( $out, $parserOutput );
}
