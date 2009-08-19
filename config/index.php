<?php

# MediaWiki web-based config/installation
# Copyright (C) 2004 Brion Vibber <brion@pobox.com>, 2006 Rob Church <robchur@gmail.com>
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
# 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
# http://www.gnu.org/copyleft/gpl.html

# Attempt to set up the include path, to fix problems with relative includes
$IP = dirname( dirname( __FILE__ ) );
define( 'MW_INSTALL_PATH', $IP );

# Define an entry point and include some files
define( "MEDIAWIKI", true );
define( "MEDIAWIKI_INSTALL", true );

# Check for PHP 5
if ( !function_exists( 'version_compare' ) 
	|| version_compare( phpversion(), '5.0.0' ) < 0
) {
	define( 'MW_PHP4', '1' );
	require( "$IP/includes/DefaultSettings.php" );
	require( "$IP/includes/templates/PHP4.php" );
	exit;
}

// Isolate the rest of the code so this file can die out cleanly
// if we find we're running under PHP 4.x... We use PHP 5 syntax
// which doesn't parse under 4.
require( dirname( __FILE__ ) . "/Installer.php" );
