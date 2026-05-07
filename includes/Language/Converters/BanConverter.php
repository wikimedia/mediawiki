<?php
/**
 * @license GPL-2.0-or-later
 * @file
 */

namespace MediaWiki\Language\Converters;

use MediaWiki\Language\LanguageConverterIcu;

/**
 * Balinese specific code.
 *
 * @ingroup Languages
 */
class BanConverter extends LanguageConverterIcu {

	public function getMainCode(): string {
		return 'ban';
	}

	public function getLanguageVariants(): array {
		return [ 'ban', 'ban-bali', 'ban-x-dharma', 'ban-x-palmleaf', 'ban-x-pku' ];
	}

	public function getVariantsFallbacks(): array {
		return [
			'ban-bali' => 'ban',
			'ban-x-dharma' => 'ban',
			'ban-x-palmleaf' => 'ban',
			'ban-x-pku' => 'ban',
		];
	}

	public function getVariantNames(): array {
		$names = [
			'ban' => 'Basa Bali',
			'ban-bali' => 'ᬩᬲᬩᬮᬶ',
			'ban-x-dharma' => 'Basa Bali (alih aksara DHARMA)',
			'ban-x-palmleaf' => 'Basa Bali (alih aksara Palmleaf.org)',
			'ban-x-pku' => 'Basa Bali (alih aksara Puri Kauhan Ubud)',
		];
		return array_merge( parent::getVariantNames(), $names );
	}

