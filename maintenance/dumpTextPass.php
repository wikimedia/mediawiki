<?php
/**
 * BackupDumper that postprocesses XML dumps from dumpBackup.php to add page text
 *
 * Copyright (C) 2005 Brooke Vibber <bvibber@wikimedia.org>
 * https://www.mediawiki.org/
 *
 * @license GPL-2.0-or-later
 * @file
 * @ingroup Maintenance
 */

use MediaWiki\Maintenance\TextPassDumper;

// @codeCoverageIgnoreStart
require_once __DIR__ . '/includes/TextPassDumper.php';

$maintClass = TextPassDumper::class;
require_once RUN_MAINTENANCE_IF_MAIN;
// @codeCoverageIgnoreEnd
