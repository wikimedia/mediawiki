<?php
/** Ancient Greek (Ἀρχαία ἑλληνικὴ)
 *
 * @ingroup Language
 * @file
 *
 * @author LeighvsOptimvsMaximvs
 * @author Omnipaedista
 * @author AndreasJS
 * @author Lefcant
 * @author Nychus
 * @author Neachili
 * @author SPQRobin
 * @author Siebrand
 * @author Yannos
 */

$dateFormats = array(
	'mdy time' => 'H:i',
	'mdy date' => 'xg j, Y',
	'mdy both' => 'H:i, xg j, Y',

	'dmy time' => 'H:i',
	'dmy date' => 'j xg Y',
	'dmy both' => 'H:i, j xg Y',

	'ymd time' => 'H:i',
	'ymd date' => 'Y xg j',
	'ymd both' => 'H:i, Y xg j',
);

$messages = array(
# User preference toggles
'tog-underline'               => 'Ὑπογραμμίζειν συνδέσμους:',
'tog-highlightbroken'         => 'Μορφοῦν ἀπωλομένους συνδέσμους <a href="" class="new">οὐτωσί</a> (ἄλλως: οὐτωσί <a href="" class="internal">;</a>).',
'tog-justify'                 => 'Στοιχίζειν παραγράφους',
'tog-hideminor'               => 'Κρύπτειν μικρὰς ἐγγραφὰς ἐν προσφάταις ἀλλαγαῖς',
'tog-extendwatchlist'         => 'Ἐφορώμενα ἐκτείνειν ἵνα ἅπασαι φανῶσιν αἱ ἁρμόδιοι ἀλλαγαὶ',
'tog-usenewrc'                => 'Προσκεκοσμημέναι πρόσφαται ἀλλαγαί (JavaScript)',
'tog-numberheadings'          => 'Ἐξαριθμεῖν τίτλους αὐτομάτως',
'tog-showtoolbar'             => 'Δεικνύναι τὴν τῶν ἐργαλείων ἐπιμελείας μετώπην (JavaScript)',
'tog-editondblclick'          => 'Δέλτους δὶς θλιβείσας ἐπιμελεῖσθαι (JavaScript)',
'tog-editsection'             => 'Τμῆμα διὰ συνδέσμου [μεταγράφειν] μεταγράφειν παρέχειν',
'tog-editsectiononrightclick' => 'Τμῆμα μεταγράφειν παρέχειν <br /> διὰ τίτλον δεξιῷ ὀμφαλῷ θλίβειν (JavaScript)',
'tog-showtoc'                 => 'Δεικνύναι πίνακα περιεχομένων (ἐν δέλτοις περιεχούσαις πλείους τῶν 3 ἐπικεφαλίδων)',
'tog-rememberpassword'        => 'Ἐνθυμεῖσθαι σύνδεσίν μου ἐν τῷδε ὑπολογιστῇ',
'tog-editwidth'               => 'Πλαίσιον ἐπιμελείας εἰς πλήρες πλάτος',
'tog-watchcreations'          => 'Προστιθέναι τὰς δέλτους ἃς ποιῶ τοῖς ἐφορώμενοῖς ἔμου',
'tog-watchdefault'            => 'Προστιθέναι τὰς δέλτους ἃς μεταγράφω τοῖς ἐφορώμενοῖς ἔμου',
'tog-watchmoves'              => 'Προστιθέναι τὰς δέλτους ἃς κινῶ τοῖς ἐφορώμενοῖς ἔμου',
'tog-watchdeletion'           => 'Προστιθέναι τὰς δέλτους ἃς διαγράφω τοῖς ἐφορώμενοῖς ἔμου',
'tog-minordefault'            => 'Σημαίνειν ὅλας τὰς μεταγραφὰς ὡς ἥττονες προκαθωρισμένως',
'tog-previewontop'            => 'Δεικνύναι τὸ προεπισκοπεῖν πρὸ τοῦ κυτίου μεταγραφῆς',
'tog-previewonfirst'          => 'Τῆς πρῶτης μεταγράφης, δεικνύναι τὸ προεπισκοπεῖν',
'tog-nocache'                 => 'Ἀπενεργοποιεῖν τὸ κρύπτειν τὰς δέλτους',
'tog-enotifwatchlistpages'    => 'Ἄγγειλον μοι ὅταν μία δέλτος ἐν τῇ ἀγρυπνοδιαλογή μου μεταβάλληται',
'tog-enotifusertalkpages'     => 'Ἄγγειλον μοι ὅταν ἡ δέλτος ὁμιλίας χρήστου μου μεταβάλληται',
'tog-enotifminoredits'        => 'Ἄγγειλον μοι ἐπἴσης τὰς ἥττονες ἀλλαγὰς δέλτων',
'tog-enotifrevealaddr'        => 'Ἀποκαλύπτειν τὴν ταχυδρομικὴν μου διεύθυνσιν ἐν τῇ εἰδοποιητηρίᾳ ἀλληλογραφίᾳ',
'tog-shownumberswatching'     => 'Δεικνύναι ἀριθμὸν παρακολουθούντων χρηστῶν',
'tog-fancysig'                => 'Ἀκατέργασται ὑπογραφαὶ (ἄνευ αὐτομάτου συνδέσμου)',
'tog-showjumplinks'           => 'Ἐνεργοποιεῖν τοὺς "ἅλμα πρὸς" συνδέσμους προσβασιμότητος',
'tog-watchlisthideown'        => 'Οὐ δηλοῦν τὰς ἐμὰς μεταβολὴς ἐν τὰ ἐφορώμενά μου',
'tog-watchlisthidebots'       => 'Ἀποκρύπτειν τὰς αὐτοματας μεταγραφ ὰς ἐκ τῆς αγρυπνοδιαλογῆς',
'tog-watchlisthideminor'      => 'Οὐ δηλοῦν τὰς μικρὰς μεταβολὴς ἐν τὰ ἐφορώμενά μου',
'tog-showhiddencats'          => 'Κεκρυμμένας κατηγορίας δηλοῦν',

'underline-always'  => 'Ἀεὶ',
'underline-never'   => 'Οὔποτε',
'underline-default' => 'Πλοηγὸς ὡς προκαθωρισμένως',

'skinpreview' => '(Προεπισκοπεῖν)',

# Dates
'sunday'        => 'Κυριακή',
'monday'        => 'Δευτέρα',
'tuesday'       => 'Τρίτη',
'wednesday'     => 'Τετάρτη',
'thursday'      => 'Πέμπτη',
'friday'        => 'Παρασκευή',
'saturday'      => 'Σάββατοv',
'sun'           => 'Κυ',
'mon'           => 'Δε',
'tue'           => 'Τρ',
'wed'           => 'Τε',
'thu'           => 'Πε',
'fri'           => 'Πα',
'sat'           => 'Σα',
'january'       => 'Ἰανουάριος',
'february'      => 'Φεβρουάριος',
'march'         => 'Μάρτιος',
'april'         => 'Ἀπρίλιος',
'may_long'      => 'Μάιος',
'june'          => 'Ἰούνιος',
'july'          => 'Ἰούλιος',
'august'        => 'Αὔγουστος',
'september'     => 'Σεπτέμβριος',
'october'       => 'Ὀκτώβριος',
'november'      => 'Νοέμβριος',
'december'      => 'Δεκέμβριος',
'january-gen'   => 'Ἰανουαρίου',
'february-gen'  => 'Φεβρουαρίου',
'march-gen'     => 'Μαρτίου',
'april-gen'     => 'Ἀπριλίου',
'may-gen'       => 'Μαΐου',
'june-gen'      => 'Ἰουνίου',
'july-gen'      => 'Ἰουλίου',
'august-gen'    => 'Αὐγούστου',
'september-gen' => 'Σεπτεμβρίου',
'october-gen'   => 'Ὀκτωβρίου',
'november-gen'  => 'Νοεμβρίου',
'december-gen'  => 'Δεκεμβρίου',
'jan'           => 'Ἰαν',
'feb'           => 'Φεβ',
'mar'           => 'Μάρ',
'apr'           => 'Ἀπρ',
'may'           => 'Μάι',
'jun'           => 'Ἰούν',
'jul'           => 'Ἰούλ',
'aug'           => 'Αὔγ',
'sep'           => 'Σεπτ',
'oct'           => 'Ὀκτ',
'nov'           => 'Νοέμ',
'dec'           => 'Δεκ',

# Categories related messages
'pagecategories'           => '{{PLURAL:$1|Γένος|Γένη}}',
'category_header'          => 'Χρήματα ἐν γένει "$1"',
'subcategories'            => 'Ὑπογένη',
'category-media-header'    => 'Μέσα ἐν κατηγορίᾳ "$1"',
'hidden-categories'        => '{{ΠΛΗΘΥΝΤΙΚΟΣ:$1|Κρυμμένη Κατηγορία|Κρυμμέναι Κατηγορίαι}',
'hidden-category-category' => 'Κρυμμέναι κατηγορίαι', # Name of the category where hidden categories will be listed
'listingcontinuesabbrev'   => 'συνεισφ.',

'mainpagedocfooter' => "Συμβουλευθήσεσθε τὸ [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] ἵνα πληροφορηθῇτε ἐπὶ τοῦ οὐίκι λογισμικοῦ.

== Ἄρξασθε ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'          => 'Περὶ',
'article'        => 'ἡ χρῆμα',
'newwindow'      => '(ἀνοίξεται ἐν νέᾳ θυρίδι)',
'cancel'         => 'Ἀκυροῦν',
'qbfind'         => 'Εὑρίσκειν',
'qbbrowse'       => 'Νέμου',
'qbedit'         => 'Μεταγράφειν',
'qbpageoptions'  => 'Ἥδε ἡ δέλτος',
'qbpageinfo'     => 'Συγκείμενον',
'qbmyoptions'    => 'Δέλτοι μου',
'qbspecialpages' => 'Εἰδικαὶ δέλτοι',
'moredotdotdot'  => 'πλέον...',
'mypage'         => 'Δέλτος μου',
'mytalk'         => 'Διάλεκτός μου',
'anontalk'       => 'Διάλεκτος πρὸ τοῦδε τοῦ IP',
'navigation'     => 'Πλοήγησις',
'and'            => 'καί',

# Metadata in edit box
'metadata_help' => 'Μεταδεδομένα:',

'errorpagetitle'    => 'Σφάλμα',
'returnto'          => 'Ἐπανέρχεσθαι εἰς $1.',
'tagline'           => 'Ἀπὸ {{SITENAME}}',
'help'              => 'Βοήθεια',
'search'            => 'Ζητεῖν',
'searchbutton'      => 'Ζητεῖν',
'go'                => 'Ἰέναι',
'searcharticle'     => 'Ἰέναι',
'history'           => 'Τὰ τῆς δέλτου πρότερα',
'history_short'     => 'Τὰ πρότερα',
'info_short'        => 'Μάθησις',
'printableversion'  => 'Ἐκτυπωτέα μορφὴ',
'permalink'         => 'Σύνδεσμος βέβαιος',
'print'             => 'Τυποῦν',
'edit'              => 'Μεταγράφειν',
'create'            => 'Δημιουργεῖν',
'editthispage'      => 'Μεταγράφειν τήνδε τὴν δέλτον',
'create-this-page'  => 'Δημιουργεῖν ταύτην δέλτον',
'delete'            => 'Σβεννύναι',
'deletethispage'    => 'Διαγράφειν τήνδε τὴν δέλτον',
'undelete_short'    => 'Ἐπανορθοῦν {{PLURAL:$1|ἕνα μεταγραφέν|$1 μεταγραφέντα}}',
'protect'           => 'Φυλλάττειν',
'protectthispage'   => 'Tήνδε τὴν δέλτον φυλάττειν',
'unprotect'         => 'μὴ φυλάττειν',
'unprotectthispage' => 'Tήνδε τὴν δέλτον  μὴ φυλάττειν',
'newpage'           => 'Δέλτος νέα',
'talkpage'          => 'Διαλέγε τήνδε τὴν δέλτον',
'talkpagelinktext'  => 'Διαλέγεσθαι',
'specialpage'       => 'Εἰδικὴ δέλτος',
'personaltools'     => 'Ἴδια ἐργαλεῖα',
'postcomment'       => 'Ἀποστέλειν ἓν σχόλιον',
'articlepage'       => 'Χρήματος δέλτον ὁρᾶν',
'talk'              => 'Διάλεξις',
'views'             => 'Ποσάκις ἔσκεπται',
'toolbox'           => 'Σιδηριοκάδος',
'userpage'          => 'Ὁρᾶν δέλτον χρωμένου',
'projectpage'       => 'Ἰδὲ δέλτον σχεδίου',
'imagepage'         => 'Ὁρᾶν μέσων δέλτον',
'mediawikipage'     => 'Ὁρᾶν δέλτον μηνυμάτων',
'templatepage'      => 'Ὁρᾶν δέλτον ἐπιγραμμάτων',
'viewhelppage'      => 'Ὁρᾶν βοηθείας δέλτον',
'categorypage'      => 'Ὁρᾶν γένους δέλτον',
'viewtalkpage'      => 'Ὁρᾶν διάλεκτον',
'otherlanguages'    => 'Βαρβαριστὶ',
'redirectedfrom'    => '(Ἀποστελτὸν παρὰ $1)',
'redirectpagesub'   => 'Ἐπανάγειν δέλτον',
'lastmodifiedat'    => 'Ἥδε ἡ δέλτος ὕστατον μετεβλήθη $2, $1.', # $1 date, $2 time
'protectedpage'     => 'Πεφυλαγμένη δέλτος',
'jumpto'            => 'Ἅλμα πρὸς:',
'jumptonavigation'  => 'περιήγησις',
'jumptosearch'      => 'ἐρευνᾶν',

# All link text and link target definitions of links into project namespace that get used by other message strings, with the exception of user group pages (see grouppage) and the disambiguation template definition (see disambiguations).
'aboutsite'            => 'Περὶ {{SITENAME}}',
'aboutpage'            => 'Project:Περί',
'bugreports'           => 'Ἀναφορὰ σφαλμάτων',
'bugreportspage'       => 'Project:Ἀναφορὰ σφαλμάτων',
'copyrightpage'        => '{{ns:project}}:Πνευματικὰ Δικαιώματα',
'currentevents'        => 'Τὰ γιγνόμενα',
'currentevents-url'    => 'Project:Τὰ γιγνόμενα',
'disclaimers'          => 'Ἀποποιήσεις',
'disclaimerpage'       => 'Project:Γενικὴ ἀποποίησις',
'edithelp'             => 'Βοήθεια περὶ τοῦ μεταγράφειν',
'edithelppage'         => 'Help:Βοήθεια περὶ τοῦ μεταγράφειν',
'faq'                  => 'Τὰ πολλάκις αἰτηθέντα',
'faqpage'              => 'Project:Πολλάκις αἰτηθέντα',
'helppage'             => 'Help:Περιεχόμενα',
'mainpage'             => 'Κυρία Δέλτος',
'mainpage-description' => 'Κυρία Δέλτος',
'policy-url'           => 'Project:Πολιτική',
'portal'               => 'Πύλη πολιτείας',
'portal-url'           => 'Project:Πύλη Κοινότητος',
'privacy'              => 'Ἡ περὶ τῶν ἰδίων προαίρεσις',
'privacypage'          => 'Βούλευμα:Περὶ τῶν ἰδιωτικῶν',
'sitesupport'          => 'Δῶρα',
'sitesupport-url'      => 'Project:Ὑποστήριξις ἱστοχώρου',

'badaccess'        => 'Σφάλμα ἀδείας',
'badaccess-group0' => 'Οὐκ ἔξεστί σοι ταῦτα διαπράττειν.',

'ok'                  => 'εἶεν',
'retrievedfrom'       => 'ᾑρεθὲν ἀπὸ "$1"',
'youhavenewmessages'  => 'Ἔχεις $1 ($2).',
'newmessageslink'     => 'νέαι ἀγγελίαι',
'newmessagesdifflink' => 'ἐσχάτη μεταβολή',
'editsection'         => 'μεταγράφειν',
'editold'             => 'μεταγράφειν',
'editsectionhint'     => 'Μεταγράφειν τὸ μέρος: $1',
'toc'                 => 'Περιεχόμενα',
'showtoc'             => 'δεικνύναι',
'hidetoc'             => 'κρύπτειν',
'viewdeleted'         => 'Ὁρᾶν $1;',
'feedlinks'           => 'Τρέφειν:',
'site-rss-feed'       => 'Ἡ τοῦ $1 Ρ.Σ.Σ.-παρασκευή',
'site-atom-feed'      => 'Ἡ τοῦ $1 Ἀτομο-παρασκευή',
'page-rss-feed'       => 'Βοτὴρ RSS "$1"',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Χρῆμα',
'nstab-user'      => 'Δέλτος χρωμένου',
'nstab-special'   => 'Εἰδικόν',
'nstab-project'   => 'Δέλτος σχεδίου',
'nstab-image'     => 'Εἰκών',
'nstab-mediawiki' => 'Ἀγγελία',
'nstab-template'  => 'Πρότυπον',
'nstab-help'      => 'Βοήθεια',
'nstab-category'  => 'Γένος',

# General errors
'error'           => 'Σφάλμα',
'dberrortext'     => 'Ἓν σφάλμα συντάξεως πεύσεως βάσεως δεδομένων ἀπάντησε,
ὅπερ ὑποδηλοῖ τὴν ὕπαρξιν ἑνὸς σφάλματος ἐν τῷ λογισμικῷ.
Ἡ ἔσχατη ἀποπειραθεῖσα πεῦσις βάσεως δεδομένων ἦν:
<blockquote><tt>$1</tt></blockquote>
ἐξ ἐντὸς τῆς τελέσεως "<tt>$2</tt>".
Ἡ MySQL ἐπέστρεψε πταῖσμα "<tt>$3: $4</tt>".',
'filenotfound'    => 'Γραφὴ "$1" οὐχ ηὑρέθη',
'badtitle'        => 'Κακὸν τὸ ἐπώνυμον',
'viewsource'      => 'Πηγήν διασκοπεῖν',
'viewsourcefor'   => 'Ὑπὲρ τοῦ $1',
'actionthrottled' => 'Δρᾶσις ἠγχθεῖσα',
'sqlhidden'       => '(πεῦσις SQL κρυμμένη)',

# Virus scanner
'virus-scanfailed' => 'Σάρωσις πταιστή (κῶδιξ $1)',

# Login and logout pages
'logouttitle'             => 'Ἀποσυνδεῖσθαι χρήστου',
'welcomecreation'         => '== Χαῖρε, $1! ==

Λογισμὸν σὸν πεποίηται. Ἔχε μνήμην μεταβάλλειν τὰς τοῦ {{SITENAME}} αἱρέσεις σὰς.',
'loginpagetitle'          => 'Συνδεῖσθαι χρήστου',
'yourname'                => 'Ὄνομα χρωμένου:',
'yourpassword'            => 'Σῆμα:',
'remembermypassword'      => 'Μιμνῄσκεσυαι ἐνθάδε τὸ συνδεῖσθαι',
'yourdomainname'          => 'Ὁ τομεύς σου:',
'login'                   => 'Συνδεῖσθαι',
'nav-login-createaccount' => 'Συνδεῖσθαι/λογισμὸν ποιεῖν',
'userlogin'               => 'Συνδεῖσθαι/λογισμὸν ποιεῖν',
'logout'                  => 'Ἐξέρχεσθαι',
'userlogout'              => 'Ἐξέρχεσθαι',
'notloggedin'             => 'Οὐ συνδεδεμένος',
'nologinlink'             => 'Λογισμὸν ποιεῖν',
'createaccount'           => 'Λογισμὸν ποιεῖν',
'gotaccount'              => 'Ἆρα λογισμὸν ἤδη τινὰ ἔχεις; $1.',
'gotaccountlink'          => 'Συνδεῖσθαι',
'createaccountmail'       => 'ἠλεκτρονικῇ ἐπιστολῇ',
'youremail'               => 'Ἠλεκτρονικαὶ ἐπιστολαὶ:',
'username'                => 'Ὄνομα χρωμένου:',
'uid'                     => 'Ταυτότης χρήστου:',
'prefs-memberingroups'    => 'Μέλος {{PLURAL:$1|ομάδoς|ομάδων}}:',
'yourrealname'            => 'Τὸ ἀληθὲς ὄνομα:',
'yourlanguage'            => 'Γλῶττά σου:',
'yournick'                => 'Προσωνυμία:',
'email'                   => 'ἠλεκτρονική ἐπιστολή',
'loginerror'              => 'Ἡμάρτηκας περὶ τοῦ συνδεδεκαῖναι',
'loginsuccesstitle'       => 'Καλῶς συνδέδεσαι',
'loginsuccess'            => "'''συνδέδεσαι ἤδη τῷ {{SITENAME}} ὡς \"\$1\".'''",
'mailmypassword'          => 'Τὸ σύνθημα ἠλεκτρονικῇ ἐπιστολῇ πέμπειν',

# Edit page toolbar
'bold_sample'    => 'Γράμματα παχέα',
'bold_tip'       => 'Γράμματα παχέα',
'italic_sample'  => 'Γράμματα πλάγια',
'italic_tip'     => 'Γράμματα πλάγια',
'link_sample'    => 'Συνδέσμου ὄνομα',
'link_tip'       => 'Σύνδεσμος οἰκεῖος',
'extlink_sample' => 'http://www.example.com ὄνομα συνδέσμου',
'media_tip'      => 'Τὸ προσάγον πρὸς τὸ φορτίον',

# Edit pages
'summary'               => 'Τὸ κεφάλαιον',
'minoredit'             => 'Μικρὰ ἥδε ἡ μεταβολή',
'watchthis'             => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'savearticle'           => 'Γράφειν τὴν δέλτον',
'preview'               => 'Τὸ προεπισκοπεῖν',
'showpreview'           => 'Προεπισκοπεῖν',
'showdiff'              => 'Δεικνύναι τὰς μεταβολάς',
'blockedtitle'          => 'Ἀποκεκλεισμένος ὁ χρώμενος',
'loginreqtitle'         => 'Δεῖ συνδεῖσθαι',
'loginreqlink'          => 'συνδεῖσθαι',
'newarticle'            => '(νέα)',
'userinvalidcssjstitle' => "'''Προσοχή:''' Οὐκ ὑφίσταται skin \"\$1\". Μέμνησο: οἱ προσηρμοσμέναι δέλτοι .css και .js χρησιμοποιούν ένα ἐπώνυμον ἔχον μικρά γράμματα, π.χ. {{ns:user}}:Foo/monobook.css ἐν ἀντίθεσει πρὸς τὸ {{ns:user}}:Foo/Monobook.css.",
'editing'               => 'Μεταγράφων $1',
'editingsection'        => 'Μεταγράφων $1 (μέρος)',
'yourtext'              => 'Τὰ ὑπό σου γραφόμενα',
'yourdiff'              => 'Τὰ διαφέροντα',
'template-protected'    => '(φυλλάττεται)',

# History pages
'currentrev'          => 'Τὸ νῦν',
'revisionasof'        => 'Τὰ ἐπὶ $1',
'revision-info'       => 'Τὸ ἐπὶ $1 ὑπὸ $2',
'previousrevision'    => '←Τὸ πρότερον',
'nextrevision'        => 'Τὸ νεώτερον→',
'currentrevisionlink' => 'Τὰ νῦν',
'cur'                 => 'ἡ νῦν',
'next'                => 'ἡ ἐχομένη',
'last'                => 'ἡ ὑστάτη',
'page_first'          => 'πρώτη',
'page_last'           => 'ἐσχάτη',
'histfirst'           => 'πρώτη',
'histlast'            => 'ἐσχάτη',

# Revision feed
'history-feed-item-nocomment' => '$1 ἐπὶ $2', # user at time

# Revision deletion
'rev-delundel' => 'δεικνύναι/κρύπτειν',
'pagehist'     => 'Ἱστορία δέλτου',

# Diffs
'history-title' => 'Τὰ πρότερα περὶ "$1" κατὰ τὸ μεταγράφειν',
'difference'    => '(Τὰ μεταβεβλήμενα)',
'lineno'        => 'Γραμμή $1·',
'editundo'      => 'ἀγέννητον τιθέναι',

# Search results
'searchresults'            => 'Ἀποτελέσματα ἀναζητήσεως',
'noexactmatch'             => "'''Οὐκ ἐστὶ δέλτος \"\$1\" ὀνομαστί.'''
Ἔξεστι σοὶ [[:\$1|ταύτην ποιεῖν]].",
'prevn'                    => 'πρότερον $1',
'nextn'                    => 'τὸ $1 τὸ ἐχόμενον',
'viewprevnext'             => 'Ἐπισκοπεῖν ($1) ($2) ($3)',
'search-interwiki-caption' => 'Sister projects
Ἀδελφὰ σχέδια',
'powersearch'              => 'Ζητεῖν ἀναλυτικῶς',

# Preferences page
'preferences'        => 'Αἱρέσεις',
'mypreferences'      => 'Αἱρέσεις μου',
'prefs-edits'        => 'Τοσοῦται αἱ μεταβολαί:',
'prefsnologin'       => 'Οὐ συνδεδεμένος',
'qbsettings-none'    => 'Οὐδέν',
'math'               => 'Τὰ μαθηματικά',
'math_unknown_error' => 'ἄγνωστον σφάλμα',
'prefs-rc'           => 'Αἱ νέαι μεταβολαί',
'prefs-watchlist'    => 'Τὰ ἐφορώμενα',
'saveprefs'          => 'Γράφειν',
'textboxsize'        => 'Τὸ μεταγράφειν',
'searchresultshead'  => 'Ζητεῖν',
'timezonelegend'     => 'Χρονικὴ ζώνη',
'localtime'          => 'Τοπικὴ ὥρα',
'timezoneoffset'     => 'Ἐκτόπισμα¹',
'default'            => 'προκαθωρισμένον',

# Groups
'group-sysop'      => 'Γέρoντες',
'group-bureaucrat' => 'Ἔφοροι',
'group-suppress'   => 'Παροράματα',

'group-sysop-member'      => 'Γέρων',
'group-bureaucrat-member' => 'Ἔφορος',

'grouppage-sysop'      => '{{ns:project}}:Γέροντες',
'grouppage-bureaucrat' => '{{ns:project}}:Ἔφοροι',

# User rights log
'rightsnone' => '(Οὐδέν)',

# Recent changes
'nchanges'        => '$1 {{PLURAL:$1|μεταβολή|μεταβολαί}}',
'recentchanges'   => 'Αἱ νέαι μεταβολαί',
'rcshowhideminor' => '$1 μικραὶ μεταβολαὶ',
'rcshowhidebots'  => '$1 αὐτόματα',
'rcshowhideliu'   => '$1 χρωμένους συνδεδεμένους',
'rcshowhideanons' => '$1 χρώμενοι ἀνώνυμοι',
'rcshowhidemine'  => '$1 μεταβολὰς μου',
'diff'            => 'διαφ.',
'hist'            => 'Προτ.',
'hide'            => 'Κρύπτειν',
'show'            => 'Δεικνύναι',
'minoreditletter' => 'μ',
'newpageletter'   => 'Ν',
'boteditletter'   => 'α',

# Recent changes linked
'recentchangeslinked'       => 'Οἰκεῖαι μεταβολαί',
'recentchangeslinked-title' => 'Μεταβολαὶ οἰκεῖαι "$1"',

# Upload
'upload'            => 'Ἀνάγειν γραφήν',
'uploadbtn'         => 'Φορτίον ἐντιθέναι',
'uploadnologin'     => 'Οὐ συνδεδεμένος',
'filedesc'          => 'Τὸ κεφάλαιον',
'fileuploadsummary' => 'Τὸ κεφάλαιον:',
'uploadedimage'     => 'Ἐγκεῖται "[[$1]]"',
'watchthisupload'   => 'Ἐφορᾶν τήνδε τὴν δέλτον',

# Special:Imagelist
'imagelist'             => 'Κατάλογος πάντων τῶν φορτίων',
'imagelist_name'        => 'Ὄνομα',
'imagelist_user'        => 'Χρώμενος',
'imagelist_size'        => 'Ὁπόσος',
'imagelist_description' => 'Διέξοδος',

# Image description page
'filehist'            => 'Τοῦ ἀρχείου συγγραφή',
'filehist-current'    => 'Τὸ νῦν',
'filehist-datetime'   => 'Ἡμέρα/Ὥρα',
'filehist-user'       => 'Χρώμενος',
'filehist-dimensions' => 'Τὸ μέγαθος',
'filehist-filesize'   => 'Μέγεθος',
'filehist-comment'    => 'Σχόλιον',
'imagelinks'          => 'Σύνδεσμοι',
'nolinkstoimage'      => 'Οὐδένα ἐστὶ προσάγον τόδε τὸ φορτίον.',
'noimage'             => 'Οὐδένα ἐστὶ οὕτως ὀνομαστί, ἔξεστι σοὶ $1.',
'noimage-linktext'    => 'Ἐντιθέναι',

# MIME search
'mimesearch' => 'MIME Ζητεῖν',

# Unused templates
'unusedtemplateswlh' => 'οἱ σύνδεσμοι οἱ ἄλλοι',

# Random page
'randompage' => 'Δέλτος τυχοῦσα',

'brokenredirects-edit'   => '(μεταγράφειν)',
'brokenredirects-delete' => '(διαγράφειν)',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|βαίς|βαίτα}}',
'ncategories'             => '$1 {{PLURAL:$1|Γένος|Γένη}}',
'nlinks'                  => '$1 {{PLURAL:$1|σύνδεσμος|σύνδεσμοι}}',
'nmembers'                => '$1 {{PLURAL:$1|μέλος|μέλη}}',
'lonelypages'             => 'Δέλτοι ὀρφαναί',
'uncategorizedpages'      => 'Αἱ δέλτοι αἱ οὐκ ἐνοῦσαι γένεσι',
'uncategorizedcategories' => 'Τὰ γένη τὰ οὐκ ἐνοῦντες γένεσι',
'wantedpages'             => 'Αἱ δέλτοι οἷας ἱμείρομεν',
'mostrevisions'           => 'Αἱ δέλτοι αἱ πλειστάκις μεταβεβλήμεναι',
'shortpages'              => 'Δέλτοι μικραί',
'longpages'               => 'Δέλτοι μακραί',
'protectedpages'          => 'Αἱ δέλτοι αἱ φυλαττόμενοι',
'listusers'               => 'Κατάλογος πάντων τῶν χρωμένων',
'newpages'                => 'Δέλτοι νέαι',
'ancientpages'            => 'Αἱ παλαιόταται δέλτοι',
'move'                    => 'κινεῖν',
'movethispage'            => 'Κινεῖν τήνδε τὴν δέλτον',

