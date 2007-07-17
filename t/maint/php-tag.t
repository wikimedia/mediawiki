#!/usr/bin/env perl
use strict;
use warnings;

use Test::More;;

use File::Find;
use File::Slurp qw< slurp >;

my $ext = qr/(?: php | inc )/x;

my @files;
find( sub { push @files, $File::Find::name if -f && /\. $ext $/x }, '.' );

plan tests => scalar @files;

for my $file (@files) {
	my $cont = slurp $file;
	if ( $cont =~ m<<\?php .* \?>>xs ) {
		pass "$file has <?php ?>";
	} elsif ( $cont =~ m<<\? .* \?>>xs ) {
		fail "$file does not use <? ?>";
	} else {
		pass "$file has neither <?php ?> nor <? ?>, check it";
	}
}



