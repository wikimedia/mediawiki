<?php
namespace UtfNormal;

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

class Constants {
	const UNICODE_HANGUL_FIRST = 0xac00;
	const UNICODE_HANGUL_LAST = 0xd7a3;

	const UNICODE_HANGUL_LBASE = 0x1100;
	const UNICODE_HANGUL_VBASE = 0x1161;
	const UNICODE_HANGUL_TBASE = 0x11a7;

	const UNICODE_HANGUL_LCOUNT = 19;
	const UNICODE_HANGUL_VCOUNT = 21;
	const UNICODE_HANGUL_TCOUNT = 28;
	# UNICODE_HANGUL_VCOUNT * UNICODE_HANGUL_TCOUNT
	const UNICODE_HANGUL_NCOUNT = 588;

	# UNICODE_HANGUL_LBASE + UNICODE_HANGUL_LCOUNT - 1
	const UNICODE_HANGUL_LEND = 4370;
	# UNICODE_HANGUL_VBASE + UNICODE_HANGUL_VCOUNT - 1
	const UNICODE_HANGUL_VEND = 4469;
	# UNICODE_HANGUL_TBASE + UNICODE_HANGUL_TCOUNT - 1
	const UNICODE_HANGUL_TEND = 4546;

	const UNICODE_SURROGATE_FIRST = 0xd800;
	const UNICODE_SURROGATE_LAST = 0xdfff;
	const UNICODE_MAX = 0x10ffff;
	const UNICODE_REPLACEMENT = 0xfffd;

	# codepointToUtf8( UNICODE_HANGUL_FIRST )
	const UTF8_HANGUL_FIRST = "\xea\xb0\x80";
	# codepointToUtf8( UNICODE_HANGUL_LAST )
	const UTF8_HANGUL_LAST = "\xed\x9e\xa3";

	# codepointToUtf8( UNICODE_HANGUL_LBASE )
	const UTF8_HANGUL_LBASE = "\xe1\x84\x80";
	# codepointToUtf8( UNICODE_HANGUL_VBASE )
	const UTF8_HANGUL_VBASE = "\xe1\x85\xa1";
	# codepointToUtf8( UNICODE_HANGUL_TBASE )
	const UTF8_HANGUL_TBASE = "\xe1\x86\xa7";

	# codepointToUtf8( UNICODE_HANGUL_LEND )
	const UTF8_HANGUL_LEND = "\xe1\x84\x92";
	# codepointToUtf8( UNICODE_HANGUL_VEND )
	const UTF8_HANGUL_VEND = "\xe1\x85\xb5";
	# codepointToUtf8( UNICODE_HANGUL_TEND )
	const UTF8_HANGUL_TEND = "\xe1\x87\x82";

	# codepointToUtf8( UNICODE_SURROGATE_FIRST )
	const UTF8_SURROGATE_FIRST = "\xed\xa0\x80";
	# codepointToUtf8( UNICODE_SURROGATE_LAST )
	const UTF8_SURROGATE_LAST = "\xed\xbf\xbf";
	# codepointToUtf8( UNICODE_MAX )
	const UTF8_MAX = "\xf4\x8f\xbf\xbf";
	# codepointToUtf8( UNICODE_REPLACEMENT )
	const UTF8_REPLACEMENT = "\xef\xbf\xbd";
	#const UTF8_REPLACEMENT = '!';

	const UTF8_OVERLONG_A = "\xc1\xbf";
	const UTF8_OVERLONG_B = "\xe0\x9f\xbf";
	const UTF8_OVERLONG_C = "\xf0\x8f\xbf\xbf";

	# These two ranges are illegal
	# codepointToUtf8( 0xfdd0 )
	const UTF8_FDD0 = "\xef\xb7\x90";
	# codepointToUtf8( 0xfdef )
	const UTF8_FDEF = "\xef\xb7\xaf";
	# codepointToUtf8( 0xfffe )
	const UTF8_FFFE = "\xef\xbf\xbe";
	# codepointToUtf8( 0xffff )
	const UTF8_FFFF = "\xef\xbf\xbf";

	const UTF8_HEAD = false;
	const UTF8_TAIL = true;

}

