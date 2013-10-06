# features/register_domain.feature
Feature: Add domain to your application
  In order to register a domain for my application
  As a logged in user
  I can register domains

Scenario: Opening application for the first time
  Given 'Bob' is logged in
  When I register the domain "mydomain.com"
  Then the domain "mydomain.com" should be registered