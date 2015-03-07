<?php
/**
 * Backwards-compatability constants which are now provided by the
 * UtfNormal library. They are hardcoded here since they are needed
 * before the composer autoloader is initialized.
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

/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UNICODE_HANGUL_FIRST', 0xac00 );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UNICODE_HANGUL_LAST', 0xd7a3 );

/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UNICODE_HANGUL_LBASE', 0x1100 );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UNICODE_HANGUL_VBASE', 0x1161 );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UNICODE_HANGUL_TBASE', 0x11a7 );

/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UNICODE_HANGUL_LCOUNT', 19 );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UNICODE_HANGUL_VCOUNT', 21 );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UNICODE_HANGUL_TCOUNT', 28 );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UNICODE_HANGUL_NCOUNT', UNICODE_HANGUL_VCOUNT * UNICODE_HANGUL_TCOUNT );

/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UNICODE_HANGUL_LEND', UNICODE_HANGUL_LBASE + UNICODE_HANGUL_LCOUNT - 1 );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UNICODE_HANGUL_VEND', UNICODE_HANGUL_VBASE + UNICODE_HANGUL_VCOUNT - 1 );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UNICODE_HANGUL_TEND', UNICODE_HANGUL_TBASE + UNICODE_HANGUL_TCOUNT - 1 );

/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UNICODE_SURROGATE_FIRST', 0xd800 );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UNICODE_SURROGATE_LAST', 0xdfff );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UNICODE_MAX', 0x10ffff );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UNICODE_REPLACEMENT', 0xfffd );

/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_HANGUL_FIRST', "\xea\xb0\x80" /*codepointToUtf8( UNICODE_HANGUL_FIRST )*/ );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_HANGUL_LAST', "\xed\x9e\xa3" /*codepointToUtf8( UNICODE_HANGUL_LAST )*/ );

/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_HANGUL_LBASE', "\xe1\x84\x80" /*codepointToUtf8( UNICODE_HANGUL_LBASE )*/ );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_HANGUL_VBASE', "\xe1\x85\xa1" /*codepointToUtf8( UNICODE_HANGUL_VBASE )*/ );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_HANGUL_TBASE', "\xe1\x86\xa7" /*codepointToUtf8( UNICODE_HANGUL_TBASE )*/ );

/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_HANGUL_LEND', "\xe1\x84\x92" /*codepointToUtf8( UNICODE_HANGUL_LEND )*/ );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_HANGUL_VEND', "\xe1\x85\xb5" /*codepointToUtf8( UNICODE_HANGUL_VEND )*/ );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_HANGUL_TEND', "\xe1\x87\x82" /*codepointToUtf8( UNICODE_HANGUL_TEND )*/ );

/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_SURROGATE_FIRST', "\xed\xa0\x80" /*codepointToUtf8( UNICODE_SURROGATE_FIRST )*/ );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_SURROGATE_LAST', "\xed\xbf\xbf" /*codepointToUtf8( UNICODE_SURROGATE_LAST )*/ );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_MAX', "\xf4\x8f\xbf\xbf" /*codepointToUtf8( UNICODE_MAX )*/ );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_REPLACEMENT', "\xef\xbf\xbd" /*codepointToUtf8( UNICODE_REPLACEMENT )*/ );
#define( 'UTF8_REPLACEMENT', '!' );

/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_OVERLONG_A', "\xc1\xbf" );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_OVERLONG_B', "\xe0\x9f\xbf" );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_OVERLONG_C', "\xf0\x8f\xbf\xbf" );

# These two ranges are illegal
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_FDD0', "\xef\xb7\x90" /*codepointToUtf8( 0xfdd0 )*/ );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_FDEF', "\xef\xb7\xaf" /*codepointToUtf8( 0xfdef )*/ );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_FFFE', "\xef\xbf\xbe" /*codepointToUtf8( 0xfffe )*/ );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_FFFF', "\xef\xbf\xbf" /*codepointToUtf8( 0xffff )*/ );

/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_HEAD', false );
/**
 * @deprecated since 1.25, use UtfNormal\Constants instead
 */
define( 'UTF8_TAIL', true );
