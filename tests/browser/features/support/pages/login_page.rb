require 'page-object'

class LoginPage
  include PageObject

  element(:error_message, css: 'div#userloginForm div.error')
  element(:password_error, css: 'input#wpPassword1:required:invalid')
  element(:username_error, css: 'input#wpName1:required:invalid')
end
