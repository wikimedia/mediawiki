<?php
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>
# http://www.mediawiki.org/
# 
# This program is free software; you can redistribute it and/or modify
# it under the terms of the GNU General Public License as published by
# the Free Software Foundation; either version 2 of the License, or 
# (at your option) any later version.
# 
# This program is distributed in the hope that it will be useful,
# but WITHOUT ANY WARRANTY; without even the implied warranty of
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
# GNU General Public License for more details.
# 
# You should have received a copy of the GNU General Public License along
# with this program; if not, write to the Free Software Foundation, Inc.,
# 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
# http://www.gnu.org/copyleft/gpl.html

/**
 * If on the old non-unique indexes, check the cur table for duplicate
 * entries and remove them...
 *
 * @package MediaWiki
 * @subpackage Maintenance
 */

$options = array( 'fix', 'index' );

require_once( "commandLine.inc" );
require_once( 'cleanupDupes.inc' );
$wgTitle = Title::newFromText( "Dupe cur entry cleanup script" );

checkDupes( isset( $options['fix'] ), isset( $options['index'] ) );

?>