# Book sources
'booksources-go' => 'Ἰέναι',

# Special:Log
'specialloguserlabel'  => 'Χρήστης·',
'speciallogtitlelabel' => 'Ὄνομα:',
'log-search-submit'    => 'Ἰέναι',

# Special:Allpages
'allpages'       => 'Πᾶσαι αἱ δέλτοι',
'alphaindexline' => '$1 ἕως $2',
'nextpage'       => 'Ἡ δέλτος ἡ ἐχομένη ($1)',
'prevpage'       => 'Ἡ δέλτος ἡ προτέρη ($1)',
'allarticles'    => 'Πάντες γραφαί',
'allpagessubmit' => 'Ἰέναι',

# Special:Categories
'categories' => 'Γένη',

# E-mail user
'emailuser'    => 'Ἠλεκτρονικὴν ἐπιστολὴν τῷδε τῷ χρήστῳ πέμπειν',
'emailmessage' => 'Ἀγγελία',
'emailsend'    => 'Πέμπειν',

# Watchlist
'watchlist'            => 'Τὰ ἐφορώμενά μου',
'mywatchlist'          => 'Τὰ ἐφορώμενά μου',
'watchlistfor'         => "(πρό '''$1''')",
'watchnologin'         => 'Οὐ συνδεδεμένος',
'addedwatch'           => 'Δέλτος ἐπῶπται',
'removedwatch'         => 'Ἀνεώραται ἥδε ἡ δέλτος',
'removedwatchtext'     => 'Ἀνεώραται ἡ δέλτος "[[:$1]]"',
'watch'                => 'Ἐφορᾶν',
'watchthispage'        => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'unwatch'              => 'Ἀνεφορᾶν',
'notanarticle'         => 'οὐ χρῆμα δέλτος',
'watchlist-hide-bots'  => 'Κρύπτειν τὰς ὑπ᾿ αὐτομάτων μεταβολάς',
'watchlist-show-own'   => 'Δεικνύναι τοὺς ἐράνους μου',
'watchlist-hide-own'   => 'Κρύπτειν τὰς ὑπ᾿ ἐμοῦ μεταβολάς',
'watchlist-show-minor' => 'Δεικνύναι τὰς μικρὰς μεταβολάς',
'watchlist-hide-minor' => 'Κρύπτειν τὰς μικρὰς μεταβολάς',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ἐφορών...',
'unwatching' => 'Ἀνεφορῶν...',

