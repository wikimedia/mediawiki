#!/usr/bin/perl

## Rough check that the base and postgres "tables.sql" are in sync
## Should be run from maintenance/postgres

use strict;
use warnings;
use Data::Dumper;

my @old = ("../tables.sql");
my $new = "tables.sql";

## Read in exceptions and other metadata
my %ok;
while (<DATA>) {
	next unless /^(\w+)\s*:\s*([^#]+)/;
	my ($name,$val) = ($1,$2);
	chomp $val;
	if ($name eq 'RENAME') {
		die "Invalid rename\n" unless $val =~ /(\w+)\s+(\w+)/;
		$ok{OLD}{$1} = $2;
		$ok{NEW}{$2} = $1;
		next;
	}
	if ($name eq 'XFILE') {
		push @old, $val;
		next;
	}
	for (split(/\s+/ => $val)) {
		$ok{$name}{$_} = 0;
	}
}

open my $newfh, "<", $new or die qq{Could not open $new: $!\n};

my $datatype = join '|' => qw(
bool
tinyint int bigint real float
tinytext mediumtext text char varchar
timestamp datetime
tinyblob mediumblob blob
);
$datatype .= q{|ENUM\([\"\w, ]+\)};
$datatype = qr{($datatype)};

my $typeval = qr{(\(\d+\))?};

my $typeval2 = qr{ unsigned| binary| NOT NULL| NULL| auto_increment| default ['\-\d\w"]+| REFERENCES .+CASCADE};

my $indextype = join '|' => qw(INDEX KEY FULLTEXT), "PRIMARY KEY", "UNIQUE INDEX", "UNIQUE KEY";
$indextype = qr{$indextype};

my $tabletype = qr{InnoDB|MyISAM|HEAP|HEAP MAX_ROWS=\d+};

my ($table,%old);
for my $old (@old) {
	open my $oldfh, "<", $old or die qq{Could not open $old: $!\n};

	while (<$oldfh>) {
		next if /^\s*\-\-/ or /^\s+$/;
		s/\s*\-\- [\w ]+$//;
		chomp;

		if (/CREATE\s*TABLE/i) {
			m{^CREATE TABLE /\*\$wgDBprefix\*/(\w+) \($}
				or die qq{Invalid CREATE TABLE at line $. of $old\n};
			$table = $1;
			$old{$table}{name}=$table;
		}
		elsif (/^\) TYPE=($tabletype);$/) {
			$old{$table}{type}=$1;
		}
		elsif (/^  (\w+) $datatype$typeval$typeval2{0,3},?$/) {
			$old{$table}{column}{$1} = $2;
		}
		elsif (/^  ($indextype)(?: (\w+))? \(([\w, \(\)]+)\),?$/) {
			$old{$table}{lc $1."_name"} = $2 ? $2 : "";
			$old{$table}{lc $1."pk_target"} = $3;
		}
		else {
			die "Cannot parse line $. of $old:\n$_\n";
		}
	}
	close $oldfh;
}

$datatype = join '|' => qw(
SMALLINT INTEGER BIGINT NUMERIC SERIAL
TEXT CHAR VARCHAR
BYTEA
TIMESTAMPTZ
CIDR
);
$datatype = qr{($datatype)};
my %new;
my ($infunction,$inview,$inrule) = (0,0,0);
while (<$newfh>) {
	next if /^\s*\-\-/ or /^\s*$/;
	s/\s*\-\- [\w ']+$//;
	next if /^BEGIN;/ or /^SET / or /^COMMIT;/;
	next if /^CREATE SEQUENCE/;
	next if /^CREATE(?: UNIQUE)? INDEX/;
	next if /^CREATE FUNCTION/;
	next if /^CREATE TRIGGER/ or /^  FOR EACH ROW/;
	next if /^INSERT INTO/ or /^  VALUES \(/;
	next if /^ALTER TABLE/;
	chomp;

	if (/^\$mw\$;?$/) {
		$infunction = $infunction ? 0 : 1;
		next;
	}
	next if $infunction;

	next if /^CREATE VIEW/ and $inview = 1;
	if ($inview) {
		/;$/ and $inview = 0;
		next;
	}

	next if /^CREATE RULE/ and $inrule = 1;
	if ($inrule) {
		/;$/ and $inrule = 0;
		next;
	}

	if (/^CREATE TABLE "?(\w+)"? \($/) {
		$table = $1;
		$new{$table}{name}=$table;
	}
	elsif (/^\);$/) {
	}
	elsif (/^  (\w+) +$datatype/) {
		$new{$table}{column}{$1} = $2;
	}
	else {
		die "Cannot parse line $. of $new:\n$_\n";
	}
}
close $newfh;

## Old but not new
for my $t (sort keys %old) {
	if (!exists $new{$t} and !exists $ok{OLD}{$t}) {
		print "Table not in $new: $t\n";
		next;
	}
	next if exists $ok{OLD}{$t} and !$ok{OLD}{$t};
	my $newt = exists $ok{OLD}{$t} ? $ok{OLD}{$t} : $t;
	my $oldcol = $old{$t}{column};
	my $newcol = $new{$newt}{column};
	for my $c (keys %$oldcol) {
		if (!exists $newcol->{$c}) {
			print "Column $t.$c not in new\n";
			next;
		}
	}
	for my $c (keys %$newcol) {
		if (!exists $oldcol->{$c}) {
			print "Column $t.$c not in old\n";
			next;
		}
	}
}
## New but not old:
for (sort keys %new) {
	if (!exists $old{$_} and !exists $ok{NEW}{$_}) {
		print "Not in old: $_\n";
		next;
	}
}

__DATA__
## Known exceptions
OLD: searchindex          ## We use tsearch2 directly on the page table instead
OLD: archive              ## This is a view due to the char(14) timestamp hack
RENAME: user mwuser       ## Reserved word causing lots of problems
RENAME: text pagecontent  ## Reserved word
NEW: archive2             ## The real archive table
NEW: mediawiki_version    ## Just us, for now
XFILE: ../archives/patch-profiling.sql
