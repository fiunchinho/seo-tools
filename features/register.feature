# features/register.feature
Feature: register
  In order to use the website
  As an anonymous user
  I need to be able to create a registered user

Scenario: Registering with valid email in the application
  Given I am a anonymous user
  And I provide a valid email like "not_existing@email.com"
  And I provide a valid password like "valid_password"
  And I provide a valid name like "bob"
  And I accept the terms
  When I try to register
  Then user should be registered with email 'not_existing@email.com' and password 'valid_password'

Scenario: Registering without all mandatory fields
  Given I am a anonymous user
  And I don't provide an email
  And I provide a valid password like "valid_password"
  And I provide a valid name like "bob"
  And I accept the terms
  When I try to register
  Then I should get an "InvalidArgumentException" error

Scenario: Registering an email that already exists
  Given I am a anonymous user
  And I provide a valid email that is already registered like "existing@email.com"
  And I provide a valid password like "valid_password"
  And I provide a valid name like "bob"
  And I accept the terms
  When I try to register
  Then I should get an "DomainFinder\Exception\AlreadyRegisteredException" error