# Delete/protect/revert
'deletepage'                  => 'Διαγράφειν τὴν δέλτον',
'confirm'                     => 'Κυροῦν',
'exblank'                     => 'δέλτος κενὴ ἦν',
'actioncomplete'              => 'Τέλειον τὸ ποιώμενον',
'deletedarticle'              => 'Ἐσβέσθη τὴν δέλτον "[[$1]]"',
'dellogpage'                  => 'Τὰ ἐσβέσμενα',
'deletecomment'               => 'Αἰτία τοῦ σβεννύναι:',
'deleteotherreason'           => 'Αἰτία ἄλλα:',
'deletereasonotherlist'       => 'Αἰτία ἄλλα',
'protectcomment'              => 'Σχόλιον:',
'protectexpiry'               => 'Ἐξήξει:',
'protect-default'             => '(κριτήριον)',
'protect-fallback'            => 'Δεῖ ἐχεῖν τὴν "$1" οὐσίαν',
'protect-level-autoconfirmed' => 'Ἀποκλῄειν τοὺς ἀγράφους',
'protect-level-sysop'         => 'Μόνοι οἱ γέροντες',
'protect-summary-cascade'     => 'Διαδεχόμενον',
'protect-expiring'            => 'Ἐξήξει $1 (UTC)',
'restriction-type'            => 'Ἐξουσία:',

