<?php

class HoverGallery {

	function Resources( & $out ) {
		$out->addModules( 'ext.HoverGallery' );
		return true;
	}

	function ParserHook( & $parser ) {
		$parser->setHook( 'hovergallery', 'HoverGallery::Render' );
		return true;
	}

	function Render( $input, array $ARGS, Parser $parser, PPFrame $frame ) {

		$maxhoverwidth = '100%';
		$maxhoverheight = '100%';
		if ( array_key_exists( 'hoversize', $ARGS ) ) {
			$maxhoverwidth = $ARGS['hoversize'] . 'px';
			$maxhoverheight = $ARGS['hoversize'] . 'px';
		}
		if ( array_key_exists( 'maxhoverwidth', $ARGS ) ) {
			$maxhoverwidth = $ARGS['maxhoverwidth'] . 'px';
		}
		if ( array_key_exists( 'maxhoverheight', $ARGS ) ) {
			$maxhoverheight = $ARGS['maxhoverheight'] . 'px';
		}			

		$normalGallery = $parser->recursiveTagParse( '<gallery>' . $input . '</gallery>' );

		$hiddenGallery = '<div class="hover-gallery">';
		$FILENAMES = explode( "\n", $input );
		$FILENAMES = array_filter( $FILENAMES );		
		foreach ( $FILENAMES as $filename ) {
			if ( $start = strpos( $filename, ":" ) ) {
				$filename = substr( $filename, $start + 1 ); // Remove the namespace
			}
			if ( strpos( $filename, "|" ) ) {
				$filename = strstr( $filename, "|", true ); // Remove the parameters				
			}
			$file = wfFindFile( $filename );
			if ( is_object( $file ) ) {
				$link = $file->getFullUrl();
				$hiddenGallery .= '<img src="' . $link . '" style="max-width: ' . $maxhoverwidth . '; max-height: ' . $maxhoverheight . '" />';				
			}
		}
		$hiddenGallery .= '</div>';
		
		return $normalGallery . $hiddenGallery;
	}
}