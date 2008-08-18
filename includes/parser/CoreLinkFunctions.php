<?php

/**
 * Various core link functions, registered in Parser::firstCallInit()
 * @ingroup Parser
 */
class CoreLinkFunctions {
	static function register( $parser ) {
		
		
	}

	static function defaultLinkHook( $markers, Title $title, $titleText, &$displayText = null, &$leadingColon = false ) {
		# Warning: This hook should NEVER return true as it is the fallback
		# default for when other hooks return true
		if( $markers->findMarker( $displayText ) ) {
			# There are links inside of the displayText
			# For backwards compatibility the deepest links are dominant so this
			# link should not be handled
			$displayText = $markers->expand($displayText);
			# Return false so that this link is reverted back to WikiText
			return false;
		}
		return $markers->holders()->makeHolder( $title, isset($displayText) ? $displayText : $titleText, '', '', '' );
	}
	
}