	/** @inheritDoc */
	protected function getIcuRules() {
		$rules = [];

		# transliteration rules developed for Palmleaf.org
		$rules['ban-x-palmleaf'] = <<<'EOF'
::NFC;

ᬒᬁ → \uE050; # OM
ᬁ → \uE001; # SIGN ULU CANDRA
ᬂ → \uE002; # SIGN CECEK
ᬄ → \uE003; # SIGN BISAH
ᬅ → \uE005; # LETTER AKARA
ᬆ → \uE006; # LETTER AKARA TEDUNG
ᬇ → \uE007; # LETTER IKARA
ᬈ → \uE008; # LETTER IKARA TEDUNG
ᬉ → \uE009; # LETTER UKARA
ᬊ → \uE00A; # LETTER UKARA TEDUNG
ᬋ → \uE00B; # LETTER RA REPA
ᬌ → \uE060; # LETTER RA REPA TEDUNG
ᬍ → \uE00C; # LETTER LA LENGA
ᬎ → \uE061; # LETTER LA LENGA TEDUNG
ᬏ → \uE00F; # LETTER EKARA
ᬐ → \uE010; # LETTER AIKARA
ᬑ → \uE013; # LETTER OKARA
ᬒ → \uE014; # LETTER OKARA TEDUNG
ᬓ → \uE015; # LETTER KA
ᬔ → \uE016; # LETTER KA MAHAPRANA
ᬕ → \uE017; # LETTER GA
ᬖ → \uE018; # LETTER GA GORA
ᬗ → \uE019; # LETTER NGA
ᬘ → \uE01A; # LETTER CA
ᬙ → \uE01B; # LETTER CA LACA
ᬚ → \uE01C; # LETTER JA
ᬛ → \uE01D; # LETTER JA JERA
ᬜ → \uE01E; # LETTER NYA
ᬝ → \uE01F; # LETTER TA LATIK
ᬞ → \uE020; # LETTER TA MURDA MAHAPRANA
ᬟ → \uE021; # LETTER DA MURDA ALPAPRANA
ᬠ → \uE022; # LETTER DA MURDA MAHAPRANA
ᬡ → \uE023; # LETTER NA RAMBAT
ᬢ → \uE024; # LETTER TA
ᬣ → \uE025; # LETTER TA TAWA
ᬤ → \uE026; # LETTER DA
ᬥ → \uE027; # LETTER DA MADU
ᬦ → \uE028; # LETTER NA
ᬧ → \uE02A; # LETTER PA
ᬨ → \uE02B; # LETTER PA KAPAL
ᬩ → \uE02C; # LETTER BA
ᬪ → \uE02D; # LETTER BA KEMBANG
ᬫ → \uE02E; # LETTER MA
ᬬ → \uE02F; # LETTER YA
ᬭ → \uE030; # LETTER RA
ᬮ → \uE032; # LETTER LA
ᬯ → \uE035; # LETTER WA
ᬰ → \uE036; # LETTER SA SAGA
ᬱ → \uE037; # LETTER SA SAPA
ᬲ → \uE038; # LETTER SA
ᬳ → \uE039; # LETTER HA
᬴ → \uE03C; # SIGN REREKAN
ᬵ → \uE03E; # VOWEL SIGN TEDUNG
ᬶ → \uE03F; # VOWEL SIGN ULU
ᬷ → \uE040; # VOWEL SIGN ULU SARI
ᬸ → \uE041; # VOWEL SIGN SUKU
ᬹ → \uE042; # VOWEL SIGN SUKU ILUT
ᬺ → \uE043; # VOWEL SIGN RA REPA
ᬻ → \uE044; # VOWEL SIGN RA REPA TEDUNG
ᬼ→ \uE062; # VOWEL SIGN LA LENGA
ᬽ → \uE063; # VOWEL SIGN LA LENGA TEDUNG
ᬾ → \uE047; # VOWEL SIGN TALING
ᬿ → \uE048; # VOWEL SIGN TALING REPA
ᭀ → \uE04B; # VOWEL SIGN TALING TEDUNG
ᭁ → \uE04C; # VOWEL SIGN TALING REPA TEDUNG
ᭂ → \uE045; # VOWEL SIGN PEPET
ᭃ → \uE049; # VOWEL SIGN PEPET TEDUNG
᭄ → \uE04D; # ADEG ADEG
ᭅ → \uE058; # LETTER KAF SASAK
ᭆ → \uE059; # LETTER KHOT SASAK
ᭇ → \uE024\uE03C; # LETTER TZIR SASAK
ᭈ → \uE05E; # LETTER EF SASAK
ᭉ → \uE081; # LETTER VE SASAK
ᭊ → \uE05B; # LETTER ZAL SASAK
ᭋ → \uE038\uE03C; # LETTER ASYURA SASAK
᭐ → \uE066; # DIGIT ZERO
᭑ → \uE067; # DIGIT ONE
᭒ → \uE068; # DIGIT TWO
᭓ → \uE069; # DIGIT THREE
᭔ → \uE06A; # DIGIT FO
᭕ → \uE06B; # DIGIT FIVE
᭖ → \uE06C; # DIGIT SIX
᭗ → \uE06D; # DIGIT SEVEN
᭘ → \uE06E; # DIGIT EIGHT
᭙ → \uE06F; # DIGIT NINE
᭚ → '//'; # PANTI
᭛ → '///'; # PAMADA
᭜ → •; # WINDU
᭟᭜᭟ → '\\•\\';
᭟ ' ' ᭜ ' ' ᭟ → '\\ • \\';
᭝ → \:; # CARIK PAMUNGKAH
᭞ → \uE064; # CARIK SIKI
᭟ → \uE065; # CARIK PAREREN
᭠ → ‐; # PAMENENG

#consonants
$chandrabindu=\uE001;
$ardhachandra=\u1B00;
$anusvara=\uE002;
$visarga=\uE003;
# w←vowel→ represents the stand-alone form
$wa=\uE005;
$waa=\uE006;
$wi=\uE007;
$wii=\uE008;
$wu=\uE009;
$wuu=\uE00A;
$wr=\uE00B;
$wl=\uE00C;
$wce=\uE00D; # LETTER CANDRA E
$wse=\uE00E; # LETTER SHORT E
$we=\uE00F;  # ए LETTER E
$wai=\uE010;
$wco=\uE011; # LETTER CANDRA O
$wso=\uE012; # LETTER SHORT O
$wo=\uE013;  # ओ LETTER O
$wau=\uE014;
$ka=\uE015;
$kha=\uE016;
$ga=\uE017;
$gha=\uE018;
$nga=\uE019;
$ca=\uE01A;
$cha=\uE01B;
$ja=\uE01C;
$jha=\uE01D;
$nya=\uE01E;
$tta=\uE01F;
$ttha=\uE020;
$dda=\uE021;
$ddha=\uE022;
$nna=\uE023;
$ta=\uE024;
$tha=\uE025;
$da=\uE026;
$dha=\uE027;
$na=\uE028;
$ena=\uE029; #compatibility
$pa=\uE02A;
$pha=\uE02B;
$ba=\uE02C;
$bha=\uE02D;
$ma=\uE02E;
$ya=\uE02F;
$ra=\uE030;
$vva=\uE081;
$rra=\uE031;
$la=\uE032;
$lla=\uE033;
$ela=\uE034; #compatibility
$va=\uE035;
$sha=\uE036;
$ssa=\uE037;
$sa=\uE038;
$ha=\uE039;
$nukta=\uE03C;
$avagraha=\uE03D; # SIGN AVAGRAHA
# ←vowel→ represents the dependent form
$aa=\uE03E;
$i=\uE03F;
$ii=\uE040;
$u=\uE041;
$uu=\uE042;
$rh=\uE043;
$rrh=\uE044;
$ce=\uE045; #VOWEL SIGN CANDRA E
$se=\uE046; #VOWEL SIGN SHORT E
$e=\uE047;
$ai=\uE048;
$co=\uE049; # VOWEL SIGN CANDRA O
$so=\uE04A; # VOWEL SIGN SHORT O
$o=\uE04B;  # ो
$au=\uE04C;
$virama=\uE04D;
$om=\uE050; # OM
\uE051→;        # UNMAPPED STRESS SIGN UDATTA
\uE052→;        # UNMAPPED STRESS SIGN ANUDATTA
\uE053→;        # UNMAPPED GRAVE ACCENT
\uE054→;        # UNMAPPED ACUTE ACCENT
$lm = \uE055;#  Telugu Length Mark
$ailm=\uE056;#  AI Length Mark
$aulm=\uE057;#  AU Length Mark
#urdu compatibility forms
$uka=\uE058;
$ukha=\uE059;
$ugha=\uE05A;
$ujha=\uE05B;
$uddha=\uE05C;
$udha=\uE05D;
$ufa=\uE05E;
$uya=\uE05F;
$wrr=\uE060;
$wll=\uE061;
$lh=\uE062;
$llh=\uE063;
$danda=\uE064;
$doubleDanda=\uE065;
$zero=\uE066;     # DIGIT ZERO
$one=\uE067;      # DIGIT ONE
$two=\uE068;      # DIGIT TWO
$three=\uE069;    # DIGIT THREE
$four=\uE06A;     # DIGIT FOUR
$five=\uE06B;     # DIGIT FIVE
$six=\uE06C;      # DIGIT SIX
$seven=\uE06D;    # DIGIT SEVEN
$eight=\uE06E;    # DIGIT EIGHT
$nine=\uE06F;     # DIGIT NINE
# Glottal stop
$dgs=\uE082;
#Khanda-ta
$kta=\uE083;
$depVowelAbove=[\uE03E-\uE040\uE045-\uE04C];
$depVowelBelow=[\uE041-\uE044];
# $x was originally called '§'; $z was '%'
$x=[$aa$ai$au$ii$i$uu$u$rrh$rh$lh$llh$e$o$se$ce$so$co];
$z=[bcdfghjklmnpqrstvwxyz];
$vowels=[aeiour̥̄̆];
$forceIndependentMatra = [^[[:L:][̀-͌]]];
$strike=\u0336;

######################################################################
# normalize input
######################################################################

# delete zwnj
\u200C→;
# reprocess from beginning
::Null;

######################################################################
# convert from Native letters to Latin letters
######################################################################

#glottal stop
$wa$virama → k'';

#anusvara
$anusvara → ng;

#surang
ᬃ → r̀;

# Urdu compatibility
$ya$nukta}$x        → y;
$ya$nukta$virama    → y;
$ya$nukta           → ya;
$la$nukta }$x       → l;
$la$nukta$virama    → l;
$la$nukta           → la;
$na$nukta }$x       → n;
$na$nukta$virama    → n;
$na$nukta           → na;
$ena }$x            → n;
$ena$virama         → n;
$ena                → na;
$uka                → qa;
$ka$nukta }$x       → q;
$ka$nukta$virama    → q;
$ka$nukta           → qa;
$kha$nukta }$x      → kh;
$kha$nukta$virama   → kh;
$kha$nukta          → kha;
$ukha$virama        → kh;
$ukha               → kha;
$ugha               → gha;
$ga$nukta }$x       → gh;
$ga$nukta$virama    → gh;
$ga$nukta           → gha;
$ujha               → za;
$ja$nukta }$x       → z;
$ja$nukta$virama    → z;
$ja$nukta           → za;
$ddha$nukta}$x      → r;
$ddha$nukta$virama  → r;
$ddha$nukta         → ra;
$uddha}$x           → r;
$uddha$virama       → r;
$uddha              → ra;
$udha               → ra;
$dda$nukta}$x       → r;
$dda$nukta$virama   → r;
$dda$nukta          → ra;
$pha$nukta }$x      → f;
$pha$nukta$virama   → f;
$pha$nukta          → fa;
$ufa }$x            → f;
$ufa$virama         → f;
$ufa                → fa;
$ra$nukta}$x        → r;
$ra$nukta$virama    → r;
$ra$nukta           → ra;
$lla$nukta}$x       → l;
$lla$nukta$virama   → l;
$lla$nukta          → la;
$ela}$x             → l;
$ela$virama         → l;
$ela                → la;
$uya}$x             → y;
$uya$virama         → y;
$uya                → ya;

