# features/domains_list.feature
Feature: List all domains for the current user
  In order to view all my registered domains
  As a logged in user
  I can list my domains

Scenario: Listing my domains
  Given 'Bob' is logged in
  When I list domains for 'Bob'
  Then I should get all domains for 'Bob'