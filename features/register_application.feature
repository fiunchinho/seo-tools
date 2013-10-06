# features/register_application.feature
Feature: Register application
  In order to register an application
  As a logged in user
  I can register application

Scenario: Creating a new application
  Given 'Bob' is logged in
  When I register the application "My App"
  Then application "My App" should be created