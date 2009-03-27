<?php
/** Serbo-Croatian (Srpskohrvatski / Српскохрватски)
 *
 * @ingroup Language
 * @file
 *
 * @author OC Ripper
 * @author לערי ריינהארט
 */

$messages = array(
# User preference toggles
'tog-rememberpassword' => 'Upamti moju lozinku na ovom kompjuteru za buduće posjete',

# Dates
'january'   => 'januar',
'february'  => 'februar',
'march'     => 'mart',
'april'     => 'april',
'may_long'  => 'maj',
'june'      => 'jun',
'july'      => 'jul',
'august'    => 'august',
'september' => 'septembar',
'october'   => 'oktobar',
'november'  => 'novembar',
'december'  => 'decembar',

# Categories related messages
'pagecategories'  => '{{PLURAL:$1|Kategorija|Kategorije}}',
'category_header' => 'Stranice u kategoriji "$1"',
'subcategories'   => 'Potkategorije',

'qbfind'     => 'Pronađite',
'navigation' => 'Navigacija',

'errorpagetitle'   => 'Greška',
'returnto'         => 'Povratak na $1.',
'tagline'          => 'Izvor: {{SITENAME}}',
'help'             => 'Pomoć',
'search'           => 'Pretraga',
'searchbutton'     => 'Traži',
'searcharticle'    => 'Idi',
'history_short'    => 'Historija',
'printableversion' => 'Verzija za ispis',
'permalink'        => 'Trajni link',
'edit'             => 'Uredi',
'editthispage'     => 'Uredite ovu stranicu',
'delete'           => 'Obriši',
'protect'          => 'Zaštiti',
'talkpage'         => 'Razgovaraj o ovoj stranici',
'talkpagelinktext' => 'Razgovor',
'talk'             => 'Razgovor',
'views'            => 'Pregledi',
'toolbox'          => 'Traka sa alatima',
'otherlanguages'   => 'Na drugim jezicima',
'redirectpagesub'  => 'Preusmjeri stranicu',
'lastmodifiedat'   => 'Ova stranica je posljednji put izmijenjena $1, $2.', # $1 date, $2 time
'jumpto'           => 'Skoči na:',
'jumptonavigation' => 'navigacija',
'jumptosearch'     => 'pretraga',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'O projektu {{SITENAME}}',
'copyright'            => 'Sadržaj je dostupan pod $1.',
'disclaimers'          => 'Odricanje odgovornosti',
'helppage'             => 'Help:Sadržaj',
'mainpage'             => 'Glavna strana',
'mainpage-description' => 'Glavna strana',
'privacy'              => 'Politika privatnosti',

'badaccess' => 'Greška pri odobrenju',

'retrievedfrom'       => 'Dobavljeno iz "$1"',
'youhavenewmessages'  => 'Imate $1 ($2).',
'newmessageslink'     => 'novih promjena',
'newmessagesdifflink' => 'posljednja promjena',
'editsection'         => 'uredi',
'editsectionhint'     => 'Uredi sekciju: $1',
'red-link-title'      => '$1 (stranica ne postoji)',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'     => 'Stranica',
'nstab-special'  => 'Posebna stranica',
'nstab-category' => 'Kategorija',

# General errors
'missingarticle-rev' => '(izmjena#: $1)',
'badtitletext'       => 'Zatražena stranica je bila nevaljana, prazna ili neispravno povezana s među-jezičkim ili inter-wiki naslovom.
Može sadržavati jedno ili više slova koja se ne mogu koristiti u naslovima.',
'viewsourcefor'      => 'za $1',

# Login and logout pages
'yourname'                => 'Korisničko ime:',
'yourpassword'            => 'Lozinka/zaporka:',
'remembermypassword'      => 'Upamti moju lozinku na ovom kompjuteru za buduće posjete',
'login'                   => 'Prijavi se',
'nav-login-createaccount' => 'Prijavi se / Registruj se',
'userlogin'               => 'Prijavi se / stvori korisnički račun',
'logout'                  => 'Odjavi me',
'nologinlink'             => 'Otvorite račun',
'mailmypassword'          => 'Pošalji mi novu lozinku putem E-maila',

# Edit pages
'preview'                          => 'Pretpregled',
'summary-preview'                  => 'Pretpregled sažetka:',
'newarticle'                       => '(Novi)',
'newarticletext'                   => "Preko linka ste došli na stranicu koja još uvijek ne postoji.
* Ako želite stvoriti stranicu, počnite tipkati u okviru dolje (v. [[{{MediaWiki:Helppage}}|stranicu za pomoć]] za više informacija).
* Ukoliko ste došli greškom, pritisnike dugme '''Nazad''' ('''back''') na vašem pregledniku.",
'previewnote'                      => "'''Upamtite da je ovo samo pretpregled.'''
Vaše izmjene još uvijek nisu snimljene!",
'editingsection'                   => 'Uređujete $1 (sekciju)',
'template-semiprotected'           => '(polu-zaštićeno)',
'hiddencategories'                 => 'Ova stranica pripada {{PLURAL:$1|1 skrivenoj kategoriji|$1 skrivenim kategorijama}}:',
'permissionserrorstext-withaction' => 'Nemate dozvolu za $2, zbog {{PLURAL:$1|sljedećeg|sljedećih}} razloga:',
'deleted-notice'                   => 'Ova stranica je obrisana.
Registar brisanja za stranicu je dolje naveden radi referenci.',

# History pages
'viewpagelogs'           => 'Pogledaj protokole ove stranice',
'nextrevision'           => 'Novija izmjena →',
'currentrevisionlink'    => 'Trenutna verzija',
'cur'                    => 'tren',
'last'                   => 'preth',
'histlegend'             => "Odabir razlika: označite radio dugme verzija za usporedbu i pritisnite enter ili dugme na dnu.<br />
Objašnjenje: '''({{int:cur}})''' = razlika sa trenutnom verzijom,
'''({{int:last}})''' = razlika sa prethodnom verzijom, '''{{int:minoreditletter}}''' = manja izmjena.",
'history-fieldset-title' => 'Pretraga historije',

# Revision deletion
'rev-delundel' => 'pokaži/sakrij',

# Diffs
'history-title' => 'Historija izmjena stranice "$1"',
'lineno'        => 'Linija $1:',
'editundo'      => 'ukloni ovu izmjenu',

# Search results
'searchresults'             => 'Rezultati pretrage',
'searchresults-title'       => 'Rezultati pretrage za "$1"',
'searchsubtitle'            => 'Tražili ste \'\'\'[[:$1]]\'\'\' ([[Special:Prefixindex/$1|sve stranice koje počinju sa "$1"]]{{int:pipe-separator}}[[Special:WhatLinksHere/$1|sve stranice koje vode do "$1"]])',
'searchsubtitleinvalid'     => "Tražili ste '''$1'''",
'search-mwsuggest-enabled'  => 'sa sugestijama',
'search-mwsuggest-disabled' => 'bez sugestija',
'powersearch'               => 'Napredna pretraga',

# Preferences page
'preferences' => 'Postavke',

# User rights log
'rightslog' => 'Registar korisničkih prava',

# Associated actions - in the sentence "You do not have permission to X"
'action-edit' => 'uređujete ovu stranicu',

# Recent changes
'nchanges' => '$1 {{PLURAL:$1|izmjena|izmjene|izmjena}}',
'rcnote'   => "Ispod {{PLURAL:$1|je '''$1''' promjena|su '''$1''' zadnje promjene|su '''$1''' zadnjih promjena}} u {{PLURAL:$2|posljednjem '''$2''' danu|posljednja '''$2''' dana|posljednjih '''$2''' dana}}, od $4, $5.",

# Recent changes linked
'recentchangeslinked' => 'Srodne izmjene',

# Upload
'uploadlogpage' => 'Registar postavljanja',
'uploadedimage' => 'postavljeno "[[$1]]"',

# File description page
'uploadnewversion-linktext' => 'Postavite novu verziju ove datoteke/fajla',

# File deletion
'filedelete-otherreason'      => 'Ostali/dodatni razlog/zi:',
'filedelete-reason-otherlist' => 'Ostali razlog/zi',

# Statistics
'statistics' => 'Statistike',

# Miscellaneous special pages
'nbytes'       => '$1 {{PLURAL:$1|bajt|bajtova}}',
'prefixindex'  => 'Sve stranice sa prefiksom',
'newpages'     => 'Nove stranice',
'movethispage' => 'Premjesti ovu stranicu',

# Book sources
'booksources'               => 'Književni izvori',
'booksources-search-legend' => 'Traži književne izvore',
'booksources-go'            => 'Idi',

# Special:AllPages
'alphaindexline' => '$1 do $2',
'prevpage'       => 'Prethodna stranica ($1)',
'allpagesfrom'   => 'Prikaži stranice koje počinju od:',
'allpagesto'     => 'Pokaži stranice koje završavaju na:',
'allarticles'    => 'Sve stranice',

# Special:LinkSearch
'linksearch' => 'Vanjski/spoljašni linkovi',

# Special:Log/newusers
'newuserlogpage'          => 'Registar novih korisnika',
'newuserlog-create-entry' => 'Novi korisnički račun',

# Special:ListGroupRights
'listgrouprights-members' => '(lista članova)',

# E-mail user
'emailuser' => 'Pošalji E-mail ovom korisniku',

# Watchlist
'watchlistfor'      => "(za '''$1''')",
'addedwatch'        => 'Dodano na listu praćenih stranica',
'addedwatchtext'    => "Stranica \"[[:\$1]]\" je dodana [[Special:Watchlist|vašoj listi praćenih stranica]].
Buduće promjene ove stranice i njoj pridružene stranice za razgovor će biti navedene ovdje, te će stranica izgledati '''podebljana''' u [[Special:RecentChanges|listi nedavnih]] izmjena kako bi se lakše uočila.",
'removedwatch'      => 'Uklonjeno s liste praćenja',
'removedwatchtext'  => 'Stranica "[[:$1]]" je uklonjena s [[Special:Watchlist|vaše liste praćenja]].',
'watchthispage'     => 'Prati ovu stranicu',
'watchlist-details' => '{{PLURAL:$1|$1 stranica praćena|$1 stranice praćene|$1 stranica praćeno}} ne računajući stranice za razgovor.',
'wlshowlast'        => 'Prikaži posljednjih $1 sati $2 dana $3',
'watchlist-options' => 'Opcije liste praćenja',

# Delete
'deletepage'            => 'Izbrišite stranicu',
'confirmdeletetext'     => 'Upravo ćete obrisati stranicu sa svom njenom historijom.
Molimo da potvrdite da ćete to učiniti, da razumijete posljedice te da to činite u skladu sa [[{{MediaWiki:Policy-url}}|pravilima]].',
'actioncomplete'        => 'Akcija završena',
'deletedtext'           => '"<nowiki>$1</nowiki>" je obrisan/a.
V. $2 za registar nedavnih brisanja.',
'deletecomment'         => 'Razlog za brisanje:',
'deleteotherreason'     => 'Ostali/dodatni razlog/zi:',
'deletereasonotherlist' => 'Ostali razlog/zi',

# Protect
'protectedarticle'            => '"[[$1]]" zaštićeno',
'modifiedarticleprotection'   => 'promijenjen nivo zaštite za "[[$1]]"',
'protectcomment'              => 'Komentar:',
'protectexpiry'               => 'Ističe:',
'protect_expiry_invalid'      => 'Upisani vremenski rok nije valjan.',
'protect_expiry_old'          => 'Upisani vremenski rok je u prošlosti.',
'protect-unchain'             => 'Deblokirajte dozvole premještanja',
'protect-text'                => "Ovdje možete gledati i izmijeniti nivo zaštite za stranicu '''<nowiki>$1</nowiki>'''.",
'protect-locked-access'       => "Nemate ovlasti za mijenjanje stepena zaštite.
Slijede trenutne postavke stranice '''$1''':",
'protect-cascadeon'           => 'Ova stranica je trenutno zaštićena jer je uključena u {{PLURAL:$1|stranicu, koja ima|stranice, koje imaju|stranice, koje imaju}} uključenu prenosnu (kaskadnu) zaštitu.  
Možete promijeniti stepen zaštite ove stranice, ali to neće uticati na prenosnu zaštitu.',
'protect-default'             => 'Dozvoli svim korisnicima',
'protect-fallback'            => 'Potrebno je imati "$1" ovlasti',
'protect-level-autoconfirmed' => 'Blokiraj nove i neregistrovane korisnike',
'protect-level-sysop'         => 'Samo administratori',
'protect-summary-cascade'     => 'prenosna (kaskadna) zaštita',
'protect-expiring'            => 'ističe $1 (UTC)',
'protect-cascade'             => 'Zaštiti sve stranice koje su uključene u ovu (kaskadna zaštita)',
'protect-cantedit'            => 'Ne možete mijenjati nivo zaštite ove stranice, jer nemate prava da je uređujete..',
'protect-otherreason'         => 'Ostali/dodatni razlog/zi:',
'protect-otherreason-op'      => 'Ostali/dodatni razlog/zi:',
'restriction-type'            => 'Dozvola:',
'restriction-level'           => 'Nivo ograničenja:',

# Undelete
'undeletedarticle' => 'vraćen "[[$1]]"',

# Namespace form on various pages
'blanknamespace' => '(Glavno)',

# Contributions
'contributions-title' => 'Korisnički doprinosi od $1',
'contribsub2'         => 'Za $1 ($2)',
'uctop'               => '(vrh)',

'sp-contributions-newbies'  => 'Pokaži doprinose samo novih korisnika',
'sp-contributions-blocklog' => 'registar blokiranja',
'sp-contributions-talk'     => 'Razgovor',
'sp-contributions-search'   => 'Pretraga doprinosa',
'sp-contributions-username' => 'IP adresa ili korisničko ime:',
'sp-contributions-submit'   => 'Traži',

# What links here
'whatlinkshere'           => 'Šta je povezano ovdje',
'whatlinkshere-title'     => 'Stranice koje vode na "$1"',
'whatlinkshere-page'      => 'Stranica:',
'linkshere'               => "Sljedeće stranice vode na '''[[:$1]]''':",
'isredirect'              => 'preusmjeri stranicu',
'whatlinkshere-prev'      => '{{PLURAL:$1|prethodni|prethodna|prethodnih}} $1',
'whatlinkshere-next'      => '{{PLURAL:$1|sljedeći|sljedeća|sljedećih}} $1',
'whatlinkshere-links'     => '← linkovi',
'whatlinkshere-hidelinks' => '$1 linkove',
'whatlinkshere-filters'   => 'Filteri',

# Block/unblock
'blockip'                  => 'Blokiraj korisnika',
'ipbreasonotherlist'       => 'Ostali razlog/zi',
'ipboptions'               => '2 sata:2 hours,1 dan:1 day,3 dana:3 days,1 sedmica:1 week,2 sedmice:2 weeks,1 mjesec:1 month,3 mjeseca:3 months,6 mjeseci:6 months,1 godine:1 year,zauvijek:infinite', # display1:time1,display2:time2,...
'ipbotherreason'           => 'Ostali/dodatni razlog/zi:',
'ipblocklist'              => 'Blokirane IP adrese i korisnička imena',
'contribslink'             => 'doprinosi',
'blocklogpage'             => 'Registar blokiranja',
'blocklogentry'            => 'blokiran [[$1]] s rokom: $2 $3',
'unblocklogentry'          => 'deblokiran $1',
'block-log-flags-nocreate' => 'pravljenje računa onemogućeno',

# Move page
'movepagetext'     => "Korištenjem donjeg formulara možete preimenovati stranicu, preusmjerivši njenu historiju na novi naziv.
Stari naslov će postati stranica za preusmjerenje na novi naslov.
Možete updateirati preusmjerenja koja idu na originalni naslov automatski.
Ako to ne učinite, budite sigurni da ste provjerili [[Special:DoubleRedirects|dupla]] ili [[Special:BrokenRedirects|mrtva prusmjerenja]].
Odgovorni ste za to da poveznice nastave povezivati stranice kojima su namijenjene.

Uzmite u obzir da stranica '''neće''' biti preusmjerena ako već postoji stranica s novim naslovom, osim ako je prazna ili ako je preusmjerenje bez prethodne historije uređivanja.
To znači da stranicu možete ponovno preimenovati u stari naslov ako je u pitanju bila pogreška, te da ne možete presnimiti već postojeću stranicu.

'''UPOZORENJE!'''
Ovo može biti drastična i neočekivana promjena za popularnu stranicu;
budite sigurni da ste shvatili sve posljedice prije nego što nastavite.",
'movepagetalktext' => "Odgovarajuća stranica za razgovor, ako postoji, će automatski biti premještena istovremeno '''osim:'''
*Neprazna stranica za razgovor već postoji pod novim imenom, ili
*Odznačite donju kutiju.

U tim slučajevima, moraćete ručno da premjestite stranicu ukoliko to želite.",
'movearticle'      => 'Premjestite stranicu:',
'newtitle'         => 'novi naslov:',
'move-watch'       => 'Prati ovu stranicu',
'movepagebtn'      => 'premjestite stranicu',
'pagemovedsub'     => 'Premještanje uspjelo',
'movepage-moved'   => '<big>\'\'\'"$1" je premještena na "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'articleexists'    => 'Stranica pod tim imenom već postoji, ili je ime koje ste izabrali neispravno.  
Molimo Vas da izaberete drugo ime.',
'talkexists'       => "'''Sama stranica je uspješno premještena, ali stranica za razgovor nije mogla biti premještena jer takva već postoji na novom naslovu.  Molimo Vas da ih spojite ručno.'''",
'movedto'          => 'premještena na',
'movetalk'         => 'Premjestite pridruženu stranicu za razgovor',
'1movedto2'        => '[[$1]] premješteno na [[$2]]',
'1movedto2_redir'  => 'premještena [[$1]] na [[$2]] putem preusmjerenja',
'movelogpage'      => 'Registar premještanja',
'movereason'       => 'Razlog:',

# Namespace 8 related
'allmessages' => 'Sistemske poruke',

# Tooltip help for the actions
'tooltip-pt-login'              => 'Predlažem da se prijavite; međutim, to nije obavezno',
'tooltip-ca-talk'               => 'Razgovor o sadržaju stranice',
'tooltip-ca-history'            => 'Prethodne verzije ove stranice',
'tooltip-ca-protect'            => 'Zaštiti ovu stranicu',
'tooltip-ca-delete'             => 'Izbriši ovu stranicu',
'tooltip-search'                => 'Pretraži ovaj wiki',
'tooltip-search-go'             => 'Idi na stranicu s upravo ovakvim imenom ako postoji',
'tooltip-search-fulltext'       => 'Pretraga stranica sa ovim tekstom',
'tooltip-n-mainpage'            => 'Posjetite glavnu stranu',
'tooltip-n-portal'              => 'O projektu, što možete učiniti, gdje možete naći stvari',
'tooltip-n-recentchanges'       => 'Spisak nedavnih izmjena na wikiju.',
'tooltip-n-randompage'          => 'Otvorite slučajnu stranicu',
'tooltip-n-help'                => 'Mjesto gdje možete nešto da naučite',
'tooltip-t-whatlinkshere'       => 'Spisak svih stranica povezanih sa ovim',
'tooltip-t-recentchangeslinked' => 'Nedavne izmjene ovdje povezanih stranica',
'tooltip-t-emailuser'           => 'Pošaljite e-mail ovom korisniku',
'tooltip-t-specialpages'        => 'Popis svih posebnih stranica',
'tooltip-t-permalink'           => 'Stalni link ove verzije stranice',
'tooltip-ca-nstab-main'         => 'Pogledajte sadržaj stranice',
'tooltip-ca-nstab-special'      => 'Ovo je posebna stranica, te je ne možete uređivati',
'tooltip-ca-nstab-category'     => 'Pogledajte stranicu kategorije',

# Browsing diffs
'nextdiff' => 'Novija izmjena →',

# Media information
'svg-long-desc' => '(SVG fajl, nominalno $1 × $2 piksela, veličina fajla: $3)',

# External editor support
'edit-externally'      => 'Izmijeni ovu datoteku/fajl koristeći eksternu aplikaciju',
'edit-externally-help' => '(Pogledajte [http://www.mediawiki.org/wiki/Manual:External_editors instrukcije za podešavanje] za više informacija)',

# 'all' in various places, this might be different for inflected languages
'watchlistall2' => 'sve',

# Watchlist editing tools
'watchlisttools-view' => 'Vidi relevantne promjene',
'watchlisttools-edit' => 'Vidi i uredi listu praćenja',
'watchlisttools-raw'  => 'Uredi grubu listu praćenja',

# Special:SpecialPages
'specialpages' => 'Posebne stranice',

);
