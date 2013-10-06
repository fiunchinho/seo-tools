# features/login.feature
Feature: Login
  In order to login
  As an anonymous user
  I need to be able to login

Scenario: Login with valid credentials
  Given I am a registered user
  And I provide a correct password like "correct_password"
  When I try to login
  Then I should be logged in in the application

Scenario: Login with incorrect password
  Given I am a registered user
  And I provide a incorrect password like "incorrect_password"
  When I try to login
  Then I should get an "DomainFinder\Exception\IncorrectPasswordException" error

Scenario: Login with an user that does not exists
  Given I am an unregistered user
  And I provide a valid email like "not_existing@email.com"
  And I provide a password like "password"
  When I try to login
  Then I should get an "DomainFinder\Exception\UserNotFoundException" error

Scenario: Logout for a logged in user
  Given I am an logged in user
  When I try to logout
  Then I should be logged out of the application