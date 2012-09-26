<?php
/** British English (British English)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author Dantman
 * @author Fitoschido
 * @author Hazard-SJ
 * @author Jon Harald Søby
 * @author Reedy
 * @author The Evil IP address
 */

$specialPageAliases = array(
	'Uncategorizedcategories'   => array( 'UncategorisedCategories' ),
	'Uncategorizedimages'       => array( 'UncategorisedFiles', 'UncategorisedImages' ),
	'Uncategorizedpages'        => array( 'UncategorisedPages' ),
	'Uncategorizedtemplates'    => array( 'UncategorisedTemplates' ),
);

$messages = array(
# User preference toggles
'tog-watchcreations' => 'Add pages I create to my watchlist',
'tog-watchdefault' => 'Add pages I edit to my watchlist',
'tog-watchmoves' => 'Add pages I move to my watchlist',
'tog-watchdeletion' => 'Add pages I delete to my watchlist',
'tog-showjumplinks' => 'Enable ‘jump to’ accessibility links',

# Categories related messages
'category_header' => 'Pages in category ‘$1’',
'category-media-header' => 'Media in category ‘$1’',

'retrievedfrom' => 'Retrieved from ‘$1’',
'page-rss-feed' => '‘$1’ RSS feed',
'page-atom-feed' => '‘$1’ Atom feed',

# General errors
'dberrortext' => 'A database query syntax error has occurred.
This may indicate a bug in the software.
The last attempted database query was:
<blockquote><tt>$1</tt></blockquote>
from within function ‘<tt>$2</tt>.’
Database returned error ‘<tt>$3: $4</tt>.’',
'dberrortextcl' => 'A database query syntax error has occurred.
The last attempted database query was:
‘$1’
from within function ‘$2.’
Database returned error ‘$3: $4’',
'missing-article' => 'The database did not find the text of a page that it should have found, named ‘$1’ $2.

This is usually caused by following an outdated diff or history link to a page that has been deleted.

If this is not the case, you may have found a bug in the software.
Please report this to an [[Special:ListUsers/sysop|administrator]], making note of the URL.',
'fileappenderrorread' => 'Could not read ‘$1’ during append.',
'fileappenderror' => 'Could not append ‘$1’ to ‘$2.’',
'filecopyerror' => 'Could not copy file ‘$1’ to ‘$2.’',
'filerenameerror' => 'Could not rename file ‘$1’ to ‘$2.’',
'filedeleteerror' => 'Could not delete file ‘$1’.',
'directorycreateerror' => 'Could not create directory ‘$1’.',
'filenotfound' => 'Could not find file ‘$1’.',
'fileexistserror' => 'Unable to write to file ‘$1’: file exists',
'unexpected' => 'Unexpected value: ‘$1’=‘$2’.',
'cannotdelete' => 'The page or file ‘$1’ could not be deleted.
It may have already been deleted by someone else.',
'cascadeprotected' => 'This page has been protected from editing, because it is included in the following {{PLURAL:$1|page, which is|pages, which are}} protected with the ‘cascading’ option turned on:
$2',

# Miscellaneous special pages
'uncategorizedpages' => 'Uncategorised pages',
'uncategorizedcategories' => 'Uncategorised categories',
'uncategorizedimages' => 'Uncategorised files',
'uncategorizedtemplates' => 'Uncategorised templates',

# Edit tokens
'sessionfailure' => 'There seems to be a problem with your login session;
this action has been cancelled as a precaution against session hijacking.
Go back to the previous page, reload that page and then try again.',

# Block/unblock
'blockiptext' => 'Use the form below to block write access from a specific IP address or username.
This should be done only to prevent vandalism, and in accordance with [[{{MediaWiki:Policy-url}}|policy]].
Fill in a specific reason below (for example, citing particular pages that were vandalised).',

# Metadata
'metadata-help' => 'This file contains additional information, probably added from the digital camera or scanner used to create or digitise it.
If the file has been modified from its original state, some details may not fully reflect the modified file.',

# EXIF tags
'exif-ycbcrcoefficients' => 'Colour space transformation matrix coefficients',
'exif-colorspace' => 'Colour space',
'exif-datetimedigitized' => 'Date and time of digitising',
'exif-subsectimedigitized' => 'DateTimeDigitised subseconds',
'exif-exposureprogram' => 'Exposure programme',

'exif-exposureprogram-2' => 'Normal programme',
'exif-exposureprogram-5' => 'Creative programme (biased toward depth of field)',
'exif-exposureprogram-6' => 'Action programme (biased toward fast shutter speed)',

'exif-subjectdistance-value' => '$1 metres',

'exif-sensingmethod-2' => 'One-chip colour area sensor',
'exif-sensingmethod-3' => 'Two-chip colour area sensor',
'exif-sensingmethod-4' => 'Three-chip colour area sensor',
'exif-sensingmethod-5' => 'Colour sequential area sensor',
'exif-sensingmethod-8' => 'Colour sequential linear sensor',

# Pseudotags used for GPSAltitudeRef
'exif-gpsaltitude-above-sealevel' => '$1 {{PLURAL:$1|metre|metres}} above sea level',
'exif-gpsaltitude-below-sealevel' => '$1 {{PLURAL:$1|metre|metres}} below sea level',

# Pseudotags used for GPSSpeedRef
'exif-gpsspeed-k' => 'Kilometres per hour',

# Pseudotags used for GPSDestDistanceRef
'exif-gpsdestdistance-k' => 'Kilometres',

# E-mail address confirmation
'confirmemail_invalidated' => 'E-mail address confirmation cancelled',

# Special:Version
'version-license-info' => 'MediaWiki is free software; you can redistribute it and/or modify it under the terms of the GNU General Public Licence as published by the Free Software Foundation; either version 2 of the Licence, or (at your option) any later version.

MediaWiki is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public Licence for more details.

You should have received [{{SERVER}}{{SCRIPTPATH}}/COPYING a copy of the GNU General Public Licence] along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA or [//www.gnu.org/licenses/old-licenses/gpl-2.0.html read it online].',

);
