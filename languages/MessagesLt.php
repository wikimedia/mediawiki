<?php
/** Lithuanian (Lietuvių)
 *
 * @package MediaWiki
 * @subpackage Language
 *
 */

$namespaceNames = array(
	NS_MEDIA            => 'Medija',
	NS_SPECIAL          => 'Specialus',
	NS_MAIN	            => '',
	NS_TALK	            => 'Aptarimas',
	NS_USER             => 'Naudotojas',
	NS_USER_TALK        => 'Naudotojo_aptarimas',
	# NS_PROJECT set by $wgMetaNamespace
	NS_PROJECT_TALK     => '$1_aptarimas',
	NS_IMAGE            => 'Vaizdas',
	NS_IMAGE_TALK       => 'Vaizdo_aptarimas',
	NS_MEDIAWIKI        => 'MediaWiki',
	NS_MEDIAWIKI_TALK   => 'MediaWiki_aptarimas',
	NS_TEMPLATE         => 'Šablonas',
	NS_TEMPLATE_TALK    => 'Šablono_aptarimas',
	NS_HELP             => 'Pagalba',
	NS_HELP_TALK        => 'Pagalbos_aptarimas',
	NS_CATEGORY         => 'Kategorija',
	NS_CATEGORY_TALK    => 'Kategorijos_aptarimas',
);

$quickbarSettings = array(
	'Nerodyti', 'Fiksuoti kairėje', 'Fiksuoti dešinėje', 'Plaukiojantis kairėje'
);

$skinNames = array(
	'standard' => 'Standartinė',
	'nostalgia' => 'Nostalgija',
	'cologneblue' => 'Kiolno Mėlyna',
	'davinci' => 'Da Vinči',
	'mono' => 'Mono',
	'monobook' => 'MonoBook',
	'myskin' => 'MySkin',
	'chick' => 'Chick'
);
$fallback8bitEncoding = 'windows-1257';
$separatorTransformTable = array(',' => ' ', '.' => ',' );


