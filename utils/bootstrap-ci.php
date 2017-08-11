<?php
/**
 * Change PHP CodeSniffer to only lint files changed in HEAD.
 * Needed PHP CodeSniffer version 3.0.0 or higher
 *
 * Copyright © 2017 Antoine Musso <hashar@free.fr>
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
 * @author Antoine Musso
 */

# Only filter when running from cli and using Jenkins
if ( ! ( PHP_SAPI === 'cli' && getenv( 'JENKINS_URL' ) !== false ) ) {
	return;
}

# From integration/jenkins.git bin/git-changed-in-head
$_head_files = [];
exec(
	'git show HEAD --name-only --diff-filter=ACM -m --first-parent --format=format:',
	$_head_files, $_return
);
if ( $_return !== 0 ) {
	unset( $_head_files );
	unset( $_return );
	return;
}

# Changes to phpcs.xml affect all files
if ( in_array( 'phpcs.xml', $_head_files ) ) {
	unset( $_head_files );
	unset( $_return );
	return;
}
# composer.json might affect mediawiki/mediawiki-codesniffer version
if ( in_array( 'composer.json', $_head_files ) ) {
	exec( 'git show HEAD^:composer.json', $_prev_composer, $_return );
	if ( $_return !== 0 ) {
		unset( $_head_files );
		unset( $_return );
		return;
	}
	exec( 'git show HEAD:composer.json', $_cur_composer, $_return );
	if ( $_return !== 0 ) {
		unset( $_head_files );
		unset( $_return );
		return;
	}
	$_prev_composer = json_decode( join( '', $_prev_composer ), true );
	$_cur_composer = json_decode( join( '', $_cur_composer ), true );
	if ( $_prev_composer['require-dev']['mediawiki/mediawiki-codesniffer']
		!== $_cur_composer['require-dev']['mediawiki/mediawiki-codesniffer']
	) {
		unset( $_head_files );
		unset( $_return );
		return;
	}
}

# Only keep files out of git head that matches phpcs.xml extensions.
$_extensions = array_keys( $this->config->extensions );
$this->config->files = array_filter(
	$_head_files,
	function ( $file ) use ( $_extensions ) {
		$pinfo = pathinfo( $file );
		return in_array( strtolower( $pinfo['extension'] ), $_extensions );
	}
);
unset( $_extensions );
unset( $_head_files );
unset( $_return );
if ( empty( $this->config->files ) ) {
	echo "No files to process. Skipping run\n";
	exit( 0 );
}
