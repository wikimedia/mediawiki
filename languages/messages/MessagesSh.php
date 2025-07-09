<?php
/** Serbo-Croatian (srpskohrvatski / српскохрватски)
 *
 * @file
 * @ingroup Languages
 *
 * @author Aca
 */

/**
 * Fallback prioritizes language codes in the following order:
 *
 * 1. Latin-script Ijekavian codes
 * 2. Latin-script Ekavian codes
 * 3. Cyrillic-script Ekavian codes
 *
 * This order aligns with T399126.
 */
$fallback = 'sh-latn, bs, hr, sr-latn, sr-el, sh-cyrl, sr-cyrl, sr-ec';
