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
		ok 1 => "$file has <?php ?>";
	} elsif ( $cont =~ m<<\? .* \?>>xs ) {
		ok 0 => "$file does not use <? ?>";
	} else {
		ok 1 => "$file has neither <?php ?> nor <? ?>, check it";
	}
}