# normal consonants
$ka$virama}$ha→k'';
$ka}$x→k;
$ka$virama→k;
$ka→ka;
$kha$i$u→k $strike h $strike;
$kha}$x→kh;
$kha$virama→kh;
$kha→kha;
$ga$virama}$ha→g'';
$ga}$x→g;
$ga$virama→g;
$ga→ga;
$gha$i$u→g $strike h $strike;
$gha}$x→gh;
$gha$virama→gh;
$gha→gha;
$nga$i$u→n $strike g $strike;
$nga}$x→ng;
$nga$virama→ng;
$nga→nga;
$ca$virama}$ha→c'';
$ca}$x→c;
$ca$virama→c;
$ca→ca;
$cha$i$u→c $strike h $strike;
$cha}$x→ch;
$cha$virama→ch;
$cha→cha;
$ja$virama}$ha→j'';
$ja}$x→j;
$ja$virama→j;
$ja→ja;
$jha$i$u→j $strike h $strike;
$jha}$x→jh;
$jha$virama→jh;
$jha→jha;
$nya }$x→ñ;
$nya$virama→ñ;
$nya → ña;
$tta$virama}$ha→ṭ'';
$tta}$x→ṭ;
$tta$virama→ṭ;
$tta→ṭa;
$ttha$i$u→ṭ $strike h $strike;
$ttha}$x→ṭh;
$ttha$virama→ṭh;
$ttha→ṭha;
$dda}$x$ha→ḍ'';
$dda}$x→ḍ;
$dda$virama→ḍ;
$dda→ḍa;
$ddha$i$u→ḍ $strike h $strike;
$ddha}$x→ḍh;
$ddha$virama→ḍh;
$ddha→ḍha;
$nna}$x→ṇ;
$nna$virama→ṇ;
$nna→ṇa;
$ta$virama}$ha→t'';
$ta}$x→t;
$ta$virama→t;
$ta→ta;
$tha$i$u→t $strike h $strike;
$tha}$x→th;
$tha$virama→th;
$tha→tha;
$da$virama}$ha→d'';
$da}$x→d;
$da$virama→d;
$da→da;
$dha$i$u→d $strike h $strike;
$dha}$x→dh;
$dha$virama→dh;
$dha→dha;
$na$virama}$ga→n'';
$na}$x→n;
$na$virama→n;
$na→na;
$pa$virama}$ha→p'';
$pa}$x→p;
$pa$virama→p;
$pa→pa;
$pha$i$u→p $strike h $strike;
$pha}$x→ph;
$pha$virama→ph;
$pha→pha;
$ba$virama}$ha→b'';
$ba}$x→b;
$ba$virama→b;
$ba→ba;
$bha$i$u→b $strike h $strike;
$bha}$x→bh;
$bha$virama→bh;
$bha→bha;
$ma}$x→m;
$ma$virama→m;
$ma→ma;
$ya}$x→y;
$ya$virama→y;
$ya→ya;
$ra}$x→r;
$ra$virama→r;
$ra→ra;
$vva}$x→v;
$vva$virama→v;
$vva→va;
$rra}$x→r;
$rra$virama→r;
$rra→ra;
$la}$x→l;
$la$virama→l;
$la→la;
$lla}$x→l;
$lla$virama→l;
$lla→la;
$va}$x→w;
$va$virama→w;
$va→wa;
$sa}$x→s;
$sa$virama→s;
#for gurmukhi
$sa$nukta}$x→sy;
$sa$nukta$virama→sy;
$sa$nukta→sya;
$sa→sa;
$sha}$x→ś;
$sha$virama→ś;
$sha→śa;
$ssa}$x→sy;
$ssa$virama→ṣ;
$ssa→ṣa;
$ha}$x→h;
$ha$virama→h;
$ha→ha;

