<?php

namespace MediaWiki\Storage;

/**
 * PageUpdateController
 *
 * @license GPL 2+
 * @author Daniel Kinzler
 */
class PageUpdateController {

	// no permission checks

	// BEGIN

	// add content, etc

	// updates:
	// - create
	// - delete
	// - update (edit)
	// - update revision (annotate)
	// - quick edit (import?)
	// - restore (to old revision)
	// - undo (here? higher level)
	// - rollback (here? higher level?)
	// - purge/invalidate (here?)
	// - refresh (null edit)
	// - dummy (content stays)
	// - restore/undelete

	// COMMIT
	// ABORT

	// will abort when going out of scope
	// will abort when throwing an exception

}
