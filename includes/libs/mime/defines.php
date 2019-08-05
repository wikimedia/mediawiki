<?php
/**
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
 */

/** @{
 * Media types.
 * This defines constants for the value returned by File::getMediaType()
 */
// unknown format
define( 'MEDIATYPE_UNKNOWN', 'UNKNOWN' );
// some bitmap image or image source (like psd, etc). Can't scale up.
define( 'MEDIATYPE_BITMAP', 'BITMAP' );
// some vector drawing (SVG, WMF, PS, ...) or image source (oo-draw, etc). Can scale up.
define( 'MEDIATYPE_DRAWING', 'DRAWING' );
// simple audio file (ogg, mp3, wav, midi, whatever)
define( 'MEDIATYPE_AUDIO', 'AUDIO' );
// simple video file (ogg, mpg, etc;
// no not include formats here that may contain executable sections or scripts!)
define( 'MEDIATYPE_VIDEO', 'VIDEO' );
// Scriptable Multimedia (flash, advanced video container formats, etc)
define( 'MEDIATYPE_MULTIMEDIA', 'MULTIMEDIA' );
// Office Documents, Spreadsheets (office formats possibly containing apples, scripts, etc)
define( 'MEDIATYPE_OFFICE', 'OFFICE' );
// Plain text (possibly containing program code or scripts)
define( 'MEDIATYPE_TEXT', 'TEXT' );
// binary executable
define( 'MEDIATYPE_EXECUTABLE', 'EXECUTABLE' );
// archive file (zip, tar, etc)
define( 'MEDIATYPE_ARCHIVE', 'ARCHIVE' );
// 3D file types (stl)
define( 'MEDIATYPE_3D', '3D' );
/** @} */