# dependent vowels (should never occur except following consonants)
$forceIndependentMatra{$aa  → ̔ā;
$forceIndependentMatra{$ai  → ̔ai;
$forceIndependentMatra{$au  → ̔au;
$forceIndependentMatra{$ii  → ̔ī;
$forceIndependentMatra{$i   → ̔i;
$forceIndependentMatra{$uu  → ̔ū;
$forceIndependentMatra{$u   → ̔u;
$forceIndependentMatra{$rrh → ̔r̥ö;
$forceIndependentMatra{$rh  → ̔r̥ĕ;
$forceIndependentMatra{$llh → ̔l̥ö;
$forceIndependentMatra{$lh  → ̔l̥ĕ;
$forceIndependentMatra{$e   → ̔e;
$forceIndependentMatra{$o   → ̔o;
#extra vowels
$forceIndependentMatra{$ce  → ̔ĕ;
$forceIndependentMatra{$co  → ̔ö;
$forceIndependentMatra{$se  → ̔ĕ;
$forceIndependentMatra{$so  → ̔o;
$forceIndependentMatra{$nukta  →; # Nukta cannot appear independently or as first character
$forceIndependentMatra{$virama →; # Virama cannot appear independently or as first character
$i$u → $strike;
$aa  → ā;
$ai  → ai;
$au  → au;
$ii  → ī;
$i   → i;
$uu  → ū;
$u   → u;
$rrh → r̥ö;
$rh  → r̥ĕ;
$llh → l̥ö;
$lh  → l̥ĕ;
$e   → e;
$o   → o;
#extra vowels
$ce  → ĕ;
$co  → ö;
$se  → ĕ;
$so  → o;

#dependent vowels when following independent vowels. Generally Illegal only for roundtripping
$waa} $x → ā;
$wai} $x → ai;
$wau} $x → au;
$wii} $x → ī;
$wi } $x → i;
$wuu} $x → ū;
$wu } $x → u;
$wrr} $x → r̥ö;
$wr } $x → r̥ĕ;
$wll} $x → l̥ö;
$wl } $x → l̥ĕ;
$we } $x → e;
$wo } $x → o;
$wa } $x → a;
#extra vowels
$wce} $x → ĕ;
$wco} $x → ö;
$wse} $x → ĕ;
$wso} $x → o;
$om} $x → oṁ;

