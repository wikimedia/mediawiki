<?php
/** Ancient Greek (Ἀρχαία ἑλληνικὴ)
 *
 * @ingroup Language
 * @file
 *
 * @author Omnipaedista
 * @author LeighvsOptimvsMaximvs
 * @author Lefcant
 * @author AndreasJS
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
'tog-rememberpassword'        => 'Ἐνθυμεῖσθαι ἐμὴν σύνδεσιν ἐν τῷδε ὑπολογιστῇ',
'tog-editwidth'               => 'Πλαίσιον ἐπιμελείας εἰς πλῆρες πλάτος',
'tog-watchcreations'          => 'Προστιθέναι τὰς δέλτους ἃς ποιῶ τοῖς ἐφορωμένοις μου',
'tog-watchdefault'            => 'Προστιθέναι τὰς δέλτους ἃς μεταγράφω τοῖς ἐφορωμένοις μου',
'tog-watchmoves'              => 'Προστιθέναι τὰς δέλτους ἃς κινῶ τοῖς ἐφορωμένοις μου',
'tog-watchdeletion'           => 'Προστιθέναι τὰς δέλτους ἃς διαγράφω τοῖς ἐφορωμένοις μου',
'tog-minordefault'            => 'Σημαίνειν ὅλας τὰς μεταγραφὰς ὡς ἥττονες προκαθωρισμένως',
'tog-previewontop'            => 'Δεικνύναι τὸ προεπισκοπεῖν πρὸ τοῦ κυτίου μεταγραφῆς',
'tog-previewonfirst'          => 'Τῆς πρώτης μεταγραφῆς, δεικνύναι τὸ προεπισκοπεῖν',
'tog-nocache'                 => 'Ἀπενεργοποιεῖν τὸ κρύπτειν τὰς δέλτους',
'tog-enotifwatchlistpages'    => 'Ἄγγειλον μοι ὅταν μία δέλτος ἐν τῇ ἐφορειοδιαλογή μου μεταβάλληται',
'tog-enotifusertalkpages'     => 'Ἄγγειλον μοι ὅταν ἡ δέλτος ὁμιλίας χρήστου μου μεταβάλληται',
'tog-enotifminoredits'        => 'Ἄγγειλον μοι ἐπἴσης τὰς ἥττονες ἀλλαγὰς δέλτων',
'tog-enotifrevealaddr'        => 'Ἀποκαλύπτειν τὴν ταχυδρομικὴν μου διεύθυνσιν ἐν τῇ εἰδοποιητηρίᾳ ἀλληλογραφίᾳ',
'tog-shownumberswatching'     => 'Δεικνύναι ἀριθμὸν παρακολουθούντων χρηστῶν',
'tog-fancysig'                => 'Ἀκατέργασται ὑπογραφαὶ (ἄνευ αὐτομάτου συνδέσμου)',
'tog-showjumplinks'           => 'Ἐνεργοποιεῖν τοὺς "ἅλμα πρὸς" συνδέσμους προσβασιμότητος',
'tog-forceeditsummary'        => 'Προειδοποίησόν με εἰ εἰσάγω κενὴ περίληψιν μεταγραφῆς',
'tog-watchlisthideown'        => 'Οὐ δηλοῦν τὰς ἐμὰς μεταβολὴς ἐν τὰ ἐφορώμενά μου',
'tog-watchlisthidebots'       => 'Ἀποκρύπτειν τὰς αὐτοματας μεταγραφ ὰς ἐκ τῆς ἐφορειοδιαλογῆς',
'tog-watchlisthideminor'      => 'Οὐ δηλοῦν τὰς μικρὰς μεταβολὰς ἐν τὰ ἐφορώμενά μου',
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
'category-empty'           => "''Ταύτη ἡ κατηγορία οὐ περιλαμβάνει δέλτους τῷ παρόντι.''",
'hidden-categories'        => '{{PLURAL:$1|Κεκρυμμένη Κατηγορία|Κεκρυμμέναι Κατηγορίαι}}',
'hidden-category-category' => 'Κεκρυμμέναι κατηγορίαι', # Name of the category where hidden categories will be listed
'listingcontinuesabbrev'   => 'συμβ.',

'mainpagedocfooter' => "Συμβουλευθήσεσθε τὸ [http://meta.wikimedia.org/wiki/Help:Contents User's Guide] ἵνα πληροφορηθῇτε ἐπὶ τοῦ οὐίκι λογισμικοῦ.

== Ἄρξασθε ==
* [http://www.mediawiki.org/wiki/Manual:Configuration_settings Configuration settings list]
* [http://www.mediawiki.org/wiki/Manual:FAQ MediaWiki FAQ]
* [http://lists.wikimedia.org/mailman/listinfo/mediawiki-announce MediaWiki release mailing list]",

'about'          => 'Περὶ',
'article'        => 'τὸ χρῆμα',
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
'bugreports'           => 'Ἀναφορὰ πλανῶν',
'bugreportspage'       => 'Project:Ἀναφορὰ πλανῶν',
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
'privacypage'          => 'Project:Περὶ τῶν ἰδιωτικῶν',

'badaccess'        => 'Σφάλμα ἀδείας',
'badaccess-group0' => 'Οὐκ ἔξεστί σοι ταῦτα διαπράττειν.',

'ok'                  => 'εἶεν',
'retrievedfrom'       => 'ᾑρεθὲν ἀπὸ "$1"',
'youhavenewmessages'  => 'Ἔχεις $1 ($2).',
'newmessageslink'     => 'νέαι ἀγγελίαι',
'newmessagesdifflink' => 'ἐσχάτη μεταβολή',
'editsection'         => 'μεταγράφειν',
'editold'             => 'μεταγράφειν',
'viewsourceold'       => 'ὁρᾶν πηγήν',
'editsectionhint'     => 'Μεταγράφειν τὸ μέρος: $1',
'toc'                 => 'Περιεχόμενα',
'showtoc'             => 'δεικνύναι',
'hidetoc'             => 'κρύπτειν',
'viewdeleted'         => 'Ὁρᾶν $1;',
'feedlinks'           => 'Βοτήρ:',
'site-rss-feed'       => 'Ἡ τοῦ $1 Ρ.Σ.Σ.-παρασκευή',
'site-atom-feed'      => 'Ἡ τοῦ $1 Ἀτομο-παρασκευή',
'page-rss-feed'       => 'Βοτὴρ RSS "$1"',

# Short words for each namespace, by default used in the namespace tab in monobook
'nstab-main'      => 'Χρῆμα',
'nstab-user'      => 'Δέλτος χρωμένου',
'nstab-media'     => 'Δέλτος μέσων',
'nstab-special'   => 'Εἰδικόν',
'nstab-project'   => 'Δέλτος σχεδίου',
'nstab-image'     => 'Εἰκών',
'nstab-mediawiki' => 'Ἀγγελία',
'nstab-template'  => 'Πρότυπον',
'nstab-help'      => 'Βοήθεια',
'nstab-category'  => 'Γένος',

# General errors
'error'           => 'Σφάλμα',
'databaseerror'   => 'Σφάλμα βάσεως δεδομένων',
'dberrortext'     => 'Ἓν σφάλμα συντάξεως τῆς πεύσεως βάσεως δεδομένων ἀπήντησεν,
ὅπερ ὑποδηλοῖ τὴν ὕπαρξιν μίας πλάνης ἐν τῷ λογισμικῷ.
Ἡ ἔσχατη ἀποπειραθεῖσα πεῦσις βάσεως δεδομένων ἦν:
<blockquote><tt>$1</tt></blockquote>
ἐξ ἐντὸς τῆς τελέσεως "<tt>$2</tt>".
Ἡ MySQL ἐπέστρεψεν σφάλμα "<tt>$3: $4</tt>".',
'readonly'        => 'Βάσις δεδομένων ἀποκεκλεισμένη',
'filenotfound'    => 'Γραφὴ "$1" οὐχ ηὑρέθη',
'formerror'       => 'Σφάλμα: οὐ δυναμένη ἡ ὑποβολὴ τοῦ τύπου ἐστίν',
'badtitle'        => 'Κακὸν τὸ ἐπώνυμον',
'viewsource'      => 'Πηγήν διασκοπεῖν',
'viewsourcefor'   => 'Ὑπὲρ τοῦ $1',
'actionthrottled' => 'Δρᾶσις ἠγχθεῖσα',
'viewsourcetext'  => 'Ἔξεστί σοὶ ὁρᾶν τε ἀντιγράφειν τὴν τῆς δέλτου πηγὴν:',
'sqlhidden'       => '(πεῦσις SQL keκρυμμένη)',

# Virus scanner
'virus-scanfailed' => 'Σάρωσις πταιστή (κῶδιξ $1)',

# Login and logout pages
'logouttitle'             => 'Ἀποσυνδεῖσθαι χρήστου',
'welcomecreation'         => '== Χαῖρε, $1! ==

Λογισμὸν σὸν πεποίηται. Ἔχε μνήμην μεταβάλλειν τὰς τοῦ {{SITENAME}} αἱρέσεις σου.',
'loginpagetitle'          => 'Συνδεῖσθαι χρήστου',
'yourname'                => 'Ὄνομα χρωμένου:',
'yourpassword'            => 'Σῆμα:',
'remembermypassword'      => 'Μίμνῃσκε ἐνθάδε τὸ συνδεῖσθαι',
'yourdomainname'          => 'Ὁ τομεύς σου:',
'login'                   => 'Συνδεῖσθαι',
'nav-login-createaccount' => 'Συνδεῖσθαι/λογισμὸν ποιεῖν',
'loginprompt'             => 'Δεῖ ἐνεργὰ τὰ HTTP-πύσματα εἶναι πρὸ τοῦ συνδεῖσθαι ἐν τῷ {{SITENAME}}.',
'userlogin'               => 'Συνδεῖσθαι/λογισμὸν ποιεῖν',
'logout'                  => 'Ἐξέρχεσθαι',
'userlogout'              => 'Ἐξέρχεσθαι',
'notloggedin'             => 'Οὐ συνδεδεμένος',
'nologin'                 => 'Ἆρα λογισμὸν οὐκ ἔχεις? $1.',
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
'prefs-help-realname'     => 'Ἀληθὲς ὄνομα προαιρετικὸν ἐστίν.
Εἰ εἰσάγεις τὸ ὄνομά σου, ἀναγνωριστέον ἔσται τὸ ἔργον σου.',
'loginerror'              => 'Ἡμάρτηκας περὶ τοῦ συνδεδεκαῖναι',
'loginsuccesstitle'       => 'Καλῶς συνδέδεσαι',
'loginsuccess'            => "'''συνδέδεσαι ἤδη τῷ {{SITENAME}} ὡς \"\$1\".'''",
'nosuchusershort'         => 'Οὔκ ἐστι χρήστης ἔχων τὸ ὄνομα "<nowiki>$1</nowiki>".
Ἔλεγξον τὴν ὀρθογραφίαν σου.',
'nouserspecified'         => 'Ὄνομα χρωμένου καθοριστέον ὑποχρεωτικώς.',
'wrongpassword'           => 'Εἰσηγμένον σύνθημα ἐσφαλμένον. Πείρασον πάλιν.',
'wrongpasswordempty'      => 'Σύνθημα οὐκ ἔγραψας.
Αὖθις πείρασον.',
'mailmypassword'          => 'Τὸ σύνθημα ἠλεκτρονικῇ ἐπιστολῇ πέμπειν',
'passwordremindertitle'   => 'Νέον ἐφήμερον σύνθημα διὰ {{SITENAME}}',
'passwordremindertext'    => "Τίς (πιθανὼς σύ, μετὰ τῆς IP-διευθύνσεως \$1) ἐζήτησεν τὴν πρὸς σέ ἀποστολὴν νέου συνθήματος διὰ τὸν ἱστότοπον {{SITENAME}} (\$4). Τὸ σύνθημα διὰ τὸν χρήστην \"\$2\" ἐν τῷ παρόντι \"\$3\" ἐστίν. Δεῖ ''νῦν'' συνδεῖσθαι τε καὶ ἀλλάττειν τὸ σύνθημά σου.

Εἰ τὶς ''ἕτερος'' τὴν αἴτησιν ταύτην ὑπέβαλεν ἢ εἰ οὐκ ἀμνηστεῖς τοῦ συνθήματός σου καὶ ''οὐκ'' επιθυμεῖς τὴν ἀλλαγὴν οὗ, δύνασαι ἀγνοῆσαι ταῦτο τὸ μήνυμα καὶ διατηρῆσαι τὸ παλαιὸν σύνθημά σου.",
'noemail'                 => 'Οὐδεμία ἠλεκτρονικὴ διεύθυνσις ἐγγεγραμένη διὰ τὸν χρώμενον "$1".',
'loginlanguagelabel'      => 'Γλῶττα: $1',

# Password reset dialog
'resetpass'        => 'Ἀναδιορισμὸς συνθήματος λογισμοῦ',
'resetpass_header' => 'Ἀναδιορισμὸς συνθήματος',
'resetpass_submit' => 'Ἀναδιορισμὸς συνθήματος καὶ σύνδεσις',

# Edit page toolbar
'bold_sample'     => 'Γράμματα παχέα',
'bold_tip'        => 'Γράμματα παχέα',
'italic_sample'   => 'Γράμματα πλάγια',
'italic_tip'      => 'Γράμματα πλάγια',
'link_sample'     => 'Συνδέσμου ὄνομα',
'link_tip'        => 'Σύνδεσμος οἰκεῖος',
'extlink_sample'  => 'http://www.example.com ὄνομα συνδέσμου',
'extlink_tip'     => 'Ἐξώτερος σύνδεσμος (μέμνησο τὸ πρόθεμα http:// )',
'headline_sample' => 'Κείμενον ἐπικεφαλίδος',
'headline_tip'    => 'Κλίμακος 2 ἐπικεφαλίς',
'math_sample'     => 'Εἰσάγειν τύπον ὧδε',
'math_tip'        => 'Μαθηματικὸς τύπος (LaTeX)',
'nowiki_sample'   => 'Εἰσάγειν ἀμόρφωτον κείμενον ὧδε',
'nowiki_tip'      => 'Ἀγνοεῖν οὐικιμορφοποιίαν',
'image_tip'       => 'Ἐμβεβαπτισμένον ἀρχεῖον',
'media_tip'       => 'Τὸ προσάγον πρὸς τὸ φορτίον',
'sig_tip'         => 'Ὑπογραφή σου μετὰ χρονοσφραγίδος',
'hr_tip'          => 'Ὁριζόντιος γραμμή (χρηστέα φειδωλώς)',

# Edit pages
'summary'                => 'Τὸ κεφάλαιον',
'subject'                => 'Χρῆμα/ἐπικεφαλίς',
'minoredit'              => 'Μικρὰ ἥδε ἡ μεταβολή',
'watchthis'              => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'savearticle'            => 'Γράφειν τὴν δέλτον',
'preview'                => 'Τὸ προεπισκοπεῖν',
'showpreview'            => 'Προεπισκοπεῖν',
'showdiff'               => 'Δεικνύναι τὰς μεταβολάς',
'anoneditwarning'        => "'''Προσοχή:''' Οὐ συνδεδεμένος εἶ.
Ἡ IP-διεύθυνσίς σου καταγεγραμμένη ἔσται ἐν τῇδε δέλτου ἱστορίᾳ.",
'summary-preview'        => 'Πρόβλεψις περιλήψεως',
'blockedtitle'           => 'Ἀποκεκλεισμένος ὁ χρώμενος',
'blockedtext'            => "<big>'''Τὸ ονομα χρήστου σου η η IP-διεύθυνσις σου πεφραγμένη εστίν.'''</big>

Ἡ φραγη γέγονε υπὸ τὸν/την $1. Η δεδομένη αιτιολογία ἐστίν: ''$2''.

* Εναρξις φραγης: $8
* Ληξις φραγης: $6
* Ἡ φραγη προορίζεται δια τον χρήστην: $7

Δύνασαι αποτάθητι εις τὸν/την $1 ἢ ὅντινα ἕτερον [[{{MediaWiki:Grouppage-sysop}}|ἔφορον]] δια τὸ διαλέγεσθαι περι της φραγης.
Οὐ δύνασαι χρησθαι την δυνατότηταν «αποστολη ηλεκτρονικης επιστολης τῷδε χρήστη» ει ουκ ὁρίσης μίαν εγκυρην ηλεκτρονικην διεύθυνσιν εν ταις [[Special:Preferences|προκρίσεσί]] σου.
Ἡ τρέχουσα IP-διεύθυνσις σου $3 εστίν, και η αναγνώρισις της φραγης #$5 εστίν. Παρακαλοῦμεν σέ περιλαμβάνειν οἱανδήποτε εξ αυτων ἢ και αμφοτέρας εν ταις ερωτήσεσί σου.",
'loginreqtitle'          => 'Δεῖ συνδεῖσθαι',
'loginreqlink'           => 'συνδεῖσθαι',
'newarticle'             => '(νέα)',
'noarticletext'          => 'Οὐδὲν ἐν τῇδε τῇ δέλτῳ γεγραμμένον, ἔξεστι σοὶ [[Special:Search/{{PAGENAME}}|δέλτον τινὰ ὧδε ὀνομαστὶ ζῆτειν]] ἢ [{{fullurl:{{FULLPAGENAME}}|action=edit}} τήνδε τὴν δέλτον μεταγράφειν].',
'userinvalidcssjstitle'  => "'''Προσοχή:''' Οὐκ ὑφίσταται skin \"\$1\". Μέμνησο: οἱ προσηρμοσμέναι δέλτοι .css και .js χρησιμοποιούν ένα ἐπώνυμον ἔχον μικρά γράμματα, π.χ. {{ns:user}}:Foo/monobook.css ἐν ἀντίθεσει πρὸς τὸ {{ns:user}}:Foo/Monobook.css.",
'note'                   => '<strong>Ἐπισήμανσις:</strong>',
'previewnote'            => '<strong>Ἥδε ἐστὶ πρόβλεψις, οὐχὶ γράφειν τὰς μεταβολάς!</strong>',
'editing'                => 'Μεταγράφων $1',
'editingsection'         => 'Μεταγράφων $1 (μέρος)',
'yourtext'               => 'Τὰ ὑπό σου γραφόμενα',
'yourdiff'               => 'Τὰ διαφέροντα',
'copyrightwarning'       => 'Ὅλαι αἱ συμβολαὶ εἰς τὸ {{SITENAME}} θεωροῦνται ὡς σύμφωναι πρὸς τὴν $2 (βλ. $1 διὰ τὰς ἀκριβεῖας).
Εἰ οὐκ ἐπιθυμεῖτε τὰ ὑμέτερα κείμενα μεταγράψιμα καὶ διαδόσιμα εἰσὶν ὑπὸ ἄλλων χρωμένων, κατὰ τὴν βούλησίν των, παρακαλοῦμεν ὑμᾶς ἵνα μὴ αὐτὰ ἀναρτητε ἐν τούτῳ χώρῳ. Ὅ,τι συνεισφέρετε ἐνθάδε (κείμενα, διαγράμματα, στοιχεῖα ἢ εἰκόνες) δεῖ εἶναι ὑμέτερον ἔργον, ἢ ἀνῆκον τῷ δημοσίῳ τομεῖ, ἢ προερχόμενον ἐξ ἐλευθέρων ἢ ανοικτῶν πηγῶν μετὰ ῥητῆς ἀδείας ἀναδημοσιεύσεως. <br />

Βεβαιοῦτε ἡμᾶς περὶ τῆς πρωτοτυπίας ὅτου ἔργου γραφομένου ὑφ’ ὑμᾶς ἐνθάδε. Βεβαιοῦτε ἡμᾶς ἐπἴσης περὶ τῆς μὴ ἐκχωρήσεως αὐτου εἰς αλλοτρίους πρὸς ὑμᾶς τοῦ δικαιώματος δημοσιεύσεως καὶ ὀνήσεως του, ἥντινα ἔκτασιν αὐτὸν ἔχει.
<br />
<strong>ΠΑΡΑΚΑΛΟΥΜΕΝ ΙΝΑ ΜΗ ΑΝΑΡΤΗΤΕ ΚΕΙΜΕΝΑ ΑΛΛΟΤΡΙΩΝ ΕΙ ΜΗ ΕΧΕΤΕ ΤΗΝ ΑΔΕΙΑ ΤΟΥ ΙΔΙΟΚΤΗΤΟΥ ΤΩΝ ΠΝΕΥΜΑΤΙΚΩΝ ΔΙΚΑΙΩΜΑΤΩΝ!</strong>',
'templatesused'          => 'Πρότυπα κεχρησμένα ἐν τοιαύτῃ δελτῳ:',
'templatesusedpreview'   => 'Πρότυπα κεχρησμένα ἐν ταύτῃ πρόβλεψει:',
'template-protected'     => '(φυλλάττεται)',
'template-semiprotected' => '(ἡμιπεφυλαγμένη)',
'recreate-deleted-warn'  => "'''Προσοχή: Ἀναδημιουργεῖς δέλτον πάλαι ποτὲ διαγραφεῖσα.'''

You should consider whether it is appropriate to continue editing this page.
The deletion log for this page is provided here for convenience:",

# History pages
'viewpagelogs'        => 'Ὁρᾶν καταλόγους διὰ ταύτην τὴν δέλτον',
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
'historyempty'        => '(κενόν)',

# Revision feed
'history-feed-item-nocomment' => '$1 ἐπὶ $2', # user at time

# Revision deletion
'rev-delundel'      => 'δεικνύναι/κρύπτειν',
'pagehist'          => 'Ἱστορία δέλτου',
'revdelete-content' => 'περιεχόμενον',
'revdelete-uname'   => 'ὄνομα χρήστου',
'revdelete-hid'     => 'κρύπ $1',
'revdelete-unhid'   => 'oὐ κρύπ $1',

# History merging
'mergehistory' => 'Συγχωνεύειν ἱστορίας δέλτων',

# Merge log
'mergelog'    => 'Τῶν συγχωνεύσεων καταλόγος',
'revertmerge' => 'Ἀποσυγχωνεύειν',

# Diffs
'history-title'           => 'Τὰ πρότερα περὶ "$1" κατὰ τὸ μεταγράφειν',
'difference'              => '(Τὰ μεταβεβλημένα)',
'lineno'                  => 'Γραμμή $1·',
'compareselectedversions' => 'Συγκρίνειν τὰς ἐπιλεγμένας δέλτους',
'editundo'                => 'ἀγέννητον τιθέναι',
'diff-multi'              => '({{PLURAL:$1|Μία ἐνδιάμεσος ἀναθεώρησις|$1 ἐνδιάμεσοι ἀναθεωρήσεις}} οὐ φαίνονται.)',

# Search results
'searchresults'            => 'Ἀποτελέσματα ἀναζητήσεως',
'noexactmatch'             => "'''Οὐκ ἐστὶ δέλτος \"\$1\" ὀνομαστί.'''
Ἔξεστι σοὶ [[:\$1|ταύτην ποιεῖν]].",
'prevn'                    => 'πρότερον $1',
'nextn'                    => 'τὸ $1 τὸ ἐχόμενον',
'viewprevnext'             => 'Ἐπισκοπεῖν ($1) ($2) ($3)',
'search-interwiki-caption' => 'Sister projects
Ἀδελφὰ σχέδια',
'search-interwiki-more'    => '(πλείω)',
'search-relatedarticle'    => 'Σχετικά',
'searchrelated'            => 'σχετικά',
'searchall'                => 'ἅπαντα',
'powersearch'              => 'Ζητεῖν ἀναλυτικῶς',
'search-external'          => 'Ἐξωτέρα ἀναζήτησις',

# Preferences page
'preferences'           => 'Αἱρέσεις',
'mypreferences'         => 'Αἱρέσεις μου',
'prefs-edits'           => 'Τοσοῦται αἱ μεταβολαί:',
'prefsnologin'          => 'Οὐ συνδεδεμένος',
'qbsettings-none'       => 'Οὐδέν',
'skin'                  => 'Ἐμφάνισις',
'math'                  => 'Τὰ μαθηματικά',
'dateformat'            => 'Μορφοποιία χρονολογίας',
'datedefault'           => 'Οὐδεμία προτίμησις',
'datetime'              => 'Χρονολογία καὶ ὥρα',
'math_unknown_error'    => 'ἄγνωστον σφάλμα',
'math_unknown_function' => 'ἄγνωστος λειτουργία',
'math_lexing_error'     => 'σφάλμα λεξικῆς ἀναλύσεως',
'math_syntax_error'     => 'σφάλμα συντάξεως',
'prefs-rc'              => 'Αἱ νέαι μεταβολαί',
'prefs-watchlist'       => 'Τὰ ἐφορώμενα',
'prefs-misc'            => 'Διάφορα',
'saveprefs'             => 'Γράφειν',
'retypenew'             => 'Ἀνατύπωσις νέου συνθήματος:',
'textboxsize'           => 'Τὸ μεταγράφειν',
'rows'                  => 'Σειραί:',
'columns'               => 'Στῆλαι:',
'searchresultshead'     => 'Ζητεῖν',
'timezonelegend'        => 'Χρονικὴ ζώνη',
'localtime'             => 'Τοπικὴ ὥρα',
'timezoneoffset'        => 'Ἐκτόπισμα¹',
'prefs-namespaces'      => 'Ὄνοματικὸς χῶρος',
'default'               => 'προκαθωρισμένον',
'files'                 => 'Ἀρχεῖα',

# User rights
'userrights-user-editname' => 'Εἰσάγειν ἓν ὄνομα χρήστου:',
'userrights-groupsmember'  => 'Μέλος τοῦ:',

# Groups
'group'            => 'Ὁμάς:',
'group-user'       => 'Χρώμενοι',
'group-bot'        => 'Αὐτόματα',
'group-sysop'      => 'Γέρoντες',
'group-bureaucrat' => 'Ἔφοροι',
'group-suppress'   => 'Παροράματα',

'group-user-member'       => 'Χρήστης',
'group-bot-member'        => 'Μεταβάλλων μηχανικός',
'group-sysop-member'      => 'Γέρων',
'group-bureaucrat-member' => 'Ἔφορος',
'group-suppress-member'   => 'Παρόραμα',

'grouppage-sysop'      => '{{ns:project}}:Γέροντες',
'grouppage-bureaucrat' => '{{ns:project}}:Ἔφοροι',

# Rights
'right-move'   => 'Μετακινεῖν δέλτους',
'right-upload' => 'Ἐπιφορτίζειν ἀρχεῖα',

# User rights log
'rightslog'  => 'Κατάλογος δικαιωμάτων χρωμένων',
'rightsnone' => '(Οὐδέν)',

# Recent changes
'nchanges'                       => '$1 {{PLURAL:$1|μεταβολή|μεταβολαί}}',
'recentchanges'                  => 'Αἱ νέαι μεταβολαί',
'recentchanges-feed-description' => 'Παρακολουθεῖν τὰς πλείω πρόσφατας ἀλλαγὰς τοῦ οὐίκι ἐν ταύτῃ περιλήψει.',
'rcnote'                         => "Κατωτέρω εἰσὶ {{PLURAL:$1|'''1''' ἀλλαγὴ|αἱ τελευταίαι '''$1''' ἀλλαγαὶ}} ἐν {{PLURAL:$2|τῇ τελευταίᾳ ἡμέρᾳ|ταῖς τελευταίαις '''$2''' ἡμέραις}}, ὡς καὶ $5, $4.",
'rcnotefrom'                     => "Ἰδοῦ αἱ ἀλλαγαὶ ἐκ τοῦ '''$2''' (ἕως τὸ '''$1''').",
'rclistfrom'                     => 'Δεικνύειν νέας ἀλλαγάς. Ἐκκίνησις ἐκ τοῦ $1',
'rcshowhideminor'                => '$1 μικραὶ μεταβολαὶ',
'rcshowhidebots'                 => '$1 αὐτόματα',
'rcshowhideliu'                  => '$1 χρωμένους συνδεδεμένους',
'rcshowhideanons'                => '$1 χρώμενοι ἀνώνυμοι',
'rcshowhidepatr'                 => '$1 Τὰς φυλασσόμενας μεταβολάς',
'rcshowhidemine'                 => '$1 μεταβολὰς μου',
'rclinks'                        => 'Ἐμφάνισις τῶν τελευταίων $1 ἀλλαγῷν τῷ χρονικῷ διαστήματι τῶν τελευταίων $2 ἡμερῷν <br />$3',
'diff'                           => 'διαφ.',
'hist'                           => 'Προτ.',
'hide'                           => 'Κρύπτειν',
'show'                           => 'Δεικνύναι',
'minoreditletter'                => 'μ',
'newpageletter'                  => 'Ν',
'boteditletter'                  => 'α',

# Recent changes linked
'recentchangeslinked'          => 'Οἰκεῖαι μεταβολαί',
'recentchangeslinked-title'    => 'Μεταβολαὶ οἰκεῖαι "$1"',
'recentchangeslinked-noresult' => 'Οὐδεμία ἀλλαγὴ τῶν συνδεδεμένων δέλτων ἐν τῇ δεδομένῃ χρονικῇ περιόδῳ.',
'recentchangeslinked-summary'  => "Ὅδε ἐστὶ κατάλογος τῶν νέων μεταβόλων κατὰ δέλτους συνδεδεμένας σὺν δέλτῳ τινί (ἢ κατὰ τὰς κατηγορίας τινός).
Δέλτοι ἐν τῷ [[Special:Watchlist|καταλόγῳ ἐφορωμένων]] σου '''ἔντοναι''' εἰσίν.",

# Upload
'upload'             => 'Ἀνάγειν γραφήν',
'uploadbtn'          => 'Φορτίον ἐντιθέναι',
'uploadnologin'      => 'Οὐ συνδεδεμένος',
'uploadlog'          => 'ἐπιφορτίζειν κατάλογον',
'uploadlogpage'      => 'Ἐπιφόρτωσις καταλόγου',
'filename'           => 'Ὄνομα ἀρχείου',
'filedesc'           => 'Τὸ κεφάλαιον',
'fileuploadsummary'  => 'Τὸ κεφάλαιον:',
'filesource'         => 'Πηγή:',
'successfulupload'   => 'Ἐπιφόρτισις ἐπιτυχής',
'uploadedimage'      => 'ἐπιφορτισμένον "[[$1]]"',
'upload-maxfilesize' => 'Μέγιστον μέγεθος ἀρχείου: $1',
'watchthisupload'    => 'Ἐφορᾶν τήνδε τὴν δέλτον',

'upload-proto-error' => 'Ἐσφαλμένον πρωθυπόμνημα',
'upload-file-error'  => 'Ἐσώτερον σφάλμα',

'license'   => 'Ἀδειηδότησις:',
'nolicense' => 'Οὐδὲν ἐπιλεγμένον',

# Special:Imagelist
'imagelist_search_for'  => 'Ἀναζήτησις τοῦ τῶν μέσων ὀνόματος:',
'imgfile'               => 'ἀρχεῖον',
'imagelist'             => 'Κατάλογος πάντων τῶν φορτίων',
'imagelist_date'        => 'Χρονολογία',
'imagelist_name'        => 'Ὄνομα',
'imagelist_user'        => 'Χρώμενος',
'imagelist_size'        => 'Ὁπόσος',
'imagelist_description' => 'Διέξοδος',

# Image description page
'filehist'                  => 'Τοῦ ἀρχείου συγγραφή',
'filehist-help'             => 'Πατήσατε ἐπὶ μίας χρονολογίας/ὥρας ἵνα ἴδετε τὸ ἀρχεῖον ὡς ἐμφανισθὲν ἐν ᾗπερ ὥρᾳ ᾗ.',
'filehist-deleteall'        => 'διαγράφειν ὅλα',
'filehist-deleteone'        => 'διαγράφειν',
'filehist-revert'           => 'ἀναστρέφειν',
'filehist-current'          => 'Τὸ νῦν',
'filehist-datetime'         => 'Ἡμέρα/Ὥρα',
'filehist-user'             => 'Χρώμενος',
'filehist-dimensions'       => 'Τὸ μέγαθος',
'filehist-filesize'         => 'Μέγεθος',
'filehist-comment'          => 'Σχόλιον',
'imagelinks'                => 'Σύνδεσμοι',
'linkstoimage'              => 'Αἱ ἀκόλουθοι/Ἡ ἀκόλουθος {{PLURAL:$1|δέλτοι σύνδεσμοι|$1 δέλτος σύνδεσμος}} πρὸς ταύτην εἰκόναν {{PLURAL:$1|εἰσίν|$1 ἐστίν}}.',
'nolinkstoimage'            => 'Οὐδένα ἐστὶ προσάγον τόδε τὸ φορτίον.',
'sharedupload'              => 'Τὸ ἀρχεῖον ταῦτο ἐπιφορτίσθη πρὸς κοινὴν χρῆσιν καὶ δύνασθε χρῆσθαι αὐτὸ εἰς ἕτερα σχέδια καὶ δέλτους ἐξἴσου.',
'noimage'                   => 'Οὐδένα ἐστὶ οὕτως ὀνομαστί, ἔξεστι σοὶ $1.',
'noimage-linktext'          => 'Ἐντιθέναι',
'uploadnewversion-linktext' => 'Ἐπιφορτίζειν μίαν νέαν ἐκδοχὴν ταύτου τοῦ ἀρχείου',

# File reversion
'filerevert-submit' => 'Ἀναστρέφειν',

# File deletion
'filedelete-submit' => 'Διαγράφειν',

# MIME search
'mimesearch' => 'MIME Ζητεῖν',
'mimetype'   => 'τύπος MIME:',
'download'   => 'καταφορτίζειν',

# List redirects
'listredirects' => 'Κατάλογος ἀναδιευθύνσεων',

# Unused templates
'unusedtemplates'    => 'Ἄχρηστα πρότυπα',
'unusedtemplateswlh' => 'οἱ σύνδεσμοι οἱ ἄλλοι',

# Random page
'randompage' => 'Δέλτος τυχοῦσα',

# Random redirect
'randomredirect' => 'Τυχαία ἀναδιεύθυνσις',

# Statistics
'statistics' => 'Τὰ περὶ τῶν δεδομένων',

'disambiguations' => 'Αἱ τινὰ ἱστάναι εἰς τὸ ἀναμφισβήτητον δέλτοι',

'doubleredirects' => 'Ἀναδιευθύνσεις διπλασιοζόμεναι',

'brokenredirects'        => 'Ἀναδιευθύνσεις οὐκέτι προὔργου οὖσαι',
'brokenredirects-edit'   => '(μεταγράφειν)',
'brokenredirects-delete' => '(διαγράφειν)',

'withoutinterwiki' => 'Δέλτοι ἄνευ γλωττικῶν συνδέσμων',

'fewestrevisions' => 'Δέλτοι ἔχουσαι τὰς ὀλιγωτέρας ἀναθεωρήσεις',

# Miscellaneous special pages
'nbytes'                  => '$1 {{PLURAL:$1|δυφιολέξις|δυφιολέξεις}}',
'ncategories'             => '$1 {{PLURAL:$1|κατηγορία|κατηγορίαι}}',
'nlinks'                  => '$1 {{PLURAL:$1|σύνδεσμος|σύνδεσμοι}}',
'nmembers'                => '$1 {{PLURAL:$1|μέλος|μέλη}}',
'nrevisions'              => '$1 {{PLURAL:$1|ἀναθεώρησις|ἀναθεωρήσεις}}',
'nviews'                  => '$1 {{PLURAL:$1|βλέψις|βλέψεις}}',
'lonelypages'             => 'Δέλτοι ὀρφαναί',
'uncategorizedpages'      => 'Αἱ δέλτοι αἱ οὐκ ἐνοῦσαι γένεσι',
'uncategorizedcategories' => 'Τὰ γένη τὰ οὐκ ἐνοῦντα γένεσι',
'uncategorizedimages'     => 'Ἀκατηγοριοποίητα ἀρχεῖα',
'uncategorizedtemplates'  => 'Ἀκατηγοριοποίητα πρότυπα',
'unusedcategories'        => 'Ἄχρησται κατηγορίαι',
'unusedimages'            => 'Ἄχρηστα ἀρχεῖα',
'wantedcategories'        => 'Αἰτούμεναι κατηγορίαι',
'wantedpages'             => 'Αἱ δέλτοι οἷας ἱμείρομεν',
'mostlinked'              => 'Αἱ πλέον προσσυνδεδεμέναι δέλτοι',
'mostlinkedcategories'    => 'Αἱ πλέον προσσυνδεδεμέναι κατηγορίαι',
'mostlinkedtemplates'     => 'Τὰ πλέον προσσυνδεδεμένα πρότυπα',
'mostcategories'          => 'Δέλτοι ἔχουσαι τὰς πλείονας κατηγορίας',
'mostimages'              => 'Τὰ πλέον προσσυνδεδεμένα ἀρχεῖα',
'mostrevisions'           => 'Αἱ δέλτοι αἱ πλειστάκις μεταβεβλήμεναι',
'prefixindex'             => 'Προθέματος δείκτης',
'shortpages'              => 'Δέλτοι μικραί',
'longpages'               => 'Δέλτοι μακραί',
'deadendpages'            => 'Ἀδιέξοδαι δέλτοι',
'protectedpages'          => 'Αἱ δέλτοι αἱ φυλαττομέναι',
'listusers'               => 'Κατάλογος πάντων τῶν χρωμένων',
'newpages'                => 'Δέλτοι νέαι',
'ancientpages'            => 'Αἱ παλαιόταται δέλτοι',
'move'                    => 'κινεῖν',
'movethispage'            => 'Κινεῖν τήνδε τὴν δέλτον',

# Book sources
'booksources'    => 'Αἱ ἐν βίβλοις πηγαί',
'booksources-go' => 'Ἰέναι',

# Special:Log
'specialloguserlabel'  => 'Χρήστης·',
'speciallogtitlelabel' => 'Ὄνομα:',
'log'                  => 'Κατάλογοι',
'all-logs-page'        => 'Κατάλογοι ἅπαντες',
'log-search-submit'    => 'Ἰέναι',

# Special:Allpages
'allpages'       => 'Πᾶσαι αἱ δέλτοι',
'alphaindexline' => '$1 ἕως $2',
'nextpage'       => 'Ἡ δέλτος ἡ ἐχομένη ($1)',
'prevpage'       => 'Ἡ δέλτος ἡ προτέρη ($1)',
'allpagesfrom'   => 'Ἐπιδεικνύειν τὰς δέλτους ἐκ:',
'allarticles'    => 'Πάντες γραφαί',
'allpagessubmit' => 'Ἰέναι',
'allpagesprefix' => 'Ἐπιδεικνύειν δέλτους ἔχουσας πρόθεμα:',

# Special:Categories
'categories' => 'Γένη',

# E-mail user
'emailuser'    => 'Ἠλεκτρονικὴν ἐπιστολὴν τῷδε τῷ χρήστῃ πέμπειν',
'emailmessage' => 'Ἀγγελία',
'emailsend'    => 'Πέμπειν',

# Watchlist
'watchlist'            => 'Τὰ ἐφορώμενά μου',
'mywatchlist'          => 'Τὰ ἐφορώμενά μου',
'watchlistfor'         => "(πρό '''$1''')",
'watchnologin'         => 'Οὐ συνδεδεμένος',
'addedwatch'           => 'Δέλτος προστεθειμένη εἰς τὸν ἐποπτευομένων κατάλογον ἐστίν',
'removedwatch'         => 'Ἀνεώραται ἥδε ἡ δέλτος',
'removedwatchtext'     => 'Ἀνεώραται ἡ δέλτος "[[:$1]]"',
'watch'                => 'Ἐφορᾶν',
'watchthispage'        => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'unwatch'              => 'Ἀνεφορᾶν',
'notanarticle'         => 'οὐ χρῆμα δέλτος',
'watchlist-details'    => '{{PLURAL:$1|$1 δέλτος|$1 δέλτοι}} ἐφορωμέναι, ἄνευ τῶν δέλτων συζητήσεως περιλαμβανομένων.',
'wlshowlast'           => 'Ἐμφάνισις τῶν τελευταίων $1 ὡρῶν $2 ἡμερῶν $3',
'watchlist-hide-bots'  => 'Κρύπτειν τὰς ὑπ᾿ αὐτομάτων μεταβολάς',
'watchlist-show-own'   => 'Δεικνύναι τοὺς ἐράνους μου',
'watchlist-hide-own'   => 'Κρύπτειν τὰς ὑπ᾿ ἐμοῦ μεταβολάς',
'watchlist-show-minor' => 'Δεικνύναι τὰς μικρὰς μεταβολάς',
'watchlist-hide-minor' => 'Κρύπτειν τὰς μικρὰς μεταβολάς',

# Displayed when you click the "watch" button and it is in the process of watching
'watching'   => 'Ἐφορῶν...',
'unwatching' => 'Ἀνεφορῶν...',

# Delete/protect/revert
'deletepage'                  => 'Διαγράφειν τὴν δέλτον',
'confirm'                     => 'Κυροῦν',
'exblank'                     => 'δέλτος κενὴ ἦν',
'delete-confirm'              => 'Διαγράφειν "$1"',
'delete-legend'               => 'Διαγράφειν',
'historywarning'              => 'Προσοχή: Ἡ δέλτος ἥντινα βούλεσαι διαγράψειν ἔχει ἱστορίαν:',
'actioncomplete'              => 'Τέλειον τὸ ποιούμενον',
'deletedarticle'              => 'Ἐσβέσθη τὴν δέλτον "[[$1]]"',
'dellogpage'                  => 'Τὰ ἐσβεσένα',
'deletecomment'               => 'Αἰτία τοῦ σβεννύναι:',
'deleteotherreason'           => 'Αἰτία ἄλλα:',
'deletereasonotherlist'       => 'Αἰτία ἄλλα',
'rollback'                    => 'Ἀναστρέφειν μεταγραφάς',
'rollback_short'              => 'Ἀναστροφή',
'rollbacklink'                => 'ἀναστροφή',
'rollbackfailed'              => 'Ἀναστροφὴ μὴ ἐπιτυχής',
'protectlogpage'              => 'Κατάλογος προφυλάξεων',
'protectcomment'              => 'Σχόλιον:',
'protectexpiry'               => 'Ἐξήξει:',
'protect_expiry_invalid'      => 'Ἄκυρος χρόνος λήξεως.',
'protect_expiry_old'          => 'Χρόνος λήξεως ἐν τῷ παρελθόντι ἐστίν.',
'protect-unchain'             => 'Δικαιώματα μετακινήσεως ἐκκλειδοῦν',
'protect-text'                => 'Ὁρᾶν τε καὶ ἀλλάττειν δύνασθε τὴν κλίμακα προστασίας ἐνθάδε διὰ τὴν δέλτον <strong><nowiki>$1</nowiki></strong>.',
'protect-default'             => '(κριτήριον)',
'protect-fallback'            => 'Δεῖ ἐχεῖν τὴν "$1" οὐσίαν',
'protect-level-autoconfirmed' => 'Ἀποκλῄειν τοὺς ἀγράφους',
'protect-level-sysop'         => 'Μόνοι οἱ γέροντες',
'protect-summary-cascade'     => 'Διαδεχόμενον',
'protect-expiring'            => 'Ἐξήξει $1 (UTC)',
'protect-cascade'             => 'Προφυλάττειν δέλτους περικεκλεισμένας ἐν τοιαύτῃ δελτῳ (διαδοχικὴ προφύλαξις)',
'protect-cantedit'            => 'Οὐ δύνασθε ἀλλάττειν τὴν κλίμακα προστασίας ταύτης τῆς δέλτου, διότι οὐκ ἔχετε τὴν ἀδείαν τοῦ μεταγράφειν αὐτήν.',
'restriction-type'            => 'Ἐξουσία:',
'restriction-level'           => 'Κλῖμαξ περιορισμοῦ:',

# Restrictions (nouns)
'restriction-edit'   => 'Μεταγράφειν',
'restriction-move'   => 'Kινεῖν',
'restriction-upload' => 'Ἐπιφορτίζειν',

# Restriction levels
'restriction-level-sysop'         => 'πλήρως διαφυλαττομένη',
'restriction-level-autoconfirmed' => 'ἡμιδιαφυλαττομένη',

# Undelete
'viewdeletedpage'        => 'Δεικνύναι διαγραφείσας δέλτους',
'undeletebtn'            => 'Ἀνορθοῦν',
'undeletecomment'        => 'Σχόλιον:',
'undelete-search-submit' => 'Ζητεῖν',

# Namespace form on various pages
'namespace'      => 'Ὀνομεῖον:',
'invert'         => 'Ἀντιστρέφειν ἐπιλογήν',
'blanknamespace' => '(Κυρία γραφή)',

# Contributions
'contributions' => 'Ἔρανοι χρωμένου',
'mycontris'     => 'Ἔρανοί μου',
'contribsub2'   => 'Πρὸς $1 ($2)',
'uctop'         => '(ἄκρον)',
'month'         => 'Μήν:',
'year'          => 'Ἔτος:',

'sp-contributions-newbies-sub' => 'Ἔρανοι νέων χρωμένων',
'sp-contributions-blocklog'    => 'Τὰ ἀποκλῄειν',
'sp-contributions-submit'      => 'Ζητεῖν',

# What links here
'whatlinkshere'       => 'Τὰ ἐνθάδε ἄγοντα',
'whatlinkshere-title' => 'Δέλτοι ἐζεύγμεναι ὑπο $1',
'linklistsub'         => '(Κατάλογος τῶν συνδέσμων)',
'linkshere'           => "Τάδε ἄγουσι πρὸς '''[[:$1]]''':",
'nolinkshere'         => "Οὐδένα ἄγουσι πρὸς '''[[:$1]]'''.",
'isredirect'          => 'ἀναδιευθύνειν δέλτον',
'istemplate'          => 'περίκλεισις',
'whatlinkshere-prev'  => '{{PLURAL:$1|πρότερον|Τὰ $1 πρότερα}}',
'whatlinkshere-next'  => '{{PLURAL:$1|ἑξῆς|οἱ $1 ἑξαῖ}}',
'whatlinkshere-links' => '← σύνδεσμοι',

# Block/unblock
'blockip'            => 'Ἀποκλῄειν τόνδε τὸν χρώμενον',
'ipboptions'         => 'βʹ ὥραι:2 hours,αʹ ἡμέρα day:1 day,γʹ ἡμέραι:3 days,ζʹ ἡμέραι:1 week,ιδʹ ἡμέραι:2 weeks,αʹ μήν:1 month,γʹ μήνες:3 months,ϝʹ μήνες:6 months,αʹ ἔτος:1 year,ἄπειρον:infinite', # display1:time1,display2:time2,...
'ipblocklist'        => 'Κατάλογος τῶν φραγμένων IP-διευθύνσεων καὶ ὀνομάτων τῶν χρωμένων',
'ipblocklist-submit' => 'Ζητεῖν',
'infiniteblock'      => 'ἄπειρον',
'blocklink'          => 'ἀποκλῄειν',
'unblocklink'        => 'χαλᾶν φραγήν',
'contribslink'       => 'Ἔρανοι',
'blocklogpage'       => 'Τὰ ἀποκλῄειν',
'blocklogentry'      => 'Κεκλῃμένος [[$1]] μέχρι οὗ $2 $3',

# Move page
'move-page-legend' => 'Κινεῖν τὴν δέλτον',
'movepagetext'     => "Χρῆτε τὸν ἀκόλουθον τύπον δια την μετωνόμασιν της δέλτου καὶ δια την μεταφοραν ὅλου τοῦ ἑου ιστορικου ὑπο το νέον ὄνομα. Η προηγουμένη επιγραφη της δέλτου ἔσται μία δέλτος ἀνακατευθύνσεως. Οι τυχόντες σύνδεσμοι προς την προηγουμένην δέλτον ἀναλλοίωτοι ἔσονται. Βεβαιοῦσθε περὶ τῆς μη ὑπάρξεως διπλῶν ἢ διεφθαρμένων συνδέσμων. Αναλαμβάνετε την ευθύνην του επιβεβαιωσαι την ορθην καὶ ὑποτιθέμενην κατεύθυνσιν των συνδέσμων.

Ἐπισημείωσις: ἡ δέλτος '''οὐ''' κινήσεται εἰ ὑπάρχει ἤδη ἑτέρα δέλτος ὑπὸ τὴν νέαν ἐπιγραφήν, εἰ μὴ ἡ δελτος ταύτη κενή ἐστι '''καὶ οὐκ''' ἔχει ἱστορίαν. Δῆλα δή, εν περιπτώσει λάθους υμων, δύνασθε μετωνομάσειν μιαν δέλτον, δίδοντες αὐτῇ τὴν ἑὴν πρότερην ονομασίαν, αλλα ου δύνασθε ὑποκαταστήσειν μιαν προϋπάρχουσαν δέλτον.

'''ΠΡΟΣΟΧΗ!'''
Ἡ μετωνόμασις δέλτου τινος αιφνιδία καὶ δραστικη αλλαγή ἐστιν ὁπηνίκα πρόκειται περὶ δημοφιλοῦς δέλτου. Παρακαλοῦμεν ἵνα εξετάζητε τας πιθανας επιπτώσεις ταύτης της ενεργείας, προ της αποφάσεως υμων.",
'movepagetalktext' => "Ἡ σχετικη δέλτος συζητήσεως μετακινήσεται αυτομάτως μετα της δελτου εγγραφης '''εκτός ει ου(κ):'''
*Μετακινησεις την δελτον εις διαφορον ονοματικον χωρον (namespace), η
*Υπάρχει υπο το νέον ονομα μη κενη δέλτος τις συζητησεως, η
*Ἀφῄρηκας την κατασήμανσιν (check) εκ του κυτίου κατωτέρω.

Εν ταύταις ταις περιπτώσεσιν, δει μετακινῆσαι η συγχωνευσαι την δελτον μέσω αντιγραφης-και-επικόλλησεως.",
'movearticle'      => 'Κινεῖν τὴν δέλτον:',
'movenologin'      => 'Οὐ συνδεδεμένος',
'newtitle'         => 'Πρὸς τὸ νέον ὄνομα:',
'move-watch'       => 'Ἑφορᾶν τήνδε τὴν δέλτον',
'movepagebtn'      => 'Κινεῖν τὴν δέλτον',
'pagemovedsub'     => 'Κεκίνηται ἡ δέλτος',
'movepage-moved'   => '<big>\'\'\'"$1" κεκίνηται πρὸς "$2"\'\'\'</big>', # The two titles are passed in plain text as $3 and $4 to allow additional goodies in the message.
'movedto'          => 'Κεκίνηται πρὸς',
'movetalk'         => 'Κινεῖν τὴν διαλόγου δέλτον',
'1movedto2'        => '[[$1]] ἐκινήθη πρὸς [[$2]]',
'movelogpage'      => 'Τὰ κινεῖν',
'movereason'       => 'Ἀπολογία:',
'revertmove'       => 'Ἐπανέρχεσθαι',

# Export
'export' => 'Δέλτους ἐξάγειν',

# Namespace 8 related
'allmessages'     => 'Μυνήματα συστήματος',
'allmessagesname' => 'Ὄνομα',

# Thumbnails
'thumbnail-more'  => 'Αὐξάνειν',
'thumbnail_error' => 'Σφάλμα τοῦ δημιουργεῖν σύνοψιν: $1',

# Special:Import
'import-upload' => 'Ἐπιφόρτωσις δεδομένων XML',

# Import log
'importlogpage'             => 'Εἰσάγειν κατάλογον',
'import-logentry-interwiki' => 'οὐικιπεποιημένη $1',

# Tooltip help for the actions
'tooltip-pt-userpage'             => 'Τὴν δέλτον χρωμένου ἐμήν',
'tooltip-pt-mytalk'               => 'Διάλεκτός μου',
'tooltip-pt-preferences'          => 'Αἱρέσεις μου',
'tooltip-pt-watchlist'            => 'Κατάλογος τῶν ἐφορωμένων μου',
'tooltip-pt-mycontris'            => 'Κατάλογος τῶν ἐράνων μου',
'tooltip-pt-login'                => 'Ἐνθαρρυντέον τὸ συνδεῖσθαι, οὐκ υποχρεωτικόν.',
'tooltip-pt-anonlogin'            => 'Ἐνθαρρυντέον τὸ συνδεῖσθαι, οὐκ υποχρεωτικόν.',
'tooltip-pt-logout'               => 'Ἐξέρχεσθαι',
'tooltip-ca-talk'                 => 'Διάλεκτος περὶ τῆς δέλτου',
'tooltip-ca-edit'                 => 'Ἔξεστι σοι μεταγράφειν τήνδε τὴν δέλτον. Προεπισκοπεῖν πρὶν ἂν γράφῃς τὴν δέλτον.',
'tooltip-ca-addsection'           => 'Προστιθέναι σχόλιόν τι τῇ συζητήσει.',
'tooltip-ca-viewsource'           => 'Σῴζεται ἥδε ἡ δέλτος.
Ἔξεστι σοὶ τὴν πηγήν ἐπισκοπεῖν.',
'tooltip-ca-protect'              => 'Ἀμύνειν τῇδε τῇ δέλτῳ',
'tooltip-ca-delete'               => 'Διαγράφειν τήνδε τὴν δέλτον',
'tooltip-ca-move'                 => 'Κινεῖν τήνδε τὴν δέλτον',
'tooltip-ca-watch'                => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'tooltip-ca-unwatch'              => 'Ἀνεφορᾶν τήνδε τὴν δέλτον',
'tooltip-search'                  => 'Ζητεῖν {{SITENAME}}',
'tooltip-p-logo'                  => 'Δέλτος Μεγίστη',
'tooltip-n-mainpage'              => 'Πορεύεσθαι τὴν κυρίαν Δέλτον',
'tooltip-n-portal'                => 'Τὰ περὶ ταῦτης τε τὴς ἐνκυκλοπαιδείας, τῶν τε οἷων ἔξεστι σοὶ πράττεις, οὗ παρεστὶ τινά',
'tooltip-n-currentevents'         => 'Πληροφορίαι διὰ ἐπίκαιρα γεγονότα',
'tooltip-n-recentchanges'         => 'Κατάλογος κατὰ πᾶσας τὰς νέας μεταβολάς.',
'tooltip-n-randompage'            => 'Τινὰ γραφὴν χύδην δηλοῦν.',
'tooltip-n-help'                  => 'Μάθησις περὶ τῆσδε Οὐίκεως',
'tooltip-t-whatlinkshere'         => 'Κατάλογος τῶν ἐνθάδε ἀγόντων',
'tooltip-t-contributions'         => 'Ὁρᾶν τοὺς τοῦδε τοῦ χρωμένου ἐράνους',
'tooltip-t-emailuser'             => 'Ἠλεκτρονικὴν ἐπιστολὴν τῷδε τῷ χρήστῃ πέμπειν',
'tooltip-t-upload'                => 'Φορτία ἐντιθέναι',
'tooltip-t-specialpages'          => 'Κατάλογος κατὰ πᾶσας τὰς εἰδικὰς δέλτους',
'tooltip-ca-nstab-main'           => 'χρῆμα δέλτον ὁρᾶν',
'tooltip-ca-nstab-user'           => 'Δέλτος χρωμένου ὁρᾶν',
'tooltip-ca-nstab-project'        => 'Ὁρᾶν τὴν δέλτον τοῦ σχεδίου',
'tooltip-ca-nstab-image'          => 'Ὁρᾶν τὴν τοῦ φορτίου δέλτον',
'tooltip-ca-nstab-template'       => 'Ὁρᾶν πρότυπον',
'tooltip-ca-nstab-help'           => 'Ὁρᾶν δέλτον βοηθείας',
'tooltip-ca-nstab-category'       => 'Ἐπισκοπεῖν τὴν τῆς κατηγορίας δέλτον',
'tooltip-minoredit'               => 'Δεικνύναι ἥδε ἡ μεταβολή μικρά εἴναι',
'tooltip-save'                    => 'Γράφειν τὰς μεταβολάς σου',
'tooltip-preview'                 => 'Προεπισκοπεῖν τὰς ἀλλαγὰς ὑμῶν. Παρακαλοῦμεν ὑμᾶς ἵνα χρῆτε ταύτην τὴν ἐπιλογὴν πρὸ τοῦ αποθηκεύειν!',
'tooltip-diff'                    => 'Δεῖξαι τὰς γεγράμμενας μεταβολάς.',
'tooltip-compareselectedversions' => 'Ὁρᾶν τὰς διαφορὰς μεταξὺ τῶν δύω ἐπιλεγμένων ἐκδοχῶν ταύτης τῆς δέλτου.',
'tooltip-watch'                   => 'Ἐφορᾶν τήνδε τὴν δέλτον',
'tooltip-upload'                  => 'Ἐκκινεῖν ἀναφόρτωσιν',

# Attribution
'others'      => 'ἄλλοι',
'creditspage' => 'Διαπιστεύσεις δέλτου',

# Info page
'numtalkedits' => 'Ἀριθμὸς μεταγραφῶν (δέλτος συζητήσεως): $1',

# Math options
'mw_math_png' => 'Ἀπόδοσις PNG πάντοτε',

# Patrolling
'markaspatrolleddiff' => 'Σεσημασμένη ὡς ἐπιτηρουμένη',

# Patrol log
'patrol-log-page' => 'Κατάλογος περιπόλων',
'patrol-log-auto' => '(αὐτόματον)',

# Browsing diffs
'previousdiff' => '← ἡ μεταβολὴ ἡ προτέρη',
'nextdiff'     => 'ἡ μεταβολὴ ἡ ἐχομένη →',

# Media information
'file-info-size'       => '($1 × $2 εἰκονοστοιχεῖα, μέγεθος ἀρχείου: $3, τύπος MIME: $4)',
'file-nohires'         => '<small>Οὐ διατίθεται ὑψηλοτέρα ἀνάλυσις.</small>',
'svg-long-desc'        => '(αρχεῖον SVG, ὀνομαστικὼς $1 × $2 εἰκονοστοιχεῖα, μέγεθος ἀρχείου: $3)',
'show-big-image'       => 'Πλήρης ἀνάλυσις',
'show-big-image-thumb' => '<small>Τοῦδε προεπισκοπεῖν μέγεθος: $1 × $2 εἰκονοστοιχεία</small>',

# Special:Newimages
'newimages'    => 'Τὰ νέα φορτία δεῦρο ἀθροίζειν',
'showhidebots' => '($1 αὐτόματα)',
'ilsubmit'     => 'Ζητεῖν',
'bydate'       => 'κατὰ χρονολογίαν',

# Bad image list
'bad_image_list' => 'Ἡ μορφοποιία ὡς ἑξῆς ἐστίν:

Μόνον ἀντικείμενα διαλογῆς (γραμμαὶ ἐκκινουμέναι μετὰ τοῦ *) δεκτὰ εἰσί. 
Ὁ πρῶτος σύνδεσμος ἐν ἐνίτινι γραμμῇ ὀφείλει εἶναι σύνδεσμος πρὸς ἕνα κακὸν ἀρχεῖον.
Οἷοι δή ποτε ἐπακόλουθοι σύνδεσμοι ἐν τῇ αυτῇ γραμμῇ θεωρουμένοι ἐξαιρέσεις εἰσίν, δῆλα δὴ δέλτοι ὅπου ἡ εἰκὼν δύναται ξυμβῆναι ἐν συνδέσει.',

# Metadata
'metadata'          => 'Μεταδεδομένα',
'metadata-expand'   => 'Δηλοῦν τὰς ἀκριβείας',
'metadata-collapse' => 'Κρύπτειν τὰς ἀκριβείας',
'metadata-fields'   => 'Τὰ πεδία μεταδεδομένων EXIF ταύτου τοῦ μηνύματος περιλήψονται ἐν τῇ δέλτῳ ἐμφανίσεως εἰκόνος ὁπηνίκα ὁ πίναξ μεταδεδομένων ἀποκρύψηται. Τὰ ἕτερα πεδία ἔσονται κεκρυμμένα κατὰ προεπιλογήν.
* make
* model
* datetimeoriginal
* exposuretime
* fnumber
* focallength', # Do not translate list items

# EXIF tags
'exif-imagewidth'          => 'Πλάτος',
'exif-imagelength'         => 'Ὕψος',
'exif-orientation'         => 'Προσανατόλισις',
'exif-planarconfiguration' => 'Παράταξις δεδομένων',
'exif-xresolution'         => 'Ὁριζόντιος ἀνάλυσις',
'exif-yresolution'         => 'Κάθετος ἀνάλυσις',
'exif-exposuretime'        => 'Χρόνος ἐκθέσεως',
'exif-exposuretime-format' => '$1 δευτ. ($2)',
'exif-fnumber'             => 'F Ἀριθμός',
'exif-oecf'                => 'Παράγων ὀπτοηλεκτονικῆς μετατροπῆς',
'exif-filesource'          => 'Πηγὴ ἀρχείου',
'exif-contrast'            => 'Ἀντίθεσις',
'exif-saturation'          => 'Κορεσμός',
'exif-sharpness'           => 'Ὀξύτης',
'exif-gpslatituderef'      => 'Βόρειον ἢ Νότιον γεωγραφικὸν πλάτος',
'exif-gpslatitude'         => 'Πλάτος γεωγραφικόν',
'exif-gpslongituderef'     => 'Ἀνατολικὸν ἢ Δυτικὸν γεωγραφικὸν μῆκος',
'exif-gpslongitude'        => 'Γεωγραφικὸν μῆκος',
'exif-gpsaltituderef'      => 'Ἀναφορὰ ὕψους γεωγραφικοῦ',
'exif-gpsaltitude'         => 'Γεωγραφικὸν ὕψος',
'exif-gpsspeedref'         => 'Μονὰς ταχύτητος',
'exif-gpsdatestamp'        => 'Χρονολογία GPS',

'exif-unknowndate' => 'Ἄγνωτος χρονολογία',

'exif-orientation-1' => 'Κανονικόν', # 0th row: top; 0th column: left
'exif-orientation-3' => 'Περιεστραμμένον κατὰ 180°', # 0th row: bottom; 0th column: right

'exif-componentsconfiguration-0' => 'Οὐκ ἔστι',

'exif-exposureprogram-1' => 'Χειροκίνητον',

'exif-subjectdistance-value' => '$1 μέτρα',

'exif-meteringmode-0'   => 'Ἄγνωτον',
'exif-meteringmode-1'   => 'Μέσον',
'exif-meteringmode-5'   => 'Ὑπόδειγμα',
'exif-meteringmode-6'   => 'Μερικόν',
'exif-meteringmode-255' => 'Ἄλλη',

'exif-lightsource-0'  => 'Ἄγνωτη',
'exif-lightsource-2'  => 'Φθορίζον',
'exif-lightsource-11' => 'Σκίασις',

'exif-sensingmethod-1' => 'Ἀόριστος',

'exif-customrendered-0' => 'Κανονικὴ διαδικασία',
'exif-customrendered-1' => 'Συνήθης διαδικασία',

'exif-exposuremode-0' => 'Αὐτοέκθεσις',

'exif-gaincontrol-0' => 'Οὐδεμία',

'exif-contrast-0' => 'Κανονική',
'exif-contrast-1' => 'Ἁπαλή',
'exif-contrast-2' => 'Σκληρή',

'exif-saturation-0' => 'Κανονικόν',
'exif-saturation-1' => 'Χαμηλὸς κορεσμός',
'exif-saturation-2' => 'Ὑψηλὸς κορεσμός',

'exif-sharpness-0' => 'Κανονική',
'exif-sharpness-1' => 'Ἁπαλή',
'exif-sharpness-2' => 'Σκληρή',

'exif-subjectdistancerange-0' => 'Ἄγνωτη',
'exif-subjectdistancerange-2' => 'Ἐγγεῖα θέα',
'exif-subjectdistancerange-3' => 'Ἀφεστηκυῖα θέα',

# Pseudotags used for GPSLatitudeRef and GPSDestLatitudeRef
'exif-gpslatitude-n' => 'Βόρειον γεωγραφικὸν πλάτος',
'exif-gpslatitude-s' => 'Νότιον γεωγραφικὸν πλάτος',

# Pseudotags used for GPSLongitudeRef and GPSDestLongitudeRef
'exif-gpslongitude-e' => 'Ἀνατολικὸν γεωγραφικὸν μῆκος',
'exif-gpslongitude-w' => 'Δυτικὸν γεωγραφικὸν μῆκος',

# Pseudotags used for GPSSpeedRef and GPSDestDistanceRef
'exif-gpsspeed-n' => 'Κόμβοι',

# External editor support
'edit-externally'      => 'Μεταγράφειν ταῦτο τὸ ἀρχεῖον χρωμένοι μίαν ἐξώτερην ἐφαρμογήν.',
'edit-externally-help' => 'Εἰ πλείοντα βούλει μαθεῖν, [http://meta.wikimedia.org/wiki/Help:External_editors τὰς περὶ τοῦ σχῆματος διδασκαλίας] λέξε.',

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

# AJAX search
'hideresults' => 'Ἀποκρύπτειν ἀποτελέσματα',

# Multipage image navigation
'imgmultipageprev' => '← Δέλτος προτέρα',
'imgmultipagenext' => 'Δέλτος ἡ ἐχομένη →',
'imgmultigo'       => 'Ἰέναι!',

# Table pager
'ascending_abbrev'         => 'ἀναβ',
'descending_abbrev'        => 'καταβ',
'table_pager_next'         => 'Δέλτος ἡ ἐχομένη',
'table_pager_prev'         => 'Δέλτος προτέρα',
'table_pager_first'        => 'Δέλτος πρώτη',
'table_pager_last'         => 'Δέλτος ἐσχάτη',
'table_pager_limit_submit' => 'Ἰέναι',
'table_pager_empty'        => 'Οὐδὲν ἀποτέλεσμα',

# Auto-summaries
'autosumm-new' => 'Δέλτος νέα: $1',

# Size units
'size-bytes'     => '$1 Δ',
'size-kilobytes' => '$1 ΧΔ',
'size-megabytes' => '$1 ΜΔ',
'size-gigabytes' => '$1 ΓΔ',

# Live preview
'livepreview-loading' => 'Φορτεῖν…',

# Watchlist editor
'watchlistedit-raw-titles' => 'Ἐπιγραφαί:',

# Watchlist editing tools
'watchlisttools-view' => 'Ὁρᾶν τὰς πρὸς ταῦτα μεταβολὰς',
'watchlisttools-edit' => 'Ὁρᾶν τε μεταγράφειν τὰ ἐφορωμένα',
'watchlisttools-raw'  => 'Μεταγράφειν τὸν πρωτογενῆ κατάλογον ἐφορωμένων',

# Special:Version
'version'                  => 'Ἐπανόρθωμα', # Not used as normal message but as header for the special page itself
'version-specialpages'     => 'Εἰδικαὶ δέλτοι',
'version-variables'        => 'Μεταβληταί',
'version-other'            => 'Ἄλλα',
'version-version'          => 'Ἐκδοχή',
'version-license'          => 'Ἄδεια',
'version-software-version' => 'Ἐκδοχή',

# Special:Filepath
'filepath-page' => 'Ἀρχεῖον:',

# Special:FileDuplicateSearch
'fileduplicatesearch-filename' => 'Ὄνομα ἀρχείου:',
'fileduplicatesearch-submit'   => 'Ἀναζήτησις',

# Special:SpecialPages
'specialpages'                 => 'Εἰδικαὶ δέλτοι',
'specialpages-group-login'     => 'Συνδεῖσθαι / ἐγγράφεσθαι',
'specialpages-group-highuse'   => 'Ὑψηλῆς χρήσεως δέλτοι',
'specialpages-group-pages'     => 'Κατάλογος δέλτων',
'specialpages-group-pagetools' => 'Ἐργαλεῖα δέλτου',
'specialpages-group-wiki'      => 'Οὐκιδεδομένα καὶ στοιχεῖα',

# Special:Blankpage
'blankpage'              => 'Κενὴ δέλτος',
'intentionallyblankpage' => 'Ταῦτη ἡ δέλτος ἀφίεται ἐσκεμμένως κενὴ καὶ ἐστὶ χρήσιμη ὡς σημεῖον ἀναφορᾶς, κτλ.',

);
