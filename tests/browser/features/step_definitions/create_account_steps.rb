Given(/^I go to Create account page at (.+)$/) do |path|
  visit(CreateAccountPage, using_params: { page_title: path })
end

Given(/^I have created account via the API$/) do
  require 'securerandom'
  @user = SecureRandom.hex(20).capitalize
  @password = SecureRandom.hex(20)
  api.create_account @user, @password
end

Then(/^form has Create account button$/) do
  expect(on(CreateAccountPage).create_account_element).to exist
end

When(/^I log in as the new user$/) do
  visit(LoginPage).login_with(@user, @password)
end

When(/^I submit the form$/) do
  on(CreateAccountPage).create_account
end

Then(/^an error message is displayed$/) do
  expect(on(CreateAccountPage).error_message_element).to exist
end