# Restrictions (nouns)
'restriction-edit' => 'Μεταγράφειν',
'restriction-move' => 'Kινεῖν',

# Restriction levels
'restriction-level-sysop'         => 'πλήρως διαφυλαττομένη',
'restriction-level-autoconfirmed' => 'ἡμιδιαφυλαττομένη',

# Undelete
'viewdeletedpage'        => 'Δεικνύναι διαγραφείσας δέλτους',
'undeletebtn'            => 'Ἀνορθοῦν',
'undelete-search-submit' => 'Ζητεῖν',

# Namespace form on various pages
'namespace'      => 'Ὀνομεῖον:',
'blanknamespace' => '(Κυρία γραφή)',

# Contributions
'contributions' => 'Ἔρανοι χρωμένου',
'mycontris'     => 'Ἔρανοί μου',
'contribsub2'   => 'Πρὸς $1 ($2)',
'uctop'         => '(ἄκρον)',
'month'         => 'Μήν:',
'year'          => 'Ἔτος:',

'sp-contributions-blocklog' => 'Τὰ ἀποκλῄειν',
'sp-contributions-submit'   => 'Ζητεῖν',

# What links here
'whatlinkshere'       => 'Τὰ ἐνθάδε ἄγοντα',
'whatlinkshere-title' => 'Δέλτοι ἐζεύγμεναι ὑπο $1',
'linklistsub'         => '(Κατάλογος τῶν συνδέσμων)',
'linkshere'           => "Τάδε ἄγουσι πρὸς '''[[:$1]]''':",
'nolinkshere'         => "Οὐδένα ἄγουσι πρὸς '''[[:$1]]'''.",
'whatlinkshere-prev'  => '{{PLURAL:$1|πρότερον|Τὰ $1 πρότερα}}',
'whatlinkshere-next'  => '{{PLURAL:$1|ἑξῆς|οἱ $1 ἑξαῖ}}',
'whatlinkshere-links' => '← σύνδεσμοι',

