#!/usr/bin/evn perl
# Takes STDIN and converts Converts decimal and named HTML entities to their
# respective literals
# Usage: perl entities2literals.pl < file_to_convert [> outfile]
# 
# Copyright 2005 Ævar Arnfjörð Bjarmason <avarab@gmail.com> No rights reserved

undef $/;
$file = <>;

for (@s=split /;&/,<DATA>;$i<=$#s,$_=$s[$i];++$i) {
	($chr, $nr) = $s[$i] =~ m#&?([^;]+)[^0-9]+([0-9]+)#;
	$file =~ s/&$chr;/&#$nr;/g;
}

for $i (0..length $file) {
	if (&ss($file,$i) eq '&' and &ss($file, $i+1) eq '#') {
		$eat = 1; # Yummie entities
		undef $food;
		next;
	} elsif ($eat && &ss($file, $i) eq '#') {
		next;
	} elsif ($eat && &ss($file, $i) =~ /\d/) {
		$food .= &ss($file, $i);
		next;
	} elsif ($eat && &ss($file, $i) =~ /;/) {
		undef $eat;
		$out .= chr($food);
		undef $food;
		next;
	}
	$out .= &ss($file, $i);
}

print $out;

sub ss {substr($_[0],$_[1],1)}

__DATA__
&aacute;:&#225;&Aacute;:&#193;&acirc;:&#226;&Acirc;:&#194;&agrave;:&#224;&Agrave;:&#192;&aring;:&#229;&Aring;:&#197;&atilde;:&#227;&Atilde;:&#195;&auml;:&#228;&Auml;:&#196;&aelig;:&#230;&AElig;:&#198;&ccedil;:&#231;&Ccedil;:&#199;&eth;:&#240;&ETH;:&#208;&eacute;:&#233;&Eacute;:&#201;&ecirc;:&#234;&Ecirc;:&#202;&egrave;:&#232;&Egrave;:&#200;&euml;:&#235;&Euml;:&#203;&iacute;:&#237;&Iacute;:&#205;&icirc;:&#238;&Icirc;:&#206;&igrave;:&#236;&Igrave;:&#204;&iuml;:&#239;&Iuml;:&#207;&ntilde;:&#241;&Ntilde;:&#209;&oacute;:&#243;&Oacute;:&#211;&ocirc;:&#244;&Ocirc;:&#212;&ograve;:&#242;&Ograve;:&#210;&oslash;:&#248;&Oslash;:&#216;&otilde;:&#245;&Otilde;:&#213;&ouml;:&#246;&Ouml;:&#214;&szlig;:&#223;&thorn;:&#254;&THORN;:&#222;&uacute;:&#250;&Uacute;:&#218;&ucirc;:&#251;&Ucirc;:&#219;&ugrave;:&#249;&Ugrave;:&#217;&uuml;:&#252;&Uuml;:&#220;&yacute;:&#253;&Yacute;:&#221;&yuml;:&#255;
