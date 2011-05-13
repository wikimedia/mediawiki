<?php

/**
 * Selenium server manager
 *
 * @file
 * @ingroup Testing
 * Copyright (C) 2010 Nadeesha Weerasinghe <nadeesha@calcey.com>
 * http://www.calcey.com/ 
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License along
 * with this program; if not, write to the Free Software Foundation, Inc.,
 * 59 Temple Place - Suite 330, Boston, MA 02111-1307, USA.
 * http://www.gnu.org/copyleft/gpl.html
 *
 * @addtogroup Testing
 *
 */


class AddContentToNewPageTestCase extends SeleniumTestCase {

  
    // Add bold text and verify output
    public function testAddBoldText() {

        $this->getExistingPage();
        $this->clickEditLink();
        $this->loadWikiEditor();
        $this->clearWikiEditor();
        $this->click( "//*[@id='mw-editbutton-bold']" );
        $this->clickShowPreviewBtn();
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        // Verify bold text displayed on mediawiki preview
        $this->assertTrue($this->isElementPresent( "//div[@id='wikiPreview']/p/b" ));
        $this->assertTrue($this->isTextPresent( "Bold text" ));
    }

    // Add italic text and verify output
    public function testAddItalicText() {

        $this->getExistingPage();
        $this->clickEditLink();
        $this->loadWikiEditor();
        $this->clearWikiEditor();
        $this->click( "//*[@id='mw-editbutton-italic']" );
        $this->clickShowPreviewBtn();
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        // Verify italic text displayed on mediawiki preview
        $this->assertTrue($this->isElementPresent("//div[@id='wikiPreview']/p/i"));
        $this->assertTrue($this->isTextPresent( "Italic text" ));
    }

    // Add internal link for a new page and verify output in the preview
    public function testAddInternalLinkNewPage() {
        $this->getExistingPage();
        $this->clickEditLink();
        $this->loadWikiEditor();
        $this->clearWikiEditor();
        $this->click( "//*[@id='mw-editbutton-link']" );
        $this->clickShowPreviewBtn();
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        // Verify internal link displayed on mediawiki preview
        $source = $this->getText( "//*[@id='wikiPreview']/p/a" );
        $correct = strstr( $source, "Link title" );
        $this->assertEquals( $correct, true );

        $this->click( SeleniumTestConstants::LINK_START."Link title" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        // Verify internal link open as a new page - editing mode
        $source = $this->getText( "firstHeading" );
        $correct = strstr( $source, "Editing Link title" );
        $this->assertEquals( $correct, true );
    }

    // Add external link and verify output in the preview
    public function testAddExternalLink() {
        $this->getExistingPage();
        $this->clickEditLink();
        $this->loadWikiEditor();
        $this->clearWikiEditor();
        $this->click( "//*[@id='mw-editbutton-extlink']" );
        $this->type( SeleniumTestConstants::TEXT_EDITOR, "[http://www.google.com Google]" );
        $this->clickShowPreviewBtn();
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        // Verify external links displayed on mediawiki preview
        $source = $this->getText( "//*[@id='wikiPreview']/p/a" );
        $correct = strstr( $source, "Google" );
        $this->assertEquals( $correct, true );

        $this->click( SeleniumTestConstants::LINK_START."Google" );
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        // Verify external link opens
        $source = $this->getTitle();
        $correct = strstr( $source, "Google" );
        $this->assertEquals( $correct, true);
    }

    // Add level 2 headline and verify output in the preview
    public function testAddLevel2HeadLine() {
        $blnElementPresent = false;
        $blnTextPresent = false;
        $this->getExistingPage();
        $this->clickEditLink();
        $this->loadWikiEditor();
        $this->clearWikiEditor();
        $this->click( "mw-editbutton-headline" );
        $this->clickShowPreviewBtn();
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );
        $this->assertTrue($this->isElementPresent( "//div[@id='wikiPreview']/h2" ));

        // Verify level 2 headline displayed on mediawiki preview
        $source = $this->getText( "//*[@id='Headline_text']" );
        $correct = strstr( $source, "Headline text" );
        $this->assertEquals( $correct, true );
    }

    // Add text with ignore wiki format and verify output the preview
    public function testAddNoWikiFormat() {
        $this->getExistingPage();
        $this->clickEditLink();
        $this->loadWikiEditor();
        $this->clearWikiEditor();
        $this->click( "//*[@id='mw-editbutton-nowiki']" );
        $this->clickShowPreviewBtn();
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        // Verify ignore wiki format text displayed on mediawiki preview
        $source = $this->getText( "//div[@id='wikiPreview']/p" );
        $correct = strstr( $source, "Insert non-formatted text here" );
        $this->assertEquals( $correct, true );
    }

    // Add signature and verify output in the preview
    public function testAddUserSignature() {
        $this->getExistingPage();
        $this->clickEditLink();
        $this->loadWikiEditor();
        $this->clearWikiEditor();
        $this->click( "mw-editbutton-signature" );
        $this->clickShowPreviewBtn();
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        // Verify signature displayed on mediawiki preview
        $source = $this->getText( "//*[@id='wikiPreview']/p/a" );
        $username = $this->getText( "//*[@id='pt-userpage']/a" );
        $correct = strstr( $source, $username );
        $this->assertEquals( $correct, true );
    }

    // Add horizontal line and verify output in the preview
    public function testHorizontalLine() {
        $this->getExistingPage();
        $this->clickEditLink();
        $this->loadWikiEditor();
        $this->clearWikiEditor();
        $this->click( "mw-editbutton-hr" );

        $this->clickShowPreviewBtn();
        $this->waitForPageToLoad( SeleniumTestConstants::WIKI_TEST_WAIT_TIME );

        // Verify horizontal line displayed on mediawiki preview
        $this->assertTrue( $this->isElementPresent( "//div[@id='wikiPreview']/hr" ));
        $this->deletePage( "new" );
    }
}