# independent vowels when preceded by vowels
$vowels{$waa  → ''ā;
$vowels{$wai  → ''ai;
$vowels{$wau  → ''au;
$vowels{$wii  → ''ī;
$vowels{$wi   → ''i;
$vowels{$wuu  → ''ū;
$vowels{$wu   → ''u;
$vowels{$we   → ''e;
$vowels{$wo   → ''o;
$vowels{$wa   → ''a;
#extra vowels
$vowels{$wce  → ''ĕ;
$vowels{$wco  → ''ö;
$vowels{$wse  → ''ĕ;
$vowels{$wso  → ''o;
$vowels{$om  → ''oṁ;

# independent vowels (otherwise)
$waa → ā;
$wai → ai;
$wau → au;
$wii → ī;
$wi  → i;
$wuu → ū;
$wu  → u;
$wrr → r̥ö;
$wr  → r̥ĕ;
$wll → l̥ö;
$wl  → l̥ĕ;
$we  → e;
$wo  → o;
$wa  → a;
#extra vowels
$wce → ĕ;
$wco → ö;
$wse → ĕ;
$wso → o;
$om → oṁ;

# stress marks
$avagraha → ;
$chandrabindu → ṅġ;
$ardhachandra → ṃ;
$visarga → ḥ;

# numbers
$zero  → 0;
$one   → 1;
$two   → 2;
$three → 3;
$four  → 4;
$five  → 5;
$six   → 6;
$seven → 7;
$eight → 8;
$nine  → 9;
$lm   →;
$ailm →;
$aulm →;
$dgs→'';
$kta→t;
# Balinese numbers are surrounded by dandas which can be removed
$danda } [$zero$one$two$three$four$five$six$seven$eight$nine] → ' ';
[0123456789] { $danda → ' ';
$danda→', ';
$doubleDanda→'. ';

\uE070→;       # ABBREVIATION SIGN
# LETTER RA WITH MIDDLE DIAGONAL
\uE071}$x→ra;
\uE071$virama→r;
\uE071→ra;
# LETTER RA WITH LOWER DIAGONAL
\uE072}$x→ra;
\uE072$virama→r;
\uE072→ra;
\uE073→;       # RUPEE MARK
\uE074→;       # RUPEE SIGN
\uE075→;       # CURRENCY NUMERATOR ONE
\uE076→;       # CURRENCY NUMERATOR TWO
\uE077→;       # CURRENCY NUMERATOR THREE
\uE078→;       # CURRENCY NUMERATOR FOUR
\uE079→;       # CURRENCY NUMERATOR ONE LESS THAN THE DENOMINATOR
\uE07A→;       # CURRENCY DENOMINATOR SIXTEEN
\uE07B→;       # ISSHAR
\uE07C→;       # TIPPI
\uE07D→;       # ADDAK
\uE07E→;       # IRI
\uE07F→;       # URA
\uE080→;       # EK ONKAR
\uE004→;       # DEVANAGARI VOWEL SIGN SHORT A

::NFC;
EOF;

		# transliteration rules following DHARMA project "strict transliteration"
		# mostly follows ISO-15919, with modifications for precision and broader coverage
		# https://hal.inria.fr/halshs-02272407/
		$rules['ban-x-dharma'] = <<<'EOF'
::NFC;

$dv_no_rerekan = [\u1B35-\u1B44];
$dv = [\u1B34$dv_no_rerekan];
$c = [\u1B13-\u1B33 \u1B45-\u1B4C];

# disambiguation from aspirates
[kgcjṭḍtdpb] { ᭄ } ᬳ → \:;

# various signs
ᬀ → ṁ\*; # ulu ricem / ardhacandra
ᬁ → m̐; # ulu candra / candrabindu
ᬂ → ṁ; # cecek / anusvara
ᬃ → r\=; # surang / repha (note, "Indonesian mode" not "Indian mode")
ᬄ → ḥ; # bisah / visarga

# akara used as glottal
ᬅ } $dv_no_rerekan → q;

# independent vowels
ᬅ → A; # LETTER AKARA
ᬆ → A\:; # LETTER AKARA TEDUNG
ᬇ → I; # LETTER IKARA
ᬈ → I\:; # LETTER IKARA TEDUNG
ᬉ → U; # LETTER UKARA
ᬊ → U\:; # LETTER UKARA TEDUNG
ᬋ → R̥; # LETTER RA REPA
ᬌ → R̥\:; # LETTER RA REPA TEDUNG
ᬍ → L̥; # LETTER LA LENGA
ᬎ → L̥̄; # LETTER LA LENGA TEDUNG
ᬏ → E; # LETTER EKARA
ᬐ → Ai; # LETTER AIKARA
ᬑ → O; # LETTER OKARA
ᬒ → O\:; # LETTER OKARA TEDUNG

# consonants
ᬓ } $dv → k;
ᬓ → ka; # LETTER KA
ᬔ } $dv → kh;
ᬔ → kha; # LETTER KA MAHAPRANA
ᬕ } $dv → g;
ᬕ → ga; # LETTER GA
ᬖ } $dv → gh;
ᬖ → gha; # LETTER GA GORA
ᬗ } $dv → ṅ;
ᬗ → ṅa; # LETTER NGA
ᬘ } $dv → c;
ᬘ → ca; # LETTER CA
ᬙ } $dv → ch;
ᬙ → cha; # LETTER CA LACA
ᬚ } $dv → j;
ᬚ → ja; # LETTER JA
ᬛ } $dv → jh;
ᬛ → jha; # LETTER JA JERA
ᬜ } $dv → ñ;
ᬜ → ña; # LETTER NYA
ᬝ } $dv → ṭ;
ᬝ → ṭa; # LETTER TA LATIK
ᬞ } $dv → ṭh;
ᬞ → ṭha; # LETTER TA MURDA MAHAPRANA
ᬟ } $dv → ḍ;
ᬟ → ḍa; # LETTER DA MURDA ALPAPRANA
ᬠ } $dv → ḍh;
ᬠ → ḍha; # LETTER DA MURDA MAHAPRANA
ᬡ } $dv → ṇ;
ᬡ → ṇa; # LETTER NA RAMBAT
ᬢ } $dv → t;
ᬢ → ta; # LETTER TA
ᬣ } $dv → th;
ᬣ → tha; # LETTER TA TAWA
ᬤ } $dv → d;
ᬤ → da; # LETTER DA
ᬥ } $dv → dh;
ᬥ → dha; # LETTER DA MADU
ᬦ } $dv → n;
ᬦ → na; # LETTER NA
ᬧ } $dv → p;
ᬧ → pa; # LETTER PA
ᬨ } $dv → ph;
ᬨ → pha; # LETTER PA KAPAL
ᬩ } $dv → b;
ᬩ → ba; # LETTER BA
ᬪ } $dv → bh;
ᬪ → bha; # LETTER BA KEMBANG
ᬫ } $dv → m;
ᬫ → ma; # LETTER MA
ᬬ } $dv → y;
ᬬ → ya; # LETTER YA
ᬭ } $dv → r;
ᬭ → ra; # LETTER RA
ᬮ } $dv → l;
ᬮ → la; # LETTER LA
ᬯ } $dv → v;
ᬯ → va; # LETTER WA
ᬰ } $dv → ś;
ᬰ → śa; # LETTER SA SAGA
ᬱ } $dv → ṣ;
ᬱ → ṣa; # LETTER SA SAPA
ᬲ } $dv → s;
ᬲ → sa; # LETTER SA
ᬳ } $dv → h;
ᬳ → ha; # LETTER HA
\u1B4C } $dv → j\=ñ;
\u1B4C → j\=ña; # LETTER ARCHAIC JNYA