# Block/unblock
'blockip'            => 'Ἀποκλῄειν τόνδε τὸν χρώμενον',
'ipboptions'         => 'βʹ ὥραι:2 hours,αʹ ἡμέρα day:1 day,γʹ ἡμέραι:3 days,ζʹ ἡμέραι:1 week,ιδʹ ἡμέραι:2 weeks,αʹ μήν:1 month,γʹ μήνες:3 months,ϝʹ μήνες:6 months,αʹ ἔτος:1 year,ἄπειρον:infinite', # display1:time1,display2:time2,...
'ipblocklist-submit' => 'Ζητεῖν',
'infiniteblock'      => 'ἄπειρον',
'blocklink'          => 'ἀποκλῃειν',
'unblocklink'        => 'χαλᾶν',
'contribslink'       => 'Ἔρανοι',
'blocklogpage'       => 'Τὰ ἀποκλῄειν',

# Move page
'move-page-legend' => 'Κινεῖν τὴν δέλτον',
'movearticle'      => 'Κινεῖν τὴν δέλτον:',
'movenologin'      => 'Οὐ συνδεδεμένος',
'newtitle'         => 'Πρὸς τὸ νέον ὄνομα:',
'move-watch'       => 'Ἑφορᾶν τήνδε τὴν δέλτον',
'movepagebtn'      => 'Κινεῖν τὴν δέλτον',
'pagemovedsub'     => 'Κεκίνηται ἡ δέλτος',
'movepage-moved'   => '<big>\'\'\'"$1" κεκίνηται πρὸς "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'movedto'          => 'Κεκίνηται πρὸς',
'movetalk'         => 'Κινεῖν τὸν διάλεκτον',
'1movedto2'        => '[[$1]] ἐκινήθη πρὸς [[$2]]',
'movelogpage'      => 'Τὰ κινεῖν',
'movereason'       => 'Ἀπολογία:',
'revertmove'       => 'Ἐπανέρχεσθαι',

