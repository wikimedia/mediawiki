<?php
/** Swiss High German (Schweizer Hochdeutsch)
 *
 * See MessagesQqq.php for message documentation incl. usage of parameters
 * To improve a translation please visit http://translatewiki.net
 *
 * @ingroup Language
 * @file
 *
 * @author MichaelFrey
 */

$fallback = 'de';
$separatorTransformTable = array( ',' => "'", '.' => ',' );

$messages = array(
# Edit pages
'longpagewarning' => "'''WARNUNG: Diese Seite ist $1 KB gross; einige Browser könnten Probleme haben, Seiten zu bearbeiten, die grösser als 32 KB sind.
Überlege bitte, ob eine Aufteilung der Seite in kleinere Abschnitte möglich ist.'''",
'longpageerror'   => "'''FEHLER: Der Text, den du zu speichern versuchst, ist $1 KB gross. Das ist grösser als das erlaubte Maximum von $2 KB – Speicherung nicht möglich.'''",

# Parser/template warnings
'post-expand-template-inclusion-warning'  => 'Warnung: Die Grösse eingebundener Vorlagen ist zu gross, einige Vorlagen können nicht eingebunden werden.',
'post-expand-template-inclusion-category' => 'Seiten, in denen die maximale Grösse eingebundener Vorlagen überschritten ist',
'post-expand-template-argument-warning'   => 'Warnung: Diese Seite enthält mindestens ein Argument in einer Vorlage, das expandiert zu gross ist. Diese Argumente werden ignoriert.',

# Search results
'nonefound' => "'''Hinweis:''' Es werden standardmässig nur einige Namensräume durchsucht. Setze ''all:'' vor deinen Suchbegriff, um alle Seiten (inkl. Diskussionsseiten, Vorlagen usw.) zu durchsuchen oder gezielt den Namen des zu durchsuchenden Namensraumes.",

# Preferences page
'prefs-watchlist-days' => 'Anzahl der Tage, die die Beobachtungsliste standardmässig umfassen soll:',
'prefs-edit-boxsize'   => 'Grösse des Bearbeitungsfensters:',
'defaultns'            => 'In diesen Namensräumen soll standardmässig gesucht werden:',

# Upload
'largefileserver'      => 'Die Datei ist grösser als die vom Server eingestellte Maximalgrösse.',
'fileexists-extension' => "Eine Datei mit ähnlichem Namen existiert bereits: [[$2|thumb]]
* Name der hochzuladenden Datei: '''<tt>[[:$1]]</tt>'''
* Name der vorhandenen Datei: '''<tt>[[:$2]]</tt>'''
Nur die Dateiendung unterscheidet sich in Gross-/Kleinschreibung. Bitte prüfe, ob die Dateien inhaltlich identisch sind.",
'file-thumbnail-no'    => "Der Dateiname beginnt mit '''<tt>$1</tt>'''. Dies deutet auf ein Bild verringerter Grösse ''(thumbnail)'' hin.
Bitte prüfe, ob du das Bild in voller Auflösung vorliegen hast und lade dieses unter dem Originalnamen hoch.",

# Special:ListFiles
'listfiles-summary' => 'Diese Spezialseite listet alle hochgeladenen Dateien auf. Standardmässig werden die zuletzt hochgeladenen Dateien zuerst angezeigt. Durch einen Klick auf die Spaltenüberschriften kann die Sortierung umgedreht werden oder es kann nach einer anderen Spalte sortiert werden.',
'listfiles_size'    => 'Grösse',

# File description page
'filehist-dimensions' => 'Masse',
'filehist-filesize'   => 'Dateigrösse',

# Special:Log
'alllogstext' => 'Dies ist die kombinierte Anzeige aller in {{SITENAME}} geführten Logbücher.
Die Ausgabe kann durch die Auswahl des Logbuchtyps, des Benutzers oder des Seitentitels eingeschränkt werden (Gross-/Kleinschreibung muss beachtet werden).',

# Protect
'minimum-size' => 'Mindestgrösse',
'maximum-size' => 'Maximalgrösse:',

# Block/unblock
'ipbreason-dropdown' => '* Allgemeine Sperrgründe
** Löschen von Seiten
** Einstellen unsinniger Seiten
** Fortgesetzte Verstösse gegen die Richtlinien für Weblinks
** Verstoss gegen den Grundsatz „Keine persönlichen Angriffe“
* Benutzerspezifische Sperrgründe
** Ungeeigneter Benutzername
** Neuanmeldung eines unbeschränkt gesperrten Benutzers
* IP-spezifische Sperrgründe
** Proxy, wegen Vandalismus einzelner Benutzer längerfristig gesperrt',

# Thumbnails
'djvu_page_error' => 'DjVu-Seite ausserhalb des Seitenbereichs',

# Special:Import
'importuploaderrorsize' => 'Das Hochladen der Importdatei ist fehlgeschlagen. Die Datei ist grösser als die maximal erlaubte Dateigrösse.',

# Media information
'imagemaxsize'   => 'Maximale Bildgrösse auf Bildbeschreibungsseiten:',
'file-info'      => '(Dateigrösse: $1, MIME-Typ: $2)',
'file-info-size' => '($1 × $2 Pixel, Dateigrösse: $3, MIME-Typ: $4)',

# EXIF tags
'exif-resolutionunit'              => 'Masseinheit der Auflösung',
'exif-jpeginterchangeformatlength' => 'Grösse der JPEG-Daten in Bytes',
'exif-referenceblackwhite'         => 'Schwarz/Weiss-Referenzpunkte',
'exif-maxaperturevalue'            => 'Grösste Blende',
'exif-whitebalance'                => 'Weissabgleich',
'exif-gpsdop'                      => 'Masspräzision',

'exif-lightsource-13' => 'Tagesweiss fluoreszierend (N 4600–5400 K)',
'exif-lightsource-14' => 'Kaltweiss fluoreszierend (W 3900–4500 K)',
'exif-lightsource-15' => 'Weiss fluoreszierend (WW 3200–3700 K)',

# Special:FileDuplicateSearch
'fileduplicatesearch-info' => '$1 × $2 Pixel<br />Dateigrösse: $3<br />MIME-Typ: $4',

);