$messages = array(
'1movedto2' => 'Straipsnis \'$1\' pervadintas į \'$2\'',
'1movedto2_redir' => '\'$1\' pervadintas į \'$2\' (anksčiau buvo nukreipiamasis)',
'Monobook.js' => '/* tooltips and access keys */
var ta = new Object();
ta[\'pt-userpage\'] = new Array(\'.\',\'Mano vartotojo puslapis\');
ta[\'pt-anonuserpage\'] = new Array(\'.\',\'Vartotojo puslapis jūsų ip ardesui\');
ta[\'pt-mytalk\'] = new Array(\'n\',\'Mano aptarimų puslapis\');
ta[\'pt-anontalk\'] = new Array(\'n\',\'Pakeitimų aptarimas, darytus naudojant šį IP adresą\');
ta[\'pt-preferences\'] = new Array(\'\',\'Mano nustatymai\');
ta[\'pt-watchlist\'] = new Array(\'l\',\'Sąrašas straipsnių, kuriuos jūs pasirinkote stebėti.\');
ta[\'pt-mycontris\'] = new Array(\'y\',\'Mano darytų keitimų sąrašas\');
ta[\'pt-login\'] = new Array(\'o\',\'Rekomenduojame prisijungti, nors tai nėra privaloma.\');
ta[\'pt-anonlogin\'] = new Array(\'o\',\'Rekomenduojame prisijungti, nors tai nėra privaloma.\');
ta[\'pt-logout\'] = new Array(\'o\',\'Atsijungti\');
ta[\'ca-talk\'] = new Array(\'t\',\'Straipsnio aptarimas\');
ta[\'ca-edit\'] = new Array(\'e\',\'Jūs galite redaguoti šį straipsnį. Nepamirškite peržiūrėti pakeitimų prieš užsaugodami.\');
ta[\'ca-addsection\'] = new Array(\'+\',\'Pradėti naują aptarimo temą.\');
ta[\'ca-viewsource\'] = new Array(\'e\',\'Puslapis yra užrakintas. Galite tik pažiūrėti turinį.\');
ta[\'ca-history\'] = new Array(\'h\',\'Ankstesnės puslapio versijos.\');
ta[\'ca-protect\'] = new Array(\'=\',\'Uždrausti šį puslapį\');
ta[\'ca-delete\'] = new Array(\'d\',\'Ištrinti šį puslapį\');
ta[\'ca-undelete\'] = new Array(\'d\',\'Atstatyti puslapį su visais darytais keitimais\');
ta[\'ca-move\'] = new Array(\'m\',\'Pervadinti straipsnį\');
ta[\'ca-nomove\'] = new Array(\'\',\'Neturite teisių pervadinti šį straipsnį\');
ta[\'ca-watch\'] = new Array(\'w\',\'Pridėti straipsnį į stebimųjų sąrašą\');
ta[\'ca-unwatch\'] = new Array(\'w\',\'Išimti straipsnį iš stebimųjų sąrašo\');
ta[\'search\'] = new Array(\'f\',\'Ieškoti projekte {{SITENAME}}\');
ta[\'p-logo\'] = new Array(\'\',\'Į pradinį puslapį\');
ta[\'n-mainpage\'] = new Array(\'z\',\'Apsilankykite pradiniame puslapyje\');
ta[\'n-portal\'] = new Array(\'\',\'Apie projektą, ką galima daryti, kur ką rasti\');
ta[\'n-currentevents\'] = new Array(\'\',\'Find background information on current events\');
ta[\'n-recentchanges\'] = new Array(\'r\',\'Sąrašas paskutinių keitimų.\');
ta[\'n-randompage\'] = new Array(\'x\',\'Parinkti atsitiktinį straipsnį\');
ta[\'n-help\'] = new Array(\'\',\'Vieta, kur rasite rūpimus atsakymus.\');
ta[\'n-sitesupport\'] = new Array(\'\',\'Aukokite projektui\');
ta[\'t-whatlinkshere\'] = new Array(\'j\',\'Sąrašas straipsnių, rodančių į čia\');
ta[\'t-recentchangeslinked\'] = new Array(\'k\',\'Paskutiniai keitimai straipsniuose, pasiekiamuose iš šio straipsnio\');
ta[\'feed-rss\'] = new Array(\'\',\'RSS feed for this page\');
ta[\'feed-atom\'] = new Array(\'\',\'Atom feed for this page\');
ta[\'t-contributions\'] = new Array(\'\',\'Pažiūrėti vartotojo įnašą - darytus keitimus\');
ta[\'t-emailuser\'] = new Array(\'\',\'Siųsti el.laišką vartotojui\');
ta[\'t-upload\'] = new Array(\'u\',\'Įdėti paveikslėlį ar media failą\');
ta[\'t-specialpages\'] = new Array(\'q\',\'Specialiųjų puslapių sąrašas\');
ta[\'ca-nstab-main\'] = new Array(\'c\',\'Pereiti į straipsnio turinį\');
ta[\'ca-nstab-user\'] = new Array(\'c\',\'Rodyti vartotojo puslapį\');
ta[\'ca-nstab-media\'] = new Array(\'c\',\'Rodyti media puslapį\');
ta[\'ca-nstab-special\'] = new Array(\'\',\'Šis puslapis yra specialusis - jo negalima redaguoti.\');
ta[\'ca-nstab-project\'] = new Array(\'a\',\'Rodyti projekto puslapį\');
ta[\'ca-nstab-image\'] = new Array(\'c\',\'Rodyti paveikslėlio puslapį\');
ta[\'ca-nstab-mediawiki\'] = new Array(\'c\',\'Rodyti sisteminį pranešimą\');
ta[\'ca-nstab-template\'] = new Array(\'c\',\'Rodyti šabloną\');
ta[\'ca-nstab-help\'] = new Array(\'c\',\'Rodyti pagalbos puslapį\');
ta[\'ca-nstab-category\'] = new Array(\'c\',\'Rodyti kategorijos puslapį\');',

'about' => 'Apie',
'aboutpage' => 'Projektas:Apie',
'aboutsite' => 'Apie {{SITENAME}} projektą',
'accmailtext' => 'Vartotojo \'$1\' slaptažodis nusiųstas į $2.',
'accmailtitle' => 'Slaptažodis išsiųstas.',
'acct_creation_throttle_hit' => 'Atleiskite, jūs jau sukūrėte $1 prisijungimo vardą. Daugiau nebegalima.',
'actioncomplete' => 'Veiksmas atliktas',
'addedwatch' => 'Pridėta prie Stebimų',
'addedwatchtext' => 'Straipsnis "$1" pridėtas į [[Special:Watchlist|stebimųjų sąrašą]].
Būsimi straipsnio bei atitinkamo aptarimo puslapio pakeitimai bus rodomi stebimųjų puslapių sąraše,
taip pat bus \'\'\'paryškinti\'\'\' [[Special:Recentchanges|naujausių keitimų sąraše]] kad išsiskirtų iš kitų straipsnių.

<p>Jei bet kuriuo metu užsinorėtumėte nustoti stebėti straipsnį, spustelkite "nebestebėti" viršutiniame meniu.',
'allarticles' => 'Visi straipsniai',
'allinnamespace' => 'Visi puslapiai (sritis - $1)',
'alllogstext' => 'Bendra įdėtų failų, ištrynimų, užrakinimų, blokavimų ir teisių suteikimų istorija.
Galima sumažinti rezultatų patikslinant veiksmo rūšį, vartotoją ar susijusį puslapį.',
'allmessages' => 'Visi sistemos tekstai bei pranešimai',
'allmessagescurrent' => 'Galiojanti reikšmė',
'allmessagesdefault' => 'Reikšmė pagal nutylėjimą',
'allmessagesname' => 'Pavadinimas',
'allmessagesnotsupportedDB' => 'Specialus:AllMessages nepalaikoma, nes nustatymas wgUseDatabaseMessages yra išjungtas.',
'allmessagesnotsupportedUI' => 'Jūsų pasirinkta kalba (<b>$1</b>) nėra palaikoma šiame projekte.',
'allmessagestext' => 'Čia pateikiami visi sisteminiai tekstai bei pranešimai, esantys MediaWiki: vardų ervėje.',
'allnotinnamespace' => 'Visi puslapiai (nesantys šioje srityje - $1)',
'allpages' => 'Visi straipsniai',
'allpagesfrom' => 'Rodyti puslapius pradedant nuo:',
'allpagesnext' => 'Kitas (tolimesnis)',
'allpagesprev' => 'Ankstesnis',
'allpagessubmit' => 'Tinka',
'alphaindexline' => 'Nuo $1 iki $2',
'already_bureaucrat' => 'Vartotojas jau yra biurokratas',
'already_sysop' => 'Vartotojas jau yra administratorius',
'alreadyloggedin' => '<strong>Jūs jau esate prisijungęs kaip vartotojas User $1!</strong><br />',
'alreadyrolled' => 'Nepavyko atmesti paskutinio [[Vartotojas:$2|$2]] ([[Vartotojo aptarimas:$2|aptarimas]]) daryto straipsnio [[$1]] keitimo; kažkas jau pakeitė straipsnį arba suspėjo pirmas atmesti keitimą.

Paskutimas keitimas darytas vartotojo [[Vartotojas:$3|$3]] ([[Vartotojo aptarimas:$3|Aptarimas]]).',
'ancientpages' => 'Seniausi straipsniai',
'and' => 'ir',
'anontalk' => 'Šio IP aptarimų puslapis',
'anontalkpagetext' => '----\'\'Tai yra anoniminio vartotojo, nesusikūrusio arba nenaudojančio vartotojo vardo, aptarimų puslapis. Dėl to naudojamas [[IP adresas]] jo identifikavimui, kuris gali būti dalinamas keliems vartotojams. Jeigu Jūs esate anoniminis (neregistruotas) vartotojas ir atrodo, kad komentarai nėra skirti Jums, [[Special:Userlogin|užsiregistruokite]], ir nebūsite tapatinamas su kitais anonimais.\'\'',
'anonymous' => 'Neregistruotas vartotojas',
'apr' => 'Bal',
'april' => 'Balandžio',
'article' => 'Turinys',
'articleexists' => 'Straipsnis tokiu pavadinimu jau egzistuoja
arba pasirinktas vardas yra neteisingas.
Pasirinkite kitą pavadinimą.',
'articlepage' => 'Rodyti straipsnį',
'aug' => 'Rgp',
'august' => 'Rugpjūčio',
'autoblocker' => 'Automatinis užblokavimas, nes dalinatės IP adresu su vartotoju "$1". Priežastis - "$2".',
'badaccess' => 'Teisių klaida',
'badarticleerror' => 'Veiksmas negalimas šiam puslapiui.',
'badfilename' => 'Paveiksliukas buvo pervadintas į "$1".',
'badfiletype' => '".$1" yra nerekomenduojamas paveikslėlio bylos formatas.',
'badipaddress' => 'Neteisingas IP adresas',
'badquery' => 'Bloga paieškos užklausa',
'badquerytext' => 'Nepavyko apdoroti Jūsų paieškos užklausos.
Tai galėjo būti dėl trumpesnio nei trijų simbolių paieškos rakto, arba neteisingai suformuotos užklausos (pavyzdžiui "namas and and tvartas").
Pamėginkite kitokią užklausą.',
'badretype' => 'Įvesti slaptažodžiai nesutampa.',
'badtitle' => 'Blogas pavadinimas',
'badtitletext' => 'Nurodytas puslapio pavadinimas buvo neteisingas, tuščias arba neteisingai sujungtas tarp-kalbinis arba tarp-wiki pavadinimas.',
'blanknamespace' => '(straipsniai)',
'blockedtext' => 'Jūsų vartotojo vardą arba IP adresą užblokavo $1.
Nurodyta priežastis:<br />\'\'$2\'\'<p>Jūs galite susisiekti su $1 arba kuriuo nors kitu
[[{{ns:project}}:Administrators|administratoriumi]] aptarti neaiškumus dėl blokavimo.

Atkreipkite dėmesį, kad negalėsite išsiųsti el. laiško, jei nesate užsiregistravę ir pateikę realaus savo el. pašto adreso vartotojo [[Special:Preferences|nustatymuose]].

Jūsų IP adresas yra $3. Prašome nurodyti šį adresą visais atvejais kai kreipiatės dėl blokavimo.',
'blockedtitle' => 'Vartotojas yra blokuotas',
'blockip' => 'Blokuoti vartotoją',
'blockipsuccesssub' => 'Užblokavimas pavyko',
'blockipsuccesstext' => '"$1" buvo užblokuotas.
<br />Aplankykite [[Special:Ipblocklist|IP blokų sąrašą]] norėdami jį peržiūrėti.',
'blockiptext' => 'Naudokite šią formą, norėdami uždrausti rašymo teises iš nurodytų IP adresų ar vartotojų. Tai turėtų būti atliekama tiktai sustabdyti vandalizmui, bei priderinant [[{{ns:project}}:Policy|politiką]].
Nurodykite tikslią priežastį apačioje (pavyzdžiui nurodydami sugadintus puslapius).',
'blocklink' => 'blokuoti',
'blocklistline' => '$1, $2 blokavo $3 (galioja iki $4)',
'blocklogentry' => 'blokavo "$1", blokavimo laikas - $2',
'blocklogpage' => 'Blokavimų_sąrašas',
'blocklogtext' => 'Čia yra vartotojų blokavimo ir atblokavimo registras. Automatiškai blokuoti IP adresai neišvardinti.  Jei norite pamatyti dabar blokuojamus adresus, žiūrėkite [[Special:Ipblocklist|IP blokavimų sąrašą]].',
'bold_sample' => 'Paryškintas tekstas',
'bold_tip' => 'Paryškinti tekstą',
'booksources' => 'Knygų paieška',
'brokenredirects' => 'Peradresavimai į niekur',
'brokenredirectstext' => 'Žemiau išvardinti peradresavimo puslapiai rodo į neegzistuojančius straipsnius.',
'bugreports' => 'Pranešti apie klaidą',
'bugreportspage' => 'Projektas:Klaidų_fiksavimas',
'bydate' => 'pagal datą',
'byname' => 'pagal vardą',
'bysize' => 'pagal dydį',
'cachederror' => 'Pateiktas išsaugota prašomo puslapio kopija, ji gali būti netiksli.',
'cancel' => 'Atšaukti',
'cannotdelete' => 'Nepavyko ištrinti nurodyto straipsnio ar paveikslėlio. (Gali būti, kad kažkas kitas ištrynė pirmas)',
'cantrollback' => 'Negalima atmesti redagavimo; paskutinis keitęs vartotojas yra vienintelis straipsnį redagavęs autorius.',
'categories' => 'Kategorijos',
'categoriespagetext' => 'Projekte yra šios kategorijos.',
'category_header' => 'Kategorijos "$1" straipsniai',
'categoryarticlecount' => 'Kategorijoje straipsnių - $1',
'changed' => 'pakeitė',
'changepassword' => 'Pakeisti slaptažodį',
'changes' => 'pasikeitimai',
'clearyourcache' => '\'\'\'Dėmesio:\'\'\' Išsaugoję turite išvalyti naršyklės spartinančią saugyklą (cache): \'\'\'Mozilla/Safari/Konqueror:\'\'\' spausdami \'\'Shift\'\' pasirinkite \'\'reload\'\' (arba \'\'Ctrl-Shift-R\'\'), \'\'\'IE:\'\'\' \'\'Ctrl-F5\'\', \'\'\'Opera:\'\'\' \'\'F5\'\'.',
'columns' => 'Stulpeliai',
'compareselectedversions' => 'Palyginti pasirinktas versijas',
'confirm' => 'Tvirtinu',
'confirmdelete' => 'Trynimo veiksmo patvirtinimas',
'confirmdeletetext' => 'Jūs pasirinkote ištrinti straipsnį ar paveikslėlį
kartu su visa istorija iš duomenų bazės.
Prašome patvirtinti kad jūs norite tai padaryti,
žinote kokios yra veiksmo pasekmės,
ir kad jūs tai darote nenusižengdamas
[[{{ns:project}}:Policy|{{SITENAME}}jos Politikai]].', // TODO: grammar
'confirmemail' => 'Patvirtinkite el.pašto adresą',
'confirmemail_body' => 'Kažkas (tikriausiai jūs) IP adresu užregistravo
vartotoją "$2" susietą su šiuo el.pašto adresu projekte {{SITENAME}}.

Kad užtikrinti el.pašto priklausomybę registravusiam naudotojui
ir aktyvuoti su šiuo adresu susijusias projekto galimybes,
atverkite šią nuorodą savo naršyklėje:

$3

Jei naudotoją registravote *ne* jūs, neatidarykite šio adreso. Patvirtinimo kodas
baigs galioti $4.',
'confirmemail_error' => 'Patvirtinimo metu įvyko neatpažinta klaida.',
'confirmemail_invalid' => 'Neteisingas patvirtinimo kodas. Kodo galiojimas gali būti jau pasibaigęs.',
'confirmemail_loggedin' => 'Jūsų el.pašto adresas patvirtintas.',
'confirmemail_send' => 'Išsiųsti patvirtinimo kodą',
'confirmemail_sendfailed' => 'Nepavyko išsiųsti patvirtinimo kodo. Patikrinkite, ar adrese nėra klaidingų simbolių.',
'confirmemail_sent' => 'Patvirtinimo laiškas išsiųstas.',
'confirmemail_subject' => '{{SITENAME}} el.pašto adreso patvirtinimas',
'confirmemail_success' => 'Jūsų el.pašto adresas patvirtintas. Dabar galite prisijungti ir mėgautis wiki.',
'confirmemail_text' => 'Šiame projekte būtina patvirtinti el.pašto adresą
prieš naudojant su el.paštu susijusias galimybes. Spustelkite žemiau esantį mygtuką,
kad jūsų el.pašto adresu būtų išsiųstas patvirtinimo kodas.
Laiške bus atsiųsta nuoroda su kodu, kuria nuėjus, el.pašto adresas bus patvirtintas.',
'confirmprotect' => 'Užrakinimo patvirtinimas',
'confirmprotecttext' => 'Ar jūs tikrai norite užrakinti šį straipsnį?',
'confirmrecreate' => 'Naudotojas [[Naudotojas:$1|$1]] ([[Naudotojo aptarimas:$1|aptarimas]]) ištrynė šį puslapį po to, kai pradėjote jį redaguoti. Trynimo priežąstis:
: \'\'$2\'\'
Prašome patvirtinti, kad tikrai norite iš naujo sukurti straipsnį.',
'confirmunprotect' => 'Atrakinimo patvirtinimas',
'confirmunprotecttext' => 'Ar tikrai norite atrakinti šį straipsnį?',
'contextchars' => 'Konteksto simbolių eilutėje',
'contextlines' => 'Eilučių rezultate',
'contribslink' => 'įnašas',
'contribsub' => 'Vartotojo $1',
'contributions' => 'Vartotojo indėlis',
'copyright' => 'Turinys pateikiamas su $1 licencija.',
'copyrightpage' => '{{ns:project}}:Copyrights',
'copyrightpagename' => '{{SITENAME}} copyright',
'copyrightwarning' => 'ūsų pakeitimai įsigalios iškart.</div>
* Jei norite tik išmėginti redagavimą, naudokite [[{{ns:project}}:Sandbox|smėlio dėžę]].
* Kūrimas, redagavimas ir tobulinimas yra skatinami; tačiau, netikę keitimai bus greitai atmesti.
* \'\'\'Nepamirškite \'\'cituoti šaltinių\'\', kad kiti galėtų įsitikinti, kad pakeitimai teisingi.
----
NEKOPIJUOKITE AUTORINĖMIS TEISĖMIS APSAUGOTŲ DARBŲ BE LEIDIMO!

*Viskam, kas patenka į projektąą, yra taikoma GNU Laisvos Documentacijos Licencija (detaliau - $1).
*\'\'\'Jei nepageidaujate, kad jūsų įvestas turinys būtų negailestingai redaguojamas ir platinamas, nerašykite čia.\'\'\'
* Jūs taip pat pasižadate, kad tai jūsų pačių rašytas turinys arba kopijuotas iš viešų ar panašių nemokamų šaltinių (dauguma internetinių puslapių nepatenka į viešų šaltinių kategoriją).',
'couldntremove' => 'Nepavyko pašalinti \'$1\'...',
'createaccount' => 'Sukurti vartotoją',
'createaccountmail' => 'el.paštu',
'createarticle' => 'Kurti straipsnį',
'created' => 'sukurta',
'cur' => 'dab',
'currentevents' => '-',
'currentevents-url' => '-',
'currentrev' => 'Dabartinė versija',
'currentrevisionlink' => 'žiūrėti esamą versiją',
'data' => 'Duomenys',
'databaseerror' => 'Duomenų bazės klaida',
'dateformat' => 'Datos formatas',
'dberrortext' => 'Įvyko duomenų bazės užklausos klaida.
Tai galėjo būti dėl neteisingos paieškos užklausos (žiūr. $5),
arba dėl klaidos programinėje įrangoje.
Paskutinė mėginta duomenų bazės užklausa buvo:
<blockquote><tt>$1</tt></blockquote>
iš funkcijos: "<tt>$2</tt>".
Klaida: "<tt>$3: $4</tt>".',
'dberrortextcl' => 'Įvyko duomenų bazės užklausos klaida.
Paskutinė mėginta duomenų bazės užklausa buvo:

    $1

iš funkcijos: "$2". Klaida: "$3: $4".',
'deadendpages' => 'Straipsniai-aklavietės',
'dec' => 'Gru',
'december' => 'Gruodžio',
'defaultns' => 'Pagal nutylėjimą ieškoti šiose vardų erdvėse:',
'defemailsubject' => '{{SITENAME}} e-mail',
'delete' => 'trinti',
'delete_and_move' => 'Perkelti ištrinant',
'delete_and_move_reason' => 'Ištrinta perkeliant Deleted',
'deletecomment' => 'Trynimo priežastis',
'deletedarticle' => 'ištrynė "$1"',
'deletedrev' => '[ištrinta]',
'deletedrevision' => 'Ištrinta sena versija $1.',
'deletedtext' => 'Straipsnis "$1" ištrintas.
Šalinimų sąrašas - $2.',
'deletedwhileediting' => 'Dėmesio: Šis puslapis ištrintas po to, kai pradėjote redaguoti!',
'deleteimg' => 'trinti',
'deleteimgcompletely' => 'Ištrinti visas versijas',
'deletepage' => 'Trinti puslapį',
'deletesub' => '(Trinama "$1")',
'deletethispage' => 'Ištrinti straipsnį',
'deletionlog' => 'šalinimų sąrašas',
'dellogpage' => 'Šalinimų_sąrašas',
'dellogpagetext' => 'Žemiau pateikiamas sąrašas paskutiniu metu pašalintų straipsnių.',
'destfilename' => 'Norimas failo vardas',
'diff' => 'skirt',
'difference' => '(Skirtumai tarp versijų)',
'disambiguations' => 'Nukreipiamieji puslapiai',
'disambiguationstext' => 'Žemiau išvardinti straipsniai, rodantys į <i>nukreipiamuosius puslapius</i>. Nuorodos turėtų būti patikslintos kad rodytų į konkretų straipsnį.<br />Puslapis skaitomas nukreipiamuoju, jei nuoroda į jį yra $1.<br />',
'disclaimers' => 'Jokių Garantijų',
'doubleredirects' => 'Dvigubi peradresavimai',
'doubleredirectstext' => 'Kiekvienoje eilutėje išvardintas pirmasis ir antrasis peradresavimai, taip pat pirma antrojo peradresavimo eilutė, paprastai rodanti į "teisingą" puslapį, į kurį turi būti rodoma.',
'download' => 'parsisiųsti',
'edit' => 'Redaguoti',
'edit-externally' => 'Atidaryti išoriniame redaktoriuje',
'edit-externally-help' => 'Žiūrėkite [http://meta.wikimedia.org/wiki/Help:External_editors diegimo instrukcijas] (angl.).',
'editcomment' => 'Redagavimo komentaras: "<i>$1</i>".',
'editconflict' => 'Išpręskite konfliktą: $1',
'editcurrent' => 'Redaguoti dabartinę puslapio versiją',
'edithelp' => 'Kaip Redaguoti',
'edithelppage' => 'Pagalba:Redagavimas',
'editing' => 'Taisomas straipsnis - $1',
'editingcomment' => 'Taisomas straipsnis - $1 (comment)',
'editingold' => '<strong>ĮSPĖJIMAS: Jūs keičiate ne naujausią puslapio versiją.
Jei išsaugosite savo keitimus, prieš tai daryti pakeitimai pradings.</strong>',
'editingsection' => 'Taisomas straipsnis - $1 (skyrius)',
'editsection' => 'taisyti',
'editold' => 'taisyti',
'editthispage' => 'Taisyti straipsnį',
'editusergroup' => 'Redaguoti naudotojo gruoes',
'email' => 'El. paštas',
'emailconfirmlink' => 'Patvirtinkite savo el.pašto adresą',
'emailfrom' => 'Nuo',
'emailmessage' => 'Tekstas',
'emailnotauthenticated' => 'Jūsų el.pašto adresas <strong>nėra patvirtintas</strong>. El.laiškas
nebus siunčiamas nei vienu žemiau išvardintų būdų.',
'emailpage' => 'Siųsti el.laišką',
'emailsend' => 'Siųsti',
'emailsent' => 'El.laiškas išsiųstas',
'emailsenttext' => 'Jūsų el.pašto žinutė išsiųsta.',
'emailsubject' => 'Tema',
'emailto' => 'Kam',
'emailuser' => 'Rašyti laišką',
'emptyfile' => 'Panašu, kad failas, kurį įkėlėte yra tuščias. Tai gali būti dėl klaidos failo pavadinime. Pasitikrinkite ar tikrai norite įkelti šitą failą.',
'enotif_body' => '$WATCHINGUSERNAME,

Projekto {{SITENAME}} puslapis $PAGETITLE buvo $CHANGEDORCREATED $PAGEEDITDATE vartotojo $PAGEEDITOR, dabartinę versiją rasite adresu $PAGETITLE_URL.

$NEWPAGE

Redaguotojo komentaras: $PAGESUMMARY $PAGEMINOREDIT

Susisiekti su redaguotoju:
el.paštu: $PAGEEDITOR_EMAIL
wiki: $PAGEEDITOR_WIKI

Daugiau pranešimų apie vėlesnius pakeitimus nebus siunčiama, jei neapsilankysite puslapyje. Jūs taip pat galite išjungti pranešimo žymę jūsų stebimiems puslapiams stebimų straipsnių puslapyje.

             Jūsų draugiškoji projekto {{SITENAME}} pranešimų sistema

--
Norėdami pakeisti stebimų puslapių nustatymus, užeikite į
{{SERVER}}{{localurl:Special:Watchlist/edit}}

Atsiliepimai ir pagalba:
{{SERVER}}{{localurl:Help:Contents}}',
'enotif_lastvisited' => 'Užeikite į $1, jei norite matyti pakeitimus nuo paskutiniojo apsilankymo.',
'enotif_mailer' => '{{SITENAME}} Pranešimų sistema',
'enotif_newpagetext' => 'Tai naujas puslapis.',
'enotif_reset' => 'Pažymėti visuspuslapius kaip aplankytus',
'enotif_subject' => '{{SITENAME}} projekte vartotojas $PAGEEDITOR $CHANGEDORCREATED $PAGETITLE',
'error' => 'Klaida',
'errorpagetitle' => 'Klaida',
'exbeforeblank' => 'turinys prieš ištrinant buvo:',
'exblank' => 'puslapis buvo tuščias',
'excontent' => 'buvęs turinys:',
'excontentauthor' => 'turinys: \'$1\' (redagavo tik \'$2\')',
'explainconflict' => 'Straipsnis jau buvo redaguotas nuo tada, kai jūs pradėjote redaguoti straipsnį.
Viršutiniame tekstiniame lauke pateikta šiuo metu projekte esanti straipsnio versija.
Jūsų keitimai pateikti žemiau esančiame lauke.
Kadangi automatiškai sulieti pakeitimų nepavyko, jums teks rankomis
sulieti savo darytus pakeitimus į dabartinę versiją.
Paspaudus "Išsaugoti", užsaugotas bus
<b>tik</b> tekstas viršutiniame tekstiniame lauke.<br />',
'export' => 'Eksportuoti puslapius',
'exportcuronly' => 'Eksportuoti tik dabartinę versiją, neįtraukiant istorijos',
'exporttext' => 'Galite eksportuoti vieno puslapio tekstą ir istoriją ar kelių puslapių vienu metu
tame pačiame XML atsakyme. Ateityje šie puslapiai galės būti importuojami į kitą
projektą, veikiantį MediaWiki pagrindu.

Norėdami eksportuoti puslapius, įveskite pavadinimus žemiau esančiame tekstiniame lauke
po vieną pavadinimą eilutėje, taip pat pasirinkite ar norite eksportuoti ir istoriją
ar tik dabartinę versiją su paskutinio redagavimo informacija.

Norėdami eksportuoti vieno puslapio dabartinę versiją, galite naudoti nuorodą, pvz. [[{{ns:Special}}:Export/Lietuva]]
straipsniui [[Lietuva]].',
'extlink_sample' => 'http://www.pavyzdys.lt Nuorodos pavadinimas',
'extlink_tip' => 'Išorinė nuoroda (nepamirškite http:// prefikso)',
'faq' => 'DUK',
'faqpage' => 'Projektas:DUK',
'feb' => 'Vas',
'february' => 'Vasario',
'filedesc' => 'Komentaras',
'fileexists' => 'Failas tuo pačiu vardu jau egzistuoja, prašome pažiūrėti $1 jei nesate tikras, ar norite perrašyti šį failą.',
'filename' => 'Failas',
'files' => 'Failai',
'fileuploaded' => 'Failas "$1" sėkmingai įkeltas.
Prašome nueiti šia nuoroda: $2 į failo aprašymo puslapį ir įrašyti informaciją apie failą, iš kokio šaltinio paimtas, kas jo autorius, bei kitą susijusią informaciją (taip pat ir licencijavimo tipą).',
'fileuploadsummary' => 'Komentaras:',
'formerror' => 'Klaida: nepavyko apdoroti formos duomenų',
'friday' => 'Penktadienis',
'go' => 'Rodyk',
'guesstimezone' => 'Paimti iš naršyklės',
'headline_sample' => 'Skyriaus Pavadinimas',
'headline_tip' => 'Skyriaus pavadinimas (2-o lygio)',
'help' => 'Pagalba',
'helppage' => 'Pagalba:turinys',
'hide' => 'Slėpti',
'hidetoc' => 'slėpti',
'hist' => 'ist',
'histfirst' => 'Seniausi',
'histlast' => 'Paskutiniai',
'histlegend' => 'Skirtumai tarp versijų: radijo mygtukais išsirinkite lyginamas versijas ir spustelkite \'\'Enter\'\' klavišą arba mygtuką, esantį apačioje.<br />
Žymėjimai: (dab) = palyginimas su naujausia versija,
(pask) = palyginimas su prieš tai buvusia versija, S = smulkus keitimas.',
'history' => 'Straipsnio istorija',
'history_short' => 'Istorija',
'historywarning' => 'Dėmesio: Trinamas puslapis turi istoriją:',
'hr_tip' => 'Horizontali linija (nepernaudoti)',
'illegalfilename' => 'Failo varde "$1" yra simbolių, netinkamų straipsnio pavadinimui. Prašome pervadint failą ir mėginti įkelti iš naujo.',
'ilsubmit' => 'Ieškoti',
'image_sample' => 'Pavyzdys.jpg',
'image_tip' => 'Įdėti paveiksėlį',
'imagelinks' => 'Paveikslėlio naudojimas',
'imagelist' => 'Paveikslėlių sąrašas',
'imagelistall' => 'visi',
'imagelisttext' => 'Žemiau yra paveikslėlių sąrašas (rodoma $1), surūšiuotas $2.',
'imagemaxsize' => 'Riboti rodomų paveikslėlių dydį:',
'imagepage' => 'Žiūrėti paveikslėlio puslapį',
'imagereverted' => 'Ankstesnės versijos atstatymas pavyko.',
'imgdelete' => 'trint',
'imgdesc' => 'apr',
'imghistlegend' => 'Žymėjimai: (dab) = dabartinė paveikslėlio versija, (trint) = ištrinti
seną versiją, (atst) = atstatyti seną versiją.
<br /><i>Spustelkite ant datos norėdami pažiūrėti tuo metu buvusią versiją</i>.',
'imghistory' => 'Paveikslėlio istorija',
'imglegend' => 'Legend: (apr) = žiūrėti/redaguoti paveikslėlio aprašymą.',
'import' => 'Importuoti puslapius',
'importnosources' => 'Nenustatyti importo šaltiniai, o tiesioginis importas uždraustas.',
'importsuccess' => 'Importas pavyko!',
'internalerror' => 'Nenustatyta vidinė klaida',
'invert' => 'Rodyti visas sritis išskyrus pasirinktąją',
'ipblocklist' => 'Blokuotų IP adresų bei vartotojų sąrašas',
'ipbreason' => 'Priežastis',
'ipbsubmit' => 'Blokuoti šį naudotoją',
'ipusubmit' => 'Atblokuoti šį adresą',
'isbn' => 'ISBN',
'isredirect' => 'nukreipiamasis',
'italic_sample' => 'Tekstas kursyvu',
'italic_tip' => 'Išskirti kursyvu',
'jan' => 'Sau',
'january' => 'Sausio',
'jul' => 'Lie',
'july' => 'Liepos',
'jun' => 'Bir',
'june' => 'Birželio',
'laggedslavemode' => 'Dėmesio: Straipsnyje gali nesimatyti naujausių pakeitimų.',
'last' => 'pask',
'lastmodified' => 'Paskutinį kartą keista $2, $1.',
'lineno' => 'Eilutė $1:',
'link_sample' => 'Straipsnio pavadinimas',
'link_tip' => 'Vidinė nuoroda',
'linklistsub' => '(Nuorodų sąrašas)',
'linkshere' => 'Šie straipsniai rodo į pasirinktąjį straipsnį:',
'linkstoimage' => 'Paveikslėlis naudojamas šiuose straipsniuose:',
'listingcontinuesabbrev' => ' tęs.',
'listusers' => 'Vartotojų sąrašas',
'loadhist' => 'Renkama straipsnio istorija',
'localtime' => 'Rodomas vietinis laikas',
'log' => 'Specialiųjų veiksmų istorija',
'login' => 'Prisijungti/Registruotis',
'loginerror' => 'Prisijungimo klaida',
'loginpagetitle' => 'Prisijungimas',
'loginproblem' => '<b>Problemos su jūsų prisijungimu.</b><br />Pabandykite iš naujo!',
'loginprompt' => '<!--Norėdami prisijungti prie Wikipedijos, privalote įsijungti \'\'\'cookies\'\'\' savo naršyklėje.-->',
'loginsuccess' => 'Šiuo metu jūs prisijungęs prie projekto kaip "$1".',
'loginsuccesstitle' => 'Sėkmingai prisijungėte',
'logout' => 'Atsijungti',
'logouttext' => 'Jūs atsijungėte nuo projekto.
Galite toliau naudoti projektą anonimiškai arba prisijunkite iš naujo tuo pačiu ar kitu vartotoju.<br />
P.S.:  kai kuriuose puslapiuose ir toliau gali rodyti lyg būtumėte prisijungęs iki tol, kol išvalysite savo naršyklės išsaugotas puslapių kopijas',
'lonelypages' => 'Vieniši straipsniai',
'longpages' => 'Ilgiausi puslapiai',
'mailmypassword' => 'Siųsti naują slaptažodį paštu',
'mainpage' => 'Pradžia',
'makesysop' => 'Padaryti administratoriumi',
'mar' => 'Kov',
'march' => 'Kovo',
'markaspatrolleddiff' => 'Žymėti kad patikrinta',
'markaspatrolledtext' => 'Pažymėti, kad straipsnis patikrintas',
'markedaspatrolled' => 'Uždėta žymė "Patikrinta"',
'markedaspatrolledtext' => 'Pasirinkta revizija sėkmingi pažymėta kaip patikrinta',
'math' => 'Matematika',
'math_sample' => 'Įveskite formulę',
'math_tip' => 'Matematinė formulė (LaTeX formatu)',
'may' => 'Geg',
'may_long' => 'Gegužės',
'minoredit' => 'Smulkus pataisymas',
'minoreditletter' => 'S',
'missingimage' => '<b>Trūkstamas paveikslėlis</b><br /><i>$1</i>',
'monday' => 'Pirmadienis',
'moredotdotdot' => 'Daugiau...',
'mostlinked' => 'Rodomiausi straipsniai',
'move' => 'Pervadinti',
'movearticle' => 'Straipsnio pervadinimas',
'movedto' => 'perkeltas į',
'movelogpage' => 'Perkėlimų sąrašas',
'movenologin' => 'Neprisijungęs',
'movenologintext' => 'Norėdami pervadinti puslapį, turite būti registruotas ir <a href="/wiki/Special:Userlogin">prisijungęs</a> vartotojas.',
'movepage' => 'Straipsnio pervadinimas',
'movepagebtn' => 'Pervadinti',
'movepagetalktext' => 'Straipsnio aptarimo puslapis (jei egzistuoja) bus automatiškai
perkeltas kartu su straipsniu, \'\'\'išskyrus,\'\'\' jei
*keičiate straipsnio vardų erdvę,
*straipsniui nauju pavadinimu jau egzistuoja netuščias aptarimo puslapis, arba
*paliksite žemiau esančia varnelę nepažymėtą.

Tokiu atveju jūs savo nuožiūra turite perkelti arba apjungti aptarimo puslapį.',
'movepagetext' => 'Naudodamiesi žemiau pateikta forma, pervadinsite straipsnį
neprarasdami jo istorijos.
Senas straipsnio pavadinimas taps nukreipiamuoju - rodys į naująjį.
Nuorodos į straipsnį nebus automatiškai pakeistos, todėl būtinai
[[Special:Maintenance|patikrinkite]] ar nesukūrėte dvigubų ar
neveikiančių nukreipimų.
Jūs esate atsakingas už tai, kad nuorodos rodytų į teisingą straipsnį.

Pažymėtina, kad puslapis nebus pervadintas, jei jau yra straipsnis
nauju pavadinimu, nebent tas straipsnis tuščias arba nukreipiamasis ir
neturi redagavimo istorijos. Taigi, jūs galite pervadinti straipsnį
seniau naudotu vardu, jei prieš tai jis buvo per klaidą pervadintas,
o egzistuojančių puslapių sugadinti negalite.

<b>DĖMESIO!</b>
Jei pervadinate populiarų straipsnį, tai gali sukelti nepageidaujamų
šalutinių efektų, dėl to šį veiksmą vykdykite tik įsitikinę,
kad suprantate visas pasekmes.',
'movereason' => 'Priežastis',
'movetalk' => 'Jei įmanoma, kartu perkelti aptarimo puslapį.',
'movethispage' => 'Pervadinti straipsnį',
'mw_math_html' => 'HTML kai įmanoma, kitaip - PNG',
'mw_math_mathml' => 'MathML jei įmanoma (eksperimentinis)',
'mw_math_modern' => 'Rekomenduojama modernioms naršyklėms',
'mw_math_png' => 'Visada formuoti PNG',
'mw_math_simple' => 'HTML paprastais atvejais, kitaip - PNG',
'mw_math_source' => 'Palikti TeX formatą (tekstinėms naršyklėms)',
'mycontris' => 'Mano įnašas',
'mypage' => 'Mano puslapis',
'mytalk' => 'mano aptarimas',
'namespace' => 'Vardų sritis:',
'namespacesall' => 'visos',
'navigation' => 'Navigacija',
'nbytes' => '$1 B',
'newarticle' => '(Naujas)',
'newarticletext' => 'Jūs patekote į neegzistuojančio straipsnio puslapį.
Norėdami sukurti straipsnį, pradėkite žemiau esančiame įvedimo lauke
(plačiau [[Help:Kaip pradėti puslapį|apie puslapių kūrimą]]).
Jei patekote čia per klaidą, paprasčiausiai spustelkite  naršyklės mygtuką \'atgal\' (\'\'\'back\'\'\').<br />
\'\'\'Nepamirškite\'\'\' straipsnio turinį pateikti taip, kad žmogus suprastu tekstą be konteksto (dažniausiai žmonės pateks į šį puslapį visai kitu keliu, nei patekote jūs).',
'newimages' => 'Naujausi paveikslėliai',
'newmessageslink' => 'naujų žinučių',
'newpages' => 'Naujausi straipsniai',
'newpassword' => 'Naujas slaptažodis',
'newtitle' => 'Naujas pavadinimas',
'newwindow' => '(atsidaro naujame lange)',
'next' => 'sekantis',
'nextdiff' => 'Sekantis pakeitimas →',
'nextn' => 'sekančius $1',
'nextpage' => 'Sekantis puslapis ($1)',
'nextrevision' => 'Sekanti versija→',
'nlinks' => '$1 k.',
'noarticletext' => '<div style="border: 1px solid #ccc; padding: 7px; background-color: #fff; color: #000">\'\'\'Projekte nėra straipsnio norimu pavadinimu.\'\'\'
* \'\'\'[{{SERVER}}{{localurl:{{NAMESPACE}}:{{PAGENAME}}|action=edit}} Sukurti straipsnį \'{{PAGENAME}}\']\'\'\'.
* [[{{ns:special}}:Search/{{PAGENAME}}|Ieškoti teksto {{PAGENAME}}]] kituose straipsniuose.
* [[wikt:{{NAMESPACE}}:{{PAGENAME}}|Tikrinti straipsnį {{PAGENAME}}]] Wikižodyne, susijusiame projekte.
* [[Commons:{{NAMESPACE}}:{{PAGENAME}}|Tikrinti straipsnį {{PAGENAME}}]] Commons projekte, kur saugomi nemokami paveiksėliai ir kita media informacija.
* [[{{ns:special}}:Allpages/{{PAGENAME}}|Žiūrėti straipsnių sąrašą]], pradedant \'{{PAGENAME}}\'.
----
* \'\'\'Jei sukūrėte šį straipsnį prieš keletą minučių ir matote šį pranešimą, tai gali būti dėl techninių trukdžių atnaujinant duomenų bazes.\'\'\' Prašome palaukti ir vėl patikrinti prieš bandant iš naujo kurti straipsnį.
* Jei anksčiau esate sukūręs straipsnį šiuo pavadinimu, jis gali būti ištrintas dėl įvairių priežasčių.
</div>',
'noexactmatch' => '<span style="font-size: 135%; font-weight: bold; margin-left: .6em">Straipsnis tiksliu įvestu pavadinimu neegzistuoja.</span>

<span style="display: block; margin: 1.5em 2em">
Galite \'\'\'[[:<nowiki>$1</nowiki>|sukurti straipsnį šiuo pavadinimu]]\'\'\'.

<span style="display:block; font-size: 89%; margin-left:.2em">Prieš kurdami straipsnį, atidžiai paieškokite, ar Projekte nėra panašaus straipsnio, kad išvengti besidubliuojančių straipsnių.</span>
</span>',
'nohistory' => 'Šiam straipsniui nėra versijų istorijos.',
'noimage' => 'Failas tokiu pavadinimu neegzistuoja. Jūs galite jį $1',
'noimage-linktext' => 'įkelti',
'nolinkshere' => 'Į šį puslapį nuorodų nėra.',
'nolinkstoimage' => 'Paveikslėlis nenaudojamas nei viename straipsnyje.',
'note' => '<strong>Pastaba:</strong>',
'notloggedin' => 'Neprisijungęs',
'nov' => 'Lap',
'november' => 'Lapkričio',
'nowatchlist' => 'Neturite nei vieno stebimo straipsnio.',
'nstab-category' => 'Kategorija',
'nstab-help' => 'Pagalba',
'nstab-image' => 'Paveikslėlis',
'nstab-main' => 'Straipsnis',
'nstab-media' => 'Failas',
'nstab-mediawiki' => 'Tekstas',
'nstab-special' => 'Specialus',
'nstab-template' => 'Šablonas',
'nstab-user' => 'Vartotojas',
'nstab-project' => 'Apie',
'oct' => 'Spa',
'october' => 'Spalio',
'oldpassword' => 'Senas slaptažodis',
'otherlanguages' => 'Kitomis kalbomis',
'pagemovedsub' => 'Pervadinta sėkmingai',
'pagemovedtext' => 'Straipsnis "[[$1]]" pervadintas. Naujas vardas - "[[$2]]".

\'\'\'Nepamirškite [[{{ns:Special}}:Whatlinkshere/$2|patikrinti]]\'\'\' ar perkeliant nebuvo sukurta dvigubų nukreipimų, o jei sukurta, prašome juos pataisyti.',
'perfcached' => 'Rodoma išsaugota duomenų kopija, todėl duomenys gali būti ne patys naujausi:',
'permalink' => 'Nuolatinė nuoroda',
'personaltools' => 'Asmeniniai įrankiai',
'portal' => 'Bendruomenė',
'portal-url' => '{{ns:project}}:Bendruomenė',
'postcomment' => 'Rašyti komentarą',
'preferences' => 'Nustatymai',
'prefixindex' => 'Rodyklė pagal pavadinimo pradžią',
'prefs-help-email' => '* El. paštas (neprivalomas): Leidžia kitiems pasiekti jus elektroniniu paštu, nesužinant adreso.',
'prefs-help-email-enotif' => 'Šis adresas tai pat naudojamas siųsti pranešimus, jei pasirinkote tokius pranešimus gauti.',
'prefs-help-realname' => '¹ Tikras vardas (neprivaloma): if you choose to provide it this will be used for giving you attribution for your work.',
'prefs-misc' => 'Įvairūs nustatymai',
'prefs-personal' => 'Vartotojo duomenys',
'prefs-rc' => 'Naujausių pasikeitimų vaizdavimas',
'preview' => 'Peržiūra',
'previewnote' => 'Nepamirškite, kad tai tik peržiūra, pakeitimai dar nėra išsaugoti!',
'previousdiff' => '← Prieš tai darytas keitimas',
'previousrevision' => '←Prieš tai buvusi versija',
'prevn' => 'ankstesnius $1',
'printableversion' => 'Versija spausdinimui',
'protect' => 'Užrakinti',
'protectcomment' => 'Rakinimo priežastis',
'protectedarticle' => 'užrakino $1',
'protectedpage' => 'Užrakintas puslapis',
'protectedpagewarning' => '<strong>DĖMESIO:  Šis puslapis yra užrakintas ir jį redaguoti gali tik administratoriaus teises turintys vartotojai. Nepamirškite laikytis
[[{{ns:project}}:Puslapių rakinimas|užrakintų puslapių]] taisyklių.</strong>',
'protectedtext' => 'Šis puslapis yra užrakintas, saugant jį nuo redagavimo;
tai gali būti padaryta dėl skirtingų priežasčių, plačiau -
[[{{ns:project}}:Puslapių rakinimas|Puslapių rakinimas]].

Jūs galite žiūrėti straipsnio turinį arba jį kopijuoti:',
'protectlogpage' => 'Rakinimų_sąrašas',
'protectlogtext' => 'Žemiau yra užrakinimų bei atrakinimų sąrašas.
Daugiau informacijos puslapyje [[{{ns:project}}:Puslapių rakinimas|Puslapių rakinimas]].',
'protectmoveonly' => 'Uždrausti tik perkėlimus',
'protectsub' => '(Rakinamas "$1")',
'protectthispage' => 'Rakinti šį puslapį',
'qbbrowse' => 'Naršymas',
'qbedit' => 'Redagavimas',
'qbfind' => 'Paieška',
'qbmyoptions' => 'Mano puslapiai',
'qbpageinfo' => 'Kontekstas',
'qbpageoptions' => 'Straipsnis',
'qbsettings' => 'Greitasis pasirinkimas',
'qbspecialpages' => 'Specialieji puslapiai',
'randompage' => 'Atsitiktinis straipsnis',
'rclinks' => 'Rodyti paskutinius $1 pakeitimų per paskutiniąsias $2 dienas(ų)<br />$3',
'rclistfrom' => 'Rodyti pakeitimus pradedant $1',
'rclsub' => '(straipsnių, pasiekiamų iš "$1")',
'rcnote' => 'Pateikiamas <strong>$1</strong> paskutinių pakeitimų sąrašas per paskutiniąsias <strong>$2</strong> dienas(ų).',
'rcnotefrom' => 'Žemiau yra pakeitimai pradedant <b>$2</b> (rodoma ne daugiau <b>$1</b> pakeitimų).',
'readonly' => 'Duomenų bazė užrakinta',
'readonlytext' => 'Enciklopedijos duomenų bazė šiuo metu yra užrakinta, todėl
negalima rašyti naujų straipsnių ar redaguoti senų,
paprastai duomenų bazė rakinama techninei profilaktikai,
taigi vėliau bus atrakinta ir enciklopedija grįš į senas vėžes.
Užrakinusiojo administratoriaus pateiktas rakinimo aprašymas:
<p>$1',
'recentchanges' => 'Naujausi keitimai',
'recentchangesall' => 'visos',
'recentchangescount' => 'Kiek pakeitimų rodoma naujausių keitimų sąraše',
'recentchangeslinked' => 'Susiję keitimai',
'redirectedfrom' => '(Nukreipta iš puslapio $1)',
'remembermypassword' => 'Atsiminti slaptažodį.',
'removechecked' => 'Išmesti pažymėtus straipsnius iš stebimų sąrašo',
'removedwatch' => 'Išmesta iš stebimų',
'removedwatchtext' => 'Straipsnis "$1" išmestas iš jūsų stebimų straipsnių sąrašo.',
'removingchecked' => 'Pasirinkti straipsniai išmetami iš stebimų sąrašo...',
'resetprefs' => 'Atšaukti nustatymus',
'restorelink' => 'ištrintų versijų: $1',
'resultsperpage' => 'Rezultatų puslapyje',
'retrievedfrom' => 'Rodomas puslapis "$1"',
'returnto' => 'Grįžti į $1.',
'retypenew' => 'Pakartokite naują slaptažodį',
'reupload' => 'Pakartoti įkėlimą',
'reuploaddesc' => 'Grįžti į įkėlimo formą.',
'revertimg' => 'atst',
'revertpage' => 'Atmestas $2 pakeitimas, grąžinta paskutinė versija (vartotojo $1 keitimas)',
'revhistory' => 'Straipsnio istorija',
'revisionasof' => '$1 versija',
'revnotfound' => 'Versija nerasta',
'revnotfoundtext' => 'Norima straipsnio versija nerasta.
Patikrinkite adresą (URL), kurio patekote į šį puslapį.',
'rightslogtext' => 'Pateikiamas vartotojų teisių pasikeitimų sąrašas.',
'rollback_short' => 'Atmesti',
'rollbacklink' => 'atmesti',
'rows' => 'Eilutės',
'saturday' => 'Šeštadienis',
'savearticle' => 'Išsaugoti',
'savedprefs' => 'Nustatymai sėkmingai išsaugoti.',
'savefile' => 'Išsaugoti failą',
'saveprefs' => 'Išsaugoti nustatymus',
'search' => 'Paieška',
'searchbutton' => 'Paieška',
'searchdisabled' => '<p style="margin: 1.5em 2em 1em">Projekto \'{{SITENAME}}\' paieška yra uždrausta dėl techninių kliūčių. Galite mėginti ieškoti Google paieškos sistemoje.
<span style="font-size: 89%; display: block; margin-left: .2em">Išorinėse paieškos sistemose (kaip Google) Vikipedijos gali būti šiek tiek pasenę duomenys.</span></p>',
'searchsubtitle' => 'Ieškoma "[[:$1]]"',
'searchsubtitleinvalid' => 'Ieškoma "$1"',
'searchresults' => 'Paieškos rezultatai',
'searchresultshead' => 'Paieškos nustatymai',
'searchresulttext' => 'Daugiau informacijos apie paiešką {{SITENAME}} projekte rasite - [[Help:Searching|Paieška projekte]].',
'sep' => 'Rgs',
'september' => 'Rugsėjo',
'servertime' => 'Serverio laikas yra',
'shortpages' => 'Trumpiausi straipsniai',
'show' => 'Rodyti',
'showbigimage' => 'Rodyti geresnės raiškos versiją ($1x$2, $3 KB)',
'showdiff' => 'Rodyti skirtumus',
'showingresults' => 'Rodoma <b>$1</b> rezultatų pradedant #<b>$2</b>.',
'showlast' => 'Rodyti paskutinius $1 paveikslėlių, rūšiuojant $2.',
'showpreview' => 'Kaip atrodys',
'showtoc' => 'rodyti',
'sitestats' => 'Tinklalapio statistika',
'sitestatstext' => 'Duomenų bazėje šiuo metu esančių puslapių - \'\'\'$1\'\'\'.
Į šį skaičių įeina aptarimų puslapiai, pagalbiniai projekto puslapiai, peradresavimo puslapiai ir kiti, neskaičiuojami kaip straipsniai.
Be šių puslapių, tikrų straipsnių yra apie \'\'\'$2\'\'\'.

Nuo wiki pradžios yra atlikta \'\'\'$4\'\'\' puslapių redagavimų - vidutiniškai kiekvienas puslapis keistas \'\'\'$5\'\'\' kartų.

<!-- peržiūrėta: $3 vidutiniškai puslapiui: $6 -->',
'sitesupport' => 'Parama',
'skin' => 'Išvaizda',
'sourcefilename' => 'Įkeliamas failas',
'speciallogtitlelabel' => 'Pavadinimas:',
'specialloguserlabel' => 'Vartotojas:',
'specialpage' => 'Specialusis Puslapis',
'specialpages' => 'Specialieji puslapiai',
'spheading' => 'Specialieji visiems vartotojams prieinami puslapiai',
'statistics' => 'Statistika',
'storedversion' => 'Išsaugota versija',
'subcategories' => 'Subkategorijos',
'subcategorycount' => 'Kategorijoje esančių kategorijų - $1',
'subject' => 'Tema/antraštė',
'successfulupload' => 'Įkelta sėkmingai',
'summary' => 'Komentaras',
'sunday' => 'Sekmadienis',
'tagline' => 'Straipsnis iš {{SITENAME}}.',
'talk' => 'Aptarimas',
'talkexists' => 'Straipsnis sėkmingai pervadintas, bet
aptarimų puslapis nebuvo perkeltas, kadangi naujo
pavadinimo straipsnis jau turėjo aptarimų puslapį.
Prašome sujungti šiuos puslapius.',
'talkpage' => 'Aptarti straipsnį',
'talkpagemoved' => 'Susietas aptarimų puslapis perkeltas.',
'talkpagenotmoved' => 'Susietas aptarimų puslapis <strong>nebuvo</strong> perkeltas.',
'templatesused' => 'Straipsnyje naudojami šablonai:',
'textboxsize' => 'Redagavimo dėžė',
'thisisdeleted' => 'Žiūrėti ar trinti $1?',
'thumbsize' => 'Thumbnail paveikslėlių dydis :',
'thursday' => 'Ketvirtadienis',
'timezonelegend' => 'Laiko juosta',
'timezoneoffset' => 'Skirtumas',
'timezonetext' => 'Įveskite kiek valandų jūsų vietinis laikas skiriasi nuo serverio laiko (UTC).',
'toc' => 'Turinys',
'tog-editondblclick' => 'Puslapių redagavimas dvigubu spustelėjimu puslapyje (JavaScript)',
'tog-editsection' => 'Įjungti skyrelių redagavimą (naudojant nuorodas [taisyti])',
'tog-editsectiononrightclick' => 'Įjungti skyrelių redagavimą dvigubu spustelėjimu ant skyrelio pavadinimo (JavaScript)',
'tog-editwidth' => 'Redagavimas pilnu pločiu',
'tog-externaldiff' => 'Pagal nutylėjimą naudoti išorinę skirtumų rodymo programą',
'tog-externaleditor' => 'Pagal nutylėjimą naudoti išorinį redaktorių',
'tog-fancysig' => 'Parašas be automatinių nuorodų',
'tog-hideminor' => 'Slėpti smulkius pakeitimus naujausių keitimų sąraše',
'tog-highlightbroken' => 'Formuoti nesančių straipsnių nuorodas <a href="" class="new">šitaip</a> (priešingai - šitaip<a href="" class="internal">?</a>).',
'tog-justify' => 'Lygiuoti pastraipas pagal abi puses',
'tog-minordefault' => 'Pagal nutylėjimą pažymėti redagavimus kaip smulkius',
'tog-nocache' => 'Nenaudoti puslapių kaupimo (caching)',
'tog-numberheadings' => 'Automatiškai numeruoti skyrelius',
'tog-previewonfirst' => 'Rodyti straipsnio peržiūrą pirmu redagavimu',
'tog-previewontop' => 'Rodyti peržiūrimą vaizdą virš redagavimo lauko',
'tog-rememberpassword' => 'Atsiminti slaptažodį tarp sesijų',
'tog-showtoc' => 'Rodyti turinį, jei straipsnyje daugiau nei 3 skyreliai',
'tog-showtoolbar' => 'Rodyti redagavimo \'įrankinę\'',
'tog-underline' => 'Pabraukti nuorodas',
'tog-usenewrc' => 'Pažangiai rodomi naujausi keitimai (veikia ne visose naršyklėse)',
'tog-watchdefault' => 'Pridėti redaguojamus straipsnius į stebimų sąrašą',
'toolbox' => 'Įrankiai',
'tooltip-compareselectedversions' => 'Žiūrėti dviejų pasirinktų puslapio versijų skirtumus. [alt-v]',
'tooltip-minoredit' => 'Pažymėti keitimą kaip smulkų [alt-i]',
'tooltip-preview' => 'Pakeitimų peržiūra, labai prašome pažiūrėti prieš išsaugant! [alt-p]',
'tooltip-save' => 'Išsaugoti pakeitimus [alt-s]',
'tooltip-search' => 'Ieškoti lietuviškame wiki [alt-f]',
'tooltip-watch' => 'Pridėti šį straipsnį prie stebimų [alt-w]',
'trackbackbox' => '<div id=\'mw_trackbacks\'>
Trackbacks for this article:<br />
$1
</div>',
'tuesday' => 'Antradienis',
'uctop' => ' (paskutinis)',
'uncategorizedcategories' => 'Kategorijos, nepriskirtos jokiai kategorijai',
'uncategorizedpages' => 'Puslapiai, nepriskirti jokiai kategorijai',
'undelete' => 'Atstatyti ištrintą puslapį',
'undelete_short' => 'Atstatyti $1 redagavimus',
'undeletearticle' => 'Atstatyti ištrintą straipsnį',
'undeletebtn' => 'Atstatyti!',
'undeletedarticle' => 'atstatyta "[[$1]]"',
'undeletedrevisions' => 'atstatyta $1 revizijų',
'undeletehistory' => 'Jei atstatysite straipsnį, istorijoje bus atstatytos visos versijos.
Jei po ištrynimo buvo sukurtas straipsnis tokiu pačiu pavadinimu,
atstatytos versijos atsiras ankstesnėje istorijoje, o dabartinė versija
liks nepakeista.',
'undeletepage' => 'Ištrintų straipsnių peržiūra ir atstatymas',
'undeletepagetext' => 'Žemiau išvardinti ištrinti straipsniai ir puslapiai, dar laikomi
archyve, todėl jie gali būti atstatyti. Archyvas gali būti periodiškai valomas.',
'undeleterevision' => 'Ištrinta $1 dienos versija',
'underline-always' => 'Visada',
'underline-default' => 'Pagal naršyklės nustatymus',
'underline-never' => 'Niekada',
'unprotect' => 'Atrakinti',
'unprotectcomment' => 'Atrakinimo priežastis',
'unprotectedarticle' => 'atrakino $1',
'unprotectsub' => '(Atrakinamas "$1")',
'unusedcategories' => 'Nenaudojamos kategorijos',
'unusedimages' => 'Nenaudojami paveikslėliai',
'unwatch' => 'Nebestebėti',
'unwatchthispage' => 'Nustoti stebėti',
'upload' => 'Įkelti failą',
'uploadbtn' => 'Įkelti',
'uploadedimage' => 'įkėlė "$1"',
'uploaderror' => 'Įkėlimo klaida',
'uploadlog' => 'įkėlimų sąrašas',
'uploadlogpage' => 'Įkėlimų_sąrašas',
'uploadlogpagetext' => 'Žemiau pateikiamas naujausių failų įkėlimų sąrašas.',
'uploadnewversion-linktext' => 'Įkelti naują failo versiją',
'uploadnologin' => 'Reikia prisijungti',
'uploadnologintext' => 'Norėdami įkelti failą, turite būti [[Special:Userlogin|prisijungęs]].',
'uploadtext' => '<div style="border: 1px solid grey; background: #ddf; padding: 5px; margin: 0 auto;">
[[Image:Commons without text.png|left|30px|]] <big>Viešo naudojimo ir [[GFDL]] paveikslėlius \'\'\'labai rekomenduojama\'\'\' kelti į [[commons:Main|Commons]] projektą - šiame projekte įkeltus paveikslėlius galės naudoti ne tik lietuviškas, bet ir kiti projektai, taip sutaupysite laiko kitų projektų dalyviams.</big></div>

\'\'\'STOP!\'\'\' Prieš įkeldami failą
būtinai perskaitykite [[Help:Paveiklėliai|paveikslėlių naudojimo politiką]].

Norėdami peržiūrėti anksčiau įkeltus paveikslėlius,
eikite į [[Special:Imagelist|įkeltų paveikslėlių sąrašą]].
Įkėlimai ir trynimai yra fiksuojami [[Project:Upload log|įkėlimų istorijoje]].

Naudodamiesi žemiau pateikta forma, galite įkelti paveikslėlius,
kuriuos vėliau panaudosite straipsnių iliustravimui.
Daugumoje naršyklių yra "Browse..." mygtukas, kuris
atidarys standartinį operacinės sistemos failo pasirinkimo dialogą.
Pasirinkus reikiamą failą, failo vardo laukas (šalia mygtuko) automatiškai bus užpildytas.
Taip pat būtina pažymėti varnelę, patvirtinančią, kad nepažeidžiamos autorinės teisės.
Mygtukas "Įkelti" inicijuoja įkėlimo veiksmą.
Jei jūsų interneto ryšys nėra greitas, įkėlimas gali šiek tiek užtrukti.

Rekomenduojami formatai yra: JPEG - fotografijoms, PNG -
schemoms ir kitiems ikoniniams paveikslėliams, OGG - garsams.
Prašome failus vardinti vienareikšmiai, kad nekiltų painiavos.
Norėdami panaudoti įkeltą paveikslėlį straipsnyje, naudokite tokio tipo nuorodas -
\'\'\'<nowiki>[[{{ns:6}}:file.jpg]]</nowiki>\'\'\' arba
\'\'\'<nowiki>[[{{ns:6}}:file.png|paveikslėlio pavadinimas]]</nowiki>\'\'\' arba
\'\'\'<nowiki>[[{{ns:-2}}:file.ogg]]</nowiki>\'\'\' garsams.

Nepamirškite, kad kaip ir visame wiki, kiti gali redaguoti ar ištrinti jūsų įkeltus failus,
jei jie mano, kad taip bus geriau projektui. Taip pat jums gali būti uždrausta įkelti failus,
jei sistema naudositės nesilaikydami reikalavimų.

<div style="border: 1px solid grey; background: #ddf; padding: 7px; margin: 0 auto;">Prašome nepamiršti:

*Detaliai aprašyti įkeliamą failą.
*Nurodyti failo licenciją. Pridėdami <tt>&#123;{GFDL}}</tt> pažymėsite kad failas pateikiamas su [[{{ns:project}}:Text of the GNU Free Documentation License|GNU FDL]] licencija, <tt>&#123;{PD}}</tt> - jei [[w:Public domain|viešo naudojimo]], taip pat reiktų aprašyti išimtis ar kitokio tipo licencijas.
</div>',
'uploadwarning' => 'Dėmesio',
'userlogin' => 'Prisijungti',
'userlogout' => 'Atsijungti',
'userpage' => 'Vartotojo puslapis',
'userrights-user-editname' => 'Enter a username:',
'userstats' => 'Vartotojų statistika',
'userstatstext' => 'Šiuo metu registruotų vartotojų - \'\'\'$1\'\'\'.
Iš jų administratoriaus teises turi - \'\'\'$2\'\'\' (žr. $3).',
'version' => 'Versija',
'viewprevnext' => 'Žiūrėti ($1) ($2) ($3).',
'views' => 'Žiūrėti',
'viewsource' => 'Žiūrėti kodą',
'wantedpages' => 'Geidžiamiausi straipsniai',
'watch' => 'Stebėti',
'watchdetails' => '* Stebimų straipsnių - $1 (aptarimų puslapiai neskaičiuojami)
* [[Special:Watchlist/edit|Parodyti ir redaguoti pilną sąrašą]]',
'watcheditlist' => 'Žemiau pateiktame stebimų straipsnių sąraše
pažymėkite varneles prie straipsnių,
kurių nebenorite stebėti ir spauskite apačioje
esantį mygtuką \'Išmesti iš stebimų\'.',
'watchlist' => 'Stebimi straipsniai',
'watchlistcontains' => 'Straipsnių jūsų stebimųjų straipsnių sąraše - $1.',
'watchmethod-list' => 'ieškoma naujausių keitimų stebimuose puslapiuose',
'watchnochange' => 'Pasirinktu laikotarpiu nebuvo redaguotas nei vienas stebimas straipsnis.',
'watchthis' => 'Stebėti straipsnį',
'watchthispage' => 'Stebėti puslapį',
'wednesday' => 'Trečiadienis',
'whatlinkshere' => 'Susiję straipsniai',
'wlnote' => 'Rodomi paskutiniai $1 pakeitimai, padaryti per paskutines <b>$2</b> valandas.',
'wlshowlast' => 'Rodyti paskutinių $1 valandų, $2 dienų ar $3 pakeitimus',
'wrongpassword' => 'Įvestas neteisingas slaptažodis. Pamėginkite dar kartą.',
'yourdiff' => 'Skirtumai',
'youremail' => 'El. pašto adresas*',
'yourlanguage' => 'Interfeiso kalba',
'yourname' => 'Jūsų vartotojo vardas',
'yournick' => 'Jūsų slapyvardis (parašams)',
'yourpassword' => 'Pasirinktas slaptažodis',
'yourpasswordagain' => 'Pakartokite slaptažodį',
'yourrealname' => 'Jūsų tikras vardas*',
'yourtext' => 'Jūsų tekstas',
);
?>
