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
Given(/^I go to Create account page at (.+)$/) do |path|
  visit(CreateAccountPage, using_params: { page_title: path })
end

Then(/^form has Create account button$/) do
  expect(on(CreateAccountPage).create_account_element).to exist
end

When(/^I submit the form$/) do
  on(CreateAccountPage).create_account
end

Then(/^an error message is displayed$/) do
  expect(on(CreateAccountPage).error_message_element.class_name).to eq 'errorbox'
end
