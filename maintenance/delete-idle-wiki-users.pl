#!/usr/bin/perl
#
# Nuke idle wiki accounts from the wiki's user database.
#
# Copyright (C) 2005 Ralf Baechle (ralf@linux-mips.org)
#
# This program is free software; you can redistribute  it and/or modify it
# under  the terms of  the GNU General  Public License as published by the
# Free Software Foundation;  either version 2 of the  License, or (at your
# option) any later version.
#
# THIS  SOFTWARE  IS PROVIDED   ``AS  IS'' AND   ANY  EXPRESS OR IMPLIED
# WARRANTIES,   INCLUDING, BUT NOT  LIMITED  TO, THE IMPLIED WARRANTIES OF
# MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED.  IN
# NO  EVENT  SHALL   THE AUTHOR  BE    LIABLE FOR ANY   DIRECT, INDIRECT,
# INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
# NOT LIMITED   TO, PROCUREMENT OF  SUBSTITUTE GOODS  OR SERVICES; LOSS OF
# USE, DATA,  OR PROFITS; OR  BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON
# ANY THEORY OF LIABILITY, WHETHER IN  CONTRACT, STRICT LIABILITY, OR TORT
# (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF
# THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
#
# You should have received a copy of the  GNU General Public License along
# with this program; if not, write  to the Free Software Foundation, Inc.,
# 675 Mass Ave, Cambridge, MA 02139, USA.
#

my $database = "DBI:mysql:database=wikidb;host=localhost";
my $dbuser   = "wikiuser";
my $dbpasswd = "password";

use strict;
use DBI();

my $verbose = 0;
my $for_real = 1;

sub do_db_op
{
	my ($dbh, $sql) = @_;

	if ($verbose >= 3) {
		print $sql . ";\n"
	}

	if ($for_real == 1) {
		$dbh->do($sql);
	}
}

sub undo_user
{
	my ($ref, $dbh, $sth, $killed);

	# Connect to the database.
	$dbh = DBI->connect($database, $dbuser, $dbpasswd, {RaiseError => 1});

	$sth = $dbh->prepare("SELECT * FROM user");
	$sth->execute();

	$ref = $sth->fetchrow_hashref();

	if ($sth->rows == 0) {
		print "There is no user in this wiki.\n";
		return;
	}

	while ($ref = $sth->fetchrow_hashref()) {
		my ($user_id, $user_name, $cph, $oph, $edits);

		$user_name = $ref->{user_name};
		$user_id = $ref->{user_id};
		if ($verbose >= 2) {
			print "Annihilating user " . $user_name .
			      " has user_id " . $user_id . ".\n";
		}

		$cph = $dbh->prepare("SELECT * FROM cur where " .
		                "cur_user = $user_id" .
		                " AND " .
		                "cur_user_text = " . $dbh->quote("$user_name"));
		$cph->execute();

		$oph = $dbh->prepare("SELECT * FROM old where " .
		                "old_user = $user_id" .
		                " AND " .
		                "old_user_text = " . $dbh->quote("$user_name"));
		$oph->execute();

		$edits = $cph->rows + $oph->rows;

		$cph->finish();
		$oph->finish();

		if ($edits == 0) {
			if ($verbose >= 2) {
				print "Keeping user " . $user_name .
				      ", user_id " . $user_id . ".\n";
			}

			do_db_op($dbh,
		         "DELETE FROM user WHERE user_name = " .
		         $dbh->quote("$user_name") .
		         " AND " .
		         "user_id = $user_id");

			$killed++;
		}
	}

	$sth->finish();

	$dbh->disconnect();

	if ($verbose >= 1) {
		print "Killed " . $killed . " users\n";
	}
}

my (@users, $user, $this, $opts);

@users = ();
$opts = 1;

foreach $this (@ARGV) {
	if ($opts == 1 && $this eq '-v') {
		$verbose++;
	} elsif ($opts == 1 && $this eq '--verbose') {
		$verbose = 1;
	} elsif ($opts == 1 && $this eq '--') {
		$opts = 0;
	} else {
		push(@users, $this);
	}
}

undo_user();