# Export
'export' => 'Δέλτους ἐξάγειν',

# Namespace 8 related
'allmessagesname' => 'Ὄνομα',

# Thumbnails
'thumbnail-more' => 'Αὐξάνειν',

# Tooltip help for the actions
'tooltip-pt-userpage'       => 'Τὴν δέλτον χρωμένου ἐμήν',
'tooltip-pt-mytalk'         => 'Διάλεκτός μου',
'tooltip-pt-preferences'    => 'Αἱρέσεις μου',
'tooltip-pt-watchlist'      => 'Κατάλογος τῶν ἐφορωμένων μου',
'tooltip-pt-mycontris'      => 'Κατάλογος τῶν ἔρανων μου',
'tooltip-pt-logout'         => 'Ἐξέρχεσθαι',
'tooltip-ca-talk'           => 'Διάλεκτος περὶ τῆς δέλτου',
'tooltip-ca-edit'           => 'Ἔξεστι σοι μεταγράφειν τήνδε τὴν δέλτον. Προεπισκοπεῖν πρὶν ἂν γράφῃς τὴν δέλτον.',
'tooltip-ca-protect'        => 'Ἀμύνειν τῇδε τῇ δέλτῳ',
'tooltip-ca-delete'         => 'Διαγράφειν τήνδε τὴν δέλτον',
'tooltip-ca-move'           => 'Κινεῖν τήνδε τὴν δέλτον',
'tooltip-ca-watch'          => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'tooltip-ca-unwatch'        => 'Ἀνεφορᾶν τήνδε τὴν δέλτον',
'tooltip-search'            => 'Ζητεῖν {{SITENAME}}',
'tooltip-p-logo'            => 'Δέλτος Μεγίστη',
'tooltip-n-mainpage'        => 'Πορεύεσθαι τὴν κυρίαν Δέλτον',
'tooltip-n-portal'          => 'Τὰ περὶ ταῦτης τε τὴς ἐνκυκλοπαιδείας, τῶν τε οἷων ἔξεστι σοὶ πράττεις, οὗ παρεστὶ τινά',
'tooltip-n-recentchanges'   => 'Κατάλογος κατὰ πᾶσας τὰς νέας μεταβολάς.',
'tooltip-n-randompage'      => 'Τινὰ γραφὴν χύδην δηλοῦν.',
'tooltip-n-help'            => 'Μάθησις περὶ τῆσδε Οὐίκεως',
'tooltip-n-sitesupport'     => 'Τρέφειν ἡμᾶς',
'tooltip-t-whatlinkshere'   => 'Κατάλογος τῶν ἐνθάδε ἀγόντων',
'tooltip-t-emailuser'       => 'Ἠλεκτρονικὴν ἐπιστολὴν τῷδε τῷ χρήστῳ πέμπειν',
'tooltip-t-upload'          => 'Φορτία ἐντιθέναι',
'tooltip-t-specialpages'    => 'Κατάλογος κατὰ πᾶσας τὰς εἰδικὰς δέλτους',
'tooltip-ca-nstab-main'     => 'χρῆμα δέλτον ὁρᾶν',
'tooltip-ca-nstab-user'     => 'Δέλτος χρωμένου ὁρᾶν',
'tooltip-ca-nstab-image'    => 'Ὁρᾶν τὴν τοῦ φορτίου δέλτον',
'tooltip-ca-nstab-category' => 'Ἐπισκοπεῖν τὴν τῆς κατηγορίας δέλτον',
'tooltip-minoredit'         => 'Δεικνύναι ἥδε ἡ μεταβολή μικρά εἴναι',
'tooltip-save'              => 'Γράφειν τὰς μεταβολάς σου',
'tooltip-diff'              => 'Δείξαι σὰ κατὰ τὰ γεγράμμενα μεταβολά.',
'tooltip-watch'             => 'Ἐφορᾶν τήνδε τὴν δέλτον',

