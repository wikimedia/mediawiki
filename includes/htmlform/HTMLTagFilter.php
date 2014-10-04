<?php
	class HTMLTagFilter extends HTMLFormFIeld {
		function getInputHTML( $value ) {
			$tagFilter = ChangeTags::buildTagFilterSelector( $value );
			if ( $tagFilter ) {
				list( $tagFilterLabel, $tagFilterSelector ) = $tagFilter;
				return $tagFilterSelector;
			}
			return;
		}
	}