# rerekan (not present in DHARMA, "*" used as impromptu mark)
᬴ } $dv_no_rerekan → \*;
᬴ → \* a; # SIGN REREKAN

# dependent vowels
ᬵ → ā; # VOWEL SIGN TEDUNG
ᬶ → i; # VOWEL SIGN ULU
ᬷ → ī; # VOWEL SIGN ULU SARI
ᬸ → u; # VOWEL SIGN SUKU
ᬹ → ū; # VOWEL SIGN SUKU ILUT
ᬺ → r̥; # VOWEL SIGN RA REPA
ᬻ → r̥\:; # VOWEL SIGN RA REPA TEDUNG
ᬼ→ l̥; # VOWEL SIGN LA LENGA
ᬽ → l̥\:; # VOWEL SIGN LA LENGA TEDUNG
ᬾ → e; # VOWEL SIGN TALING
ᬿ → ai; # VOWEL SIGN TALING REPA
ᭀ → o; # VOWEL SIGN TALING TEDUNG
ᭁ → au; # VOWEL SIGN TALING REPA TEDUNG
ᭂ → ə; # VOWEL SIGN PEPET
ᭃ → ə\:; # VOWEL SIGN PEPET TEDUNG

# adeg-adeg
᭄\u200C → ·; # explicit ADEG ADEG
᭄ } $c → ; # ADEG ADEG
᭄ → ·; # ADEG ADEG

# Sasak consonants (not present in DHARMA, "'" used as impromptu mark)
ᭅ } $dv → k\';
ᭅ → k\'a; # LETTER KAF SASAK
ᭆ } $dv → kh\';
ᭆ → kh\'a; # LETTER KHOT SASAK
ᭇ } $dv → t\';
ᭇ → t\'a; # LETTER TZIR SASAK
ᭈ } $dv → p\';
ᭈ → p\'a; # LETTER EF SASAK
ᭉ } $dv → v\';
ᭉ → v\'a; # LETTER VE SASAK
ᭊ } $dv → j\';
ᭊ → j\'a; # LETTER ZAL SASAK
ᭋ } $dv → s\';
ᭋ → s\'a; # LETTER ASYURA SASAK

