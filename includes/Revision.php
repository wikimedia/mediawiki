<?php

global $wgUseMCRRevision;

/**
 * Loads an alias for Revision based on the $wgUseMCRRevision global.
 * Checks MW_MCR_LOAD_REVISION so that this is NOT run by autoloading but only in Setup.php
 */
if ( defined( 'MW_MCR_LOAD_REVISION' ) && $wgUseMCRRevision ) {
	class_alias( 'RevisionMCR', 'Revision' );
} else {
	class_alias( 'RevisionPreMCR', 'Revision' );
}
