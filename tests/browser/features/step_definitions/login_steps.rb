Given(/^I am at Log in page$/) do
  visit LoginPage
end

When(/^I log in$/) do
  on(LoginPage).login_with(user, password, false)
end

When(/^I log in with incorrect password$/) do
  on(LoginPage).login_with(user, 'incorrect password', false)
end

When(/^I log in with incorrect username$/) do
  on(LoginPage).login_with('incorrect username', password, false)
end

When(/^I log in without entering credentials$/) do
  on(LoginPage).login_with('', '', false)
end

When(/^I log in without entering password$/) do
  on(LoginPage).login_with(user, '', false)
end

Then(/^error box should be visible$/) do
  expect(on(LoginPage).error_message_element).to exist
end

Then(/^error box should not be visible$/) do
  expect(on(LoginPage).error_message_element).not_to exist
end

Then(/^error message should be displayed for username$/) do
  expect(on(LoginPage).username_error_element).to exist
end

Then(/^error message should be displayed for password$/) do
  expect(on(LoginPage).password_error_element).to exist
end

Then(/^feedback should be (.+)$/) do |feedback|
  on(LoginPage) do |page|
    page.feedback_element.click
    expect(page.feedback).to match Regexp.escape(feedback)
  end
end

Then(/^Log in element should be there$/) do
  expect(on(LoginPage).login_element).to exist
end

Then(/^main page should open$/) do
  expect(@browser.url).to eq on(MainPage).class.url
end

Then(/^Password element should be there$/) do
  expect(on(LoginPage).password_element).to exist
end

Then(/^there should be a link to (.+)$/) do |text|
  expect(on(LoginPage).username_displayed_element.text).to eq text
end

Then(/^Username element should be there$/) do
  expect(on(LoginPage).username_element).to exist
end