# Attribution
'others' => 'ἄλλοι',

# Browsing diffs
'previousdiff' => '← ἡ μεταβολὴ ἡ προτέρη',
'nextdiff'     => 'ἡ μεταβολὴ ἡ ἐχομένη →',

# Media information
'show-big-image-thumb' => '<small>Τοῦδε προεπισκοπεῖν μέγεθος: $1 × $2 εἰκονοστοιχεία</small>',

# Special:Newimages
'newimages' => 'Τὰ νέα φορτία δεῦρο ἀθροίζει',
'ilsubmit'  => 'Ζητεῖν',

# Metadata
'metadata-expand'   => 'Δηλοῦν τὰς ἀκριβείας',
'metadata-collapse' => 'Κρύπτειν τὰς ἀκριβείας',

'exif-componentsconfiguration-0' => 'Οὐκ ἔστι',

# 'all' in various places, this might be different for inflected languages
'recentchangesall' => 'Πᾶσαι',
'imagelistall'     => 'Πᾶσαι',
'watchlistall2'    => 'Πάντα',
'namespacesall'    => 'πάντα',
'monthsall'        => 'ἅπαντες',

# Trackbacks
'trackbackremove' => ' ([$1 Διαγράφειν])',

# action=purge
'confirm_purge_button' => 'εἶεν',

