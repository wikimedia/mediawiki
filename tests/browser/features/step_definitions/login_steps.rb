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
Given(/^I am at Log in page$/) do
  visit LoginPage
end

When(/^I log in with incorrect password$/) do
  on(LoginPage).login_with(ENV["MEDIAWIKI_USER"], "incorrect password", false)
end

When(/^I log in with incorrect username$/) do
  on(LoginPage).login_with("incorrect username", ENV["MEDIAWIKI_PASSWORD"], false)
end

When(/^I log in without entering credentials$/) do
  on(LoginPage).login_with("", "", false)
end

When(/^I log in without entering password$/) do
  on(LoginPage).login_with(ENV["MEDIAWIKI_USER"], "", false)
end

Then(/^error box should be visible$/) do
  on(LoginErrorPage).error_box_element.should be_visible
end

Then(/^error box should not be visible$/) do
  on(LoginErrorPage).error_box_element.should_not be_visible
end

Then(/^feedback should be (.+)$/) do |feedback|
  on(LoginPage) do |page|
    page.feedback_element.when_present.click
    page.feedback.should match Regexp.escape(feedback)
  end
end

Then(/^Log in element should be there$/) do
  on(LoginPage).login_element.should exist
end

Then(/^main page should open$/) do
  @browser.url.should == on(MainPage).class.url
end

Then(/^Password element should be there$/) do
  on(LoginPage).password_element.should exist
end

Then(/^there should be a link to (.+)$/) do |text|
  on(LoginPage).username_displayed_element.when_present.text.should == text
end

Then(/^Username element should be there$/) do
  on(LoginPage).username_element.should exist
end
