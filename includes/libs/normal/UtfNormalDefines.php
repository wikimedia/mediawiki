<?php
/**
 * Some constant definitions for the unicode normalization module.
 *
 * Note: these constants must all be resolvable at compile time by HipHop,
 * since this file will not be executed during request startup for a compiled
 * MediaWiki.
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @file
 * @ingroup UtfNormal
 */

use UtfNormal\Constants;

/**
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UNICODE_HANGUL_FIRST', Constants::UNICODE_HANGUL_FIRST );
/**
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UNICODE_HANGUL_LAST', Constants::UNICODE_HANGUL_LAST );
/**
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UNICODE_HANGUL_LBASE', Constants::UNICODE_HANGUL_LBASE );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UNICODE_HANGUL_VBASE', Constants::UNICODE_HANGUL_VBASE );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UNICODE_HANGUL_TBASE', Constants::UNICODE_HANGUL_TBASE );
/**
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UNICODE_HANGUL_LCOUNT', Constants::UNICODE_HANGUL_LCOUNT );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UNICODE_HANGUL_VCOUNT', Constants::UNICODE_HANGUL_VCOUNT );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UNICODE_HANGUL_TCOUNT', Constants::UNICODE_HANGUL_TCOUNT );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UNICODE_HANGUL_NCOUNT', Constants::UNICODE_HANGUL_NCOUNT );
/**
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UNICODE_HANGUL_LEND', Constants::UNICODE_HANGUL_LEND );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UNICODE_HANGUL_VEND', Constants::UNICODE_HANGUL_VEND );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UNICODE_HANGUL_TEND', Constants::UNICODE_HANGUL_TEND );
/**
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UNICODE_SURROGATE_FIRST', Constants::UNICODE_SURROGATE_FIRST );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UNICODE_SURROGATE_LAST', Constants::UNICODE_SURROGATE_LAST );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UNICODE_MAX', Constants::UNICODE_MAX );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UNICODE_REPLACEMENT', Constants::UNICODE_REPLACEMENT );
/**
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_HANGUL_FIRST', Constants::UTF8_HANGUL_FIRST );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_HANGUL_LAST', Constants::UTF8_HANGUL_LAST );
/**
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_HANGUL_LBASE', Constants::UTF8_HANGUL_LBASE );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_HANGUL_VBASE', Constants::UTF8_HANGUL_VBASE );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_HANGUL_TBASE', Constants::UTF8_HANGUL_TBASE );
/**
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_HANGUL_LEND', Constants::UTF8_HANGUL_LEND );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_HANGUL_VEND', Constants::UTF8_HANGUL_VEND );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_HANGUL_TEND', Constants::UTF8_HANGUL_TEND );
/**
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_SURROGATE_FIRST', Constants::UTF8_SURROGATE_FIRST );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_SURROGATE_LAST', Constants::UTF8_SURROGATE_LAST );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_MAX', Constants::UTF8_MAX );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_REPLACEMENT', Constants::UTF8_REPLACEMENT );
/**
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_OVERLONG_A', Constants::UTF8_OVERLONG_A );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_OVERLONG_B', Constants::UTF8_OVERLONG_B );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_OVERLONG_C', Constants::UTF8_OVERLONG_C );

# These two ranges are illegal
/**
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_FDD0', Constants::UTF8_FDD0 );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_FDEF', Constants::UTF8_FDEF );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_FFFE', Constants::UTF8_FFFE );
/** 
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_FFFF', Constants::UTF8_FFFF );
/**
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_HEAD', Constants::UTF8_HEAD );
/**
 * @deprecated since 1.25, use UtfNormal\Constants directly
 */
define( 'UTF8_TAIL', Constants::UTF8_TAIL );
