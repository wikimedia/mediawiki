#!/usr/bin/evn perl
# Takes STDIN and converts Converts decimal and named HTML entities to their
# respective literals
# Usage: perl entities2literals.pl < file_to_convert [> outfile]
# Reference: http://www.w3.org/TR/REC-html40/sgml/entities.html
# Copyright 2005 Ævar Arnfjörð Bjarmason <avarab@gmail.com> No rights reserved

binmode STDOUT, ":utf8";
undef $/;
$file = <>;

for (@s=split /:/,<DATA>;$i<=$#s,$_=$s[$i];++$i) {
	($nr, $chr) = $s[$i] =~ m#(\d+)(.*)#;
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
34quot:38amp:60lt:62gt:160nbsp:161iexcl:162cent:163pound:164curren:165yen:166brvbar:167sect:168uml:169copy:170ordf:171laquo:172not:173shy:174reg:175macr:176deg:177plusmn:178sup2:179sup3:180acute:181micro:182para:183middot:184cedil:185sup1:186ordm:187raquo:188frac14:189frac12:190frac34:191iquest:192Agrave:193Aacute:194Acirc:195Atilde:196Auml:197Aring:198AElig:199Ccedil:200Egrave:201Eacute:202Ecirc:203Euml:204Igrave:205Iacute:206Icirc:207Iuml:208ETH:209Ntilde:210Ograve:211Oacute:212Ocirc:213Otilde:214Ouml:215times:216Oslash:217Ugrave:218Uacute:219Ucirc:220Uuml:221Yacute:222THORN:223szlig:224agrave:225aacute:226acirc:227atilde:228auml:229aring:230aelig:231ccedil:232egrave:233eacute:234ecirc:235euml:236igrave:237iacute:238icirc:239iuml:240eth:241ntilde:242ograve:243oacute:244ocirc:245otilde:246ouml:247divide:248oslash:249ugrave:250uacute:251ucirc:252uuml:253yacute:254thorn:255yuml:338OElig:339oelig:352Scaron:353scaron:376Yuml:402fnof:710circ:732tilde:913Alpha:914Beta:915Gamma:916Delta:917Epsilon:918Zeta:919Eta:920Theta:921Iota:922Kappa:923Lambda:924Mu:925Nu:926Xi:927Omicron:928Pi:929Rho:931Sigma:932Tau:933Upsilon:934Phi:935Chi:936Psi:937Omega:945alpha:946beta:947gamma:948delta:949epsilon:950zeta:951eta:952theta:953iota:954kappa:955lambda:956mu:957nu:958xi:959omicron:960pi:961rho:962sigmaf:963sigma:964tau:965upsilon:966phi:967chi:968psi:969omega:977thetasym:978upsih:982piv:8194ensp:8195emsp:8201thinsp:8204zwnj:8205zwj:8206lrm:8207rlm:8211ndash:8212mdash:8216lsquo:8217rsquo:8218sbquo:8220ldquo:8221rdquo:8222bdquo:8224dagger:8225Dagger:8226bull:8230hellip:8240permil:8242prime:8243Prime:8249lsaquo:8250rsaquo:8254oline:8260frasl:8364euro:8465image:8472weierp:8476real:8482trade:8501alefsym:8592larr:8593uarr:8594rarr:8595darr:8596harr:8629crarr:8656lArr:8657uArr:8658rArr:8659dArr:8660hArr:8704forall:8706part:8707exist:8709empty:8711nabla:8712isin:8713notin:8715ni:8719prod:8721sum:8722minus:8727lowast:8730radic:8733prop:8734infin:8736ang:8743and:8744or:8745cap:8746cup:8747int:8756there4:8764sim:8773cong:8776asymp:8800ne:8801equiv:8804le:8805ge:8834sub:8835sup:8836nsub:8838sube:8839supe:8853oplus:8855otimes:8869perp:8901sdot:8968lceil:8969rceil:8970lfloor:8971rfloor:9001lang:9002rang:9674loz:9824spades:9827clubs:9829hearts:9830diams
