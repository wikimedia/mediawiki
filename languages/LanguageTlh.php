<?php

# Hooray for Klingon, the most controversial language addition to date

require_once( "LanguageUtf8.php" );

if ( $wgMetaNamespace == "Wikipedia" ) {
        $wgMetaNamespace = "wIqIpe'DIya";
}

/* private */ $wgNamespaceNamesTlh = array(
        NS_MEDIA            => "Doch",
        NS_SPECIAL          => "le'",
        NS_MAIN             => "",
        NS_TALK             => "ja'chuq",
        NS_USER             => "lo'wI'",
        NS_USER_TALK        => "lo'wI'_ja'chuq",
        NS_WIKIPEDIA        => $wgMetaNamespace,
        NS_WIKIPEDIA_TALK   => $wgMetaNamespace . "_ja'chuq",
        NS_IMAGE            => "nagh_beQ",
        NS_IMAGE_TALK       => "nagh_beQ_ja'chuq",
        NS_MEDIAWIKI        => "MediaWiki",
        NS_MEDIAWIKI_TALK   => "MediaWiki_ja'chuq",
        NS_TEMPLATE         => "chen'ay'",
        NS_TEMPLATE_TALK    => "chen'ay'_ja'chuq",
        NS_HELP             => "QaH",
        NS_HELP_TALK        => "QaH_ja'chuq",
        NS_CATEGORY         => "Segh",
        NS_CATEGORY_TALK    => "Segh_ja'chuq"
) + $wgNamespaceNamesEn;

class LanguageTlh extends LanguageUtf8
{
        function getNamespaces() {
                global $wgNamespaceNamesTlh;
                return $wgNamespaceNamesTlh;
        }

        function getNsText( $index ) {
                global $wgNamespaceNamesTlh;
                return $wgNamespaceNamesTlh[$index];
        }

        function getNsIndex( $text ) {
                global $wgNamespaceNamesTlh;

                foreach ( $wgNamespaceNamesTlh as $i => $n ) {
                        if ( 0 == strcasecmp( $n, $text ) ) { return $i; }
                }
                return false;
        }
}

?>


