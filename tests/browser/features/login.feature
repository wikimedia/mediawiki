#
# This file is subject to the license terms in the LICENSE file found in the
# qa-browsertests top-level directory and at
# https://git.wikimedia.org/blob/qa%2Fbrowsertests/HEAD/LICENSE. No part of
# qa-browsertests, including this file, may be copied, modified, propagated, or
# distributed except according to the terms contained in the LICENSE file.
#
# Copyright 2012-2014 by the Mediawiki developers. See the CREDITS file in the
# qa-browsertests top-level directory and at
# https://git.wikimedia.org/blob/qa%2Fbrowsertests/HEAD/CREDITS
#
@chrome @clean @firefox @internet_explorer_6 @internet_explorer_7 @internet_explorer_8 @internet_explorer_9 @internet_explorer_10 @phantomjs
Feature: Log in

  Background:
    Given I am at Log in page

  Scenario: Go to Log in page
    Then Username element should be there
      And Password element should be there
      And Log in element should be there

  Scenario: Log in without entering credentials
    When I log in without entering credentials
    Then error box should be visible

  Scenario: Log in without entering password
    When I log in without entering password
    Then error box should be visible

  Scenario: Log in with incorrect username
    When I log in with incorrect username
    Then error box should be visible

  Scenario: Log in with incorrect password
    When I log in with incorrect password
    Then error box should be visible

  @login
  Scenario: Log in with valid credentials
    When I am logged in
    Then error box should not be visible
