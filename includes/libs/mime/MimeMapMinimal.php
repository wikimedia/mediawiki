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

namespace Wikimedia\Mime;

/**
 * MimeMapMinimal defines a core set of MIME types that cannot be overridden by
 * configuration.
 *
 * This class exists for backward compatibility ONLY. New MIME types should be
 * added to MimeMap instead.
 *
 * @internal
 */
class MimeMapMinimal {
	public const MIME_EXTENSIONS = [
		'application/ogg' => [ 'ogx', 'ogg', 'ogm', 'ogv', 'oga', 'spx', 'opus' ],
		'application/pdf' => [ 'pdf' ],
		'application/vnd.oasis.opendocument.chart' => [ 'odc' ],
		'application/vnd.oasis.opendocument.chart-template' => [ 'otc' ],
		'application/vnd.oasis.opendocument.database' => [ 'odb' ],
		'application/vnd.oasis.opendocument.formula' => [ 'odf' ],
		'application/vnd.oasis.opendocument.formula-template' => [ 'otf' ],
		'application/vnd.oasis.opendocument.graphics' => [ 'odg' ],
		'application/vnd.oasis.opendocument.graphics-template' => [ 'otg' ],
		'application/vnd.oasis.opendocument.image' => [ 'odi' ],
		'application/vnd.oasis.opendocument.image-template' => [ 'oti' ],
		'application/vnd.oasis.opendocument.presentation' => [ 'odp' ],
		'application/vnd.oasis.opendocument.presentation-template' => [ 'otp' ],
		'application/vnd.oasis.opendocument.spreadsheet' => [ 'ods' ],
		'application/vnd.oasis.opendocument.spreadsheet-template' => [ 'ots' ],
		'application/vnd.oasis.opendocument.text' => [ 'odt' ],
		'application/vnd.oasis.opendocument.text-master' => [ 'otm' ],
		'application/vnd.oasis.opendocument.text-template' => [ 'ott' ],
		'application/vnd.oasis.opendocument.text-web' => [ 'oth' ],
		'application/javascript' => [ 'js' ],
		'application/x-shockwave-flash' => [ 'swf' ],
		'audio/midi' => [ 'mid', 'midi', 'kar' ],
		'audio/mpeg' => [ 'mpga', 'mpa', 'mp2', 'mp3' ],
		'audio/x-aiff' => [ 'aif', 'aiff', 'aifc' ],
		'audio/x-wav' => [ 'wav' ],
		'audio/ogg' => [ 'oga', 'spx', 'ogg', 'opus' ],
		'audio/opus' => [ 'opus', 'ogg', 'oga', 'spx' ],
		'image/x-bmp' => [ 'bmp' ],
		'image/gif' => [ 'gif' ],
		'image/jpeg' => [ 'jpeg', 'jpg', 'jpe' ],
		'image/png' => [ 'png' ],
		'image/svg+xml' => [ 'svg' ],
		'image/svg' => [ 'svg' ],
		'image/tiff' => [ 'tiff', 'tif' ],
		'image/vnd.djvu' => [ 'djvu' ],
		'image/x.djvu' => [ 'djvu' ],
		'image/x-djvu' => [ 'djvu' ],
		'image/x-portable-pixmap' => [ 'ppm' ],
		'image/x-xcf' => [ 'xcf' ],
		'text/plain' => [ 'txt' ],
		'text/html' => [ 'html', 'htm' ],
		'video/ogg' => [ 'ogv', 'ogm', 'ogg' ],
		'video/mpeg' => [ 'mpg', 'mpeg' ],
	];

	public const MEDIA_TYPES = [
		MEDIATYPE_OFFICE => [
			'application/pdf',
			'application/vnd.oasis.opendocument.chart',
			'application/vnd.oasis.opendocument.chart-template',
			'application/vnd.oasis.opendocument.database',
			'application/vnd.oasis.opendocument.formula',
			'application/vnd.oasis.opendocument.formula-template',
			'application/vnd.oasis.opendocument.graphics',
			'application/vnd.oasis.opendocument.graphics-template',
			'application/vnd.oasis.opendocument.image',
			'application/vnd.oasis.opendocument.image-template',
			'application/vnd.oasis.opendocument.presentation',
			'application/vnd.oasis.opendocument.presentation-template',
			'application/vnd.oasis.opendocument.spreadsheet',
			'application/vnd.oasis.opendocument.spreadsheet-template',
			'application/vnd.oasis.opendocument.text',
			'application/vnd.oasis.opendocument.text-template',
			'application/vnd.oasis.opendocument.text-master',
			'application/vnd.oasis.opendocument.text-web',
		],
		MEDIATYPE_EXECUTABLE => [
			'application/javascript',
			'text/javascript',
			'application/x-javascript',
		],
		MEDIATYPE_MULTIMEDIA => [
			'application/x-shockwave-flash',
			'application/ogg',
			'audio/ogg',
			'video/ogg',
		],
		MEDIATYPE_AUDIO => [
			'audio/midi',
			'audio/x-aiff',
			'audio/x-wav',
			'audio/mp3',
			'audio/mpeg',
		],
		MEDIATYPE_BITMAP => [
			'image/x-bmp',
			'image/x-ms-bmp',
			'image/bmp',
			'image/gif',
			'image/jpeg',
			'image/png',
			'image/tiff',
			'image/vnd.djvu',
			'image/x-xcf',
			'image/x-portable-pixmap',
		],
		MEDIATYPE_DRAWING => [
			'image/svg+xml',
		],
		MEDIATYPE_TEXT => [
			'text/plain',
			'text/html',
		],
		MEDIATYPE_VIDEO => [
			'video/ogg',
			'video/mpeg',
		],
		MEDIATYPE_UNKNOWN => [
			'unknown/unknown',
			'application/octet-stream',
			'application/x-empty',
		],
	];

	public const MIME_TYPE_ALIASES = [
		'text/javascript' => 'application/javascript',
		'application/x-javascript' => 'application/javascript',
		'audio/mpeg' => 'audio/mp3',
		'audio/ogg' => 'application/ogg',
		'video/ogg' => 'application/ogg',
		'image/x-ms-bmp' => 'image/x-bmp',
		'image/bmp' => 'image/x-bmp',
		'application/octet-stream' => 'unknown/unknown',
		'application/x-empty' => 'unknown/unknown',
	];
}
