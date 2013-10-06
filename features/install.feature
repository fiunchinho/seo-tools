# features/install.feature
Feature: Install
  In order to start using the application
  As a user that has never used it
  I need to be able to install it

Scenario: Opening application for the first time
  Given I didn't install the application before
  When I provide a valid email like "valid@email.com"
  And I provide a valid password like "correct_password"
  And I install the application
  Then application should be installed
  And user should be registered with email 'valid@email.com' and password 'correct_password'