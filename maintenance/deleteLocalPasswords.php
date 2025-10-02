<?php
/**
 * Delete unused local passwords.
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\DeleteLocalPasswords;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/includes/DeleteLocalPasswords.php';

$maintClass = DeleteLocalPasswords::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