# digits
᭐ → 0; # DIGIT ZERO
᭑ → 1; # DIGIT ONE
᭒ → 2; # DIGIT TWO
᭓ → 3; # DIGIT THREE
᭔ → 4; # DIGIT FOUR
᭕ → 5; # DIGIT FIVE
᭖ → 6; # DIGIT SIX
᭗ → 7; # DIGIT SEVEN
᭘ → 8; # DIGIT EIGHT
᭙ → 9; # DIGIT NINE

# punctuation
᭚ → '<g type="panti"/>'; # PANTI
᭛ → '<g type="pamada"/>'; # PAMADA
᭜ → \@; # WINDU
᭝ → '<g type="pamungkah"/>'; # CARIK PAMUNGKAH
᭞ → \,; # CARIK SIKI
᭟ → \,\,; # CARIK PAREREN
᭠ → '<g type="pameneng"/>'; # PAMENENG
\u1B7D → '<g type="pantiLantang"/>';
\u1B7E → '<g type="pamadaLantang"/>';
EOF;

		# transliteration rules developed at Puri Kauhan Ubud and widely used in Bali
		# default Balinese to Latin transliteration variant
		$rules['ban-x-pku'] = <<<'EOF'
::NFC;

$dv_no_rerekan = [\u1B35-\u1B44];
$dv = [\u1B34$dv_no_rerekan];
$c = [\u1B13-\u1B33 \u1B45-\u1B4C];
$base = [\u1B05-\u1B33 \u1B45-\u1B60];

# ulu suku deletion mark
$base ᬶᬸ → ∅;

# disambiguation from aspirates
[kgcjṭḍtdpb] { ᭄ } ᬳ → \:;

# various signs
ᬀ → ṃ; # ulu ricem / ardhacandra
ᬁ → m̐; # ulu candra / candrabindu
ᬂ → ŋ; # cecek / anusvara
ᬃ → ŕ; # surang / repha (note, "Indonesian mode" not "Indian mode")
ᬄ → ḥ; # bisah / visarga

# akara used as glottal
ᬅ } $dv_no_rerekan → \*;

# independent vowels
ᬅ → ᵒa; # LETTER AKARA
ᬆ → ᵒā; # LETTER AKARA TEDUNG
ᬇ → ᵒi; # LETTER IKARA
ᬈ → ᵒī; # LETTER IKARA TEDUNG
ᬉ → ᵒu; # LETTER UKARA
ᬊ → ᵒū; # LETTER UKARA TEDUNG
ᬋ → r̥; # LETTER RA REPA
ᬌ → r̥̄; # LETTER RA REPA TEDUNG
ᬍ → l̥; # LETTER LA LENGA
ᬎ → l̥̄; # LETTER LA LENGA TEDUNG
ᬏ → ᵒe; # LETTER EKARA
ᬐ → ᵒai; # LETTER AIKARA
ᬑ → ᵒo; # LETTER OKARA
ᬒ → ᵒau; # LETTER OKARA TEDUNG

# consonants
ᬓ } $dv → k;
ᬓ → ka; # LETTER KA
ᬔ } $dv → kh;
ᬔ → kha; # LETTER KA MAHAPRANA
ᬕ } $dv → g;
ᬕ → ga; # LETTER GA
ᬖ } $dv → gh;
ᬖ → gha; # LETTER GA GORA
ᬗ } $dv → ṅ;
ᬗ → ṅa; # LETTER NGA
ᬘ } $dv → c;
ᬘ → ca; # LETTER CA
ᬙ } $dv → ch;
ᬙ → cha; # LETTER CA LACA
ᬚ } $dv → j;
ᬚ → ja; # LETTER JA
ᬛ } $dv → jh;
ᬛ → jha; # LETTER JA JERA
ᬜ } $dv → ñ;
ᬜ → ña; # LETTER NYA
ᬝ } $dv → ṭ;
ᬝ → ṭa; # LETTER TA LATIK
ᬞ } $dv → ṭh;
ᬞ → ṭha; # LETTER TA MURDA MAHAPRANA
ᬟ } $dv → ḍ;
ᬟ → ḍa; # LETTER DA MURDA ALPAPRANA
ᬠ } $dv → ḍh;
ᬠ → ḍha; # LETTER DA MURDA MAHAPRANA
ᬡ } $dv → ṇ;
ᬡ → ṇa; # LETTER NA RAMBAT
ᬢ } $dv → t;
ᬢ → ta; # LETTER TA
ᬣ } $dv → th;
ᬣ → tha; # LETTER TA TAWA
ᬤ } $dv → d;
ᬤ → da; # LETTER DA
ᬥ } $dv → dh;
ᬥ → dha; # LETTER DA MADU
ᬦ } $dv → n;
ᬦ → na; # LETTER NA
ᬧ } $dv → p;
ᬧ → pa; # LETTER PA
ᬨ } $dv → ph;
ᬨ → pha; # LETTER PA KAPAL
ᬩ } $dv → b;
ᬩ → ba; # LETTER BA
ᬪ } $dv → bh;
ᬪ → bha; # LETTER BA KEMBANG
ᬫ } $dv → m;
ᬫ → ma; # LETTER MA
ᬬ } $dv → y;
ᬬ → ya; # LETTER YA
ᬭ } $dv → r;
ᬭ → ra; # LETTER RA
ᬮ } $dv → l;
ᬮ → la; # LETTER LA
ᬯ } $dv → w;
ᬯ → wa; # LETTER WA
ᬰ } $dv → ś;
ᬰ → śa; # LETTER SA SAGA
ᬱ } $dv → ṣ;
ᬱ → ṣa; # LETTER SA SAPA
ᬲ } $dv → s;
ᬲ → sa; # LETTER SA
ᬳ } $dv → h;
ᬳ → ha; # LETTER HA
\u1B4C } $dv → j\=ñ;
\u1B4C → j\=ña; # LETTER ARCHAIC JNYA

