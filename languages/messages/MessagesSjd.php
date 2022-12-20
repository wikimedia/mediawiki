<?php
/** Kildin Sami (кӣллт са̄мь кӣлл)
 *
 * @file
 * @ingroup Languages
 *
 * @author Amir A. Aharoni
 */

$fallback = 'ru';

// There's a combining macron after the small я
// and it covers all the letters with macron
$linkTrail = '/^([А-Яа-я̄ӒӓҺһЈјҊҋӅӆӍӎӉӊӇӈҌҍӬӭ]+)(.*)$/sDu';
