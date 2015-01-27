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
Given(/^I am at file that does not exist$/) do
  visit(FileDoesNotExistPage, using_params: { page_name: @random_string })
end

Then(/^page should show that no such file exists$/) do
  expect(on(FileDoesNotExistPage).file_does_not_exist_message_element).to be_visible
end