# rerekan (not present in DHARMA, "*" used as impromptu mark)
᬴ } $dv_no_rerekan → \*;
᬴ → \* a; # SIGN REREKAN

# dependent vowels
ᬵ → ā; # VOWEL SIGN TEDUNG
ᬶ → i; # VOWEL SIGN ULU
ᬷ → ī; # VOWEL SIGN ULU SARI
ᬸ → u; # VOWEL SIGN SUKU
ᬹ → ū; # VOWEL SIGN SUKU ILUT
ᬺᭂ → r̥ĕ;
ᬺ → r̥ĕ; # VOWEL SIGN RA REPA
ᬻ → r̥ö; # VOWEL SIGN RA REPA TEDUNG
ᬼ→ lĕ; # VOWEL SIGN LA LENGA
ᬽ → lö; # VOWEL SIGN LA LENGA TEDUNG
ᬾ → e; # VOWEL SIGN TALING
ᬿ → ai; # VOWEL SIGN TALING REPA
ᭀ → o; # VOWEL SIGN TALING TEDUNG
ᭁ → au; # VOWEL SIGN TALING REPA TEDUNG
ᭂ → ĕ; # VOWEL SIGN PEPET
ᭃ → ö; # VOWEL SIGN PEPET TEDUNG

# adeg-adeg
᭄\u200C → ·; # explicit ADEG ADEG
᭄ } $c → ; # ADEG ADEG
᭄ → ·; # ADEG ADEG

# Sasak consonants (not present in DHARMA, "'" used as impromptu mark)
ᭅ } $dv → k\';
ᭅ → k\'a; # LETTER KAF SASAK
ᭆ } $dv → kh\';
ᭆ → kh\'a; # LETTER KHOT SASAK
ᭇ } $dv → t\';
ᭇ → t\'a; # LETTER TZIR SASAK
ᭈ } $dv → p\';
ᭈ → p\'a; # LETTER EF SASAK
ᭉ } $dv → w\';
ᭉ → w\'a; # LETTER VE SASAK
ᭊ } $dv → j\';
ᭊ → j\'a; # LETTER ZAL SASAK
ᭋ } $dv → s\';
ᭋ → s\'a; # LETTER ASYURA SASAK

# digits
᭐ → 0; # DIGIT ZERO
᭑ → 1; # DIGIT ONE
᭒ → 2; # DIGIT TWO
᭓ → 3; # DIGIT THREE
᭔ → 4; # DIGIT FOUR
᭕ → 5; # DIGIT FIVE
᭖ → 6; # DIGIT SIX
᭗ → 7; # DIGIT SEVEN
᭘ → 8; # DIGIT EIGHT
᭙ → 9; # DIGIT NINE

# punctuation
᭚ → '||'; # PANTI
᭛ → '//'; # PAMADA
᭜ → 0; # WINDU
᭝ → \=; # CARIK PAMUNGKAH
᭞ → \,; # CARIK SIKI
᭟ → \.; # CARIK PAREREN
᭠ → \-; # PAMENENG
\u1B7D → '|||';
\u1B7E → '///';
EOF;

		return $rules;
	}

	/** @inheritDoc */
	protected function getTransliteratorAliases() {
		return [
			'ban' => 'ban-x-pku',
			'ban-bali' => 'ban-x-pku',
		];
	}

	/**
	 * Guess if a text is written in Balinese or Latin.
	 *
	 * @param string $text The text to be checked
	 * @param string $variant Language code of the variant to be checked for
	 * @return bool True if $text appears to be written in $variant
	 */
	public function guessVariant( $text, $variant ) {
		$hasBalinese = preg_match( "/[\x{1B00}-\x{1B7F}]/u", $text );
		return ( $variant == 'ban-bali' ) == $hasBalinese;
	}

}

/** @deprecated class alias since 1.46 */
class_alias( BanConverter::class, 'BanConverter' );