# Multipage image navigation
'imgmultipageprev' => '← Δέλτος προτέρα',
'imgmultipagenext' => 'Δέλτος ἡ ἐχομένη →',
'imgmultigo'       => 'Ἰέναι!',

# Table pager
'table_pager_next'         => 'Δέλτος ἡ ἐχομένη',
'table_pager_prev'         => 'Δέλτος προτέρα',
'table_pager_first'        => 'Δέλτος πρώτη',
'table_pager_last'         => 'Δέλτος ἐσχάτη',
'table_pager_limit_submit' => 'Ἰέναι',

# Auto-summaries
'autosumm-new' => 'Δέλτος νέα: $1',

# Size units
'size-bytes'     => '$1 Δ',
'size-kilobytes' => '$1 ΧΔ',
'size-megabytes' => '$1 ΜΔ',
'size-gigabytes' => '$1 ΓΔ',

# Watchlist editor
'watchlistedit-raw-titles' => 'Τίτλοι:',

# Watchlist editing tools
'watchlisttools-edit' => 'Ὁρᾶν τε μεταγράφειν τὰ ἐφορώμενα',

# Special:Version
'version' => 'Ἐπανόρθωμα', # Not used as normal message but as header for the special page itself

# Special:SpecialPages
'specialpages' => 'Εἰδικαὶ δέλτοι',

# Special:Blankpage
'blankpage'              => 'Κενὴ δέλτος',
'intentionallyblankpage' => 'Ταῦτη ἡ δέλτος ἀφίεται ἐσκεμμένως κενὴ καὶ ἐστὶ χρήσιμη ὡς σημεῖον ἀναφορᾶς, κτλ.',

);
