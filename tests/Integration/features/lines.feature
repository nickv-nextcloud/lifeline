Feature: admin-notification
  Background:
    Given user "test1" exists
    Given user "test2" exists

  Scenario: Handle line
    Given user "test1" creates line
      | name | Line1 |
    Then user "test1" has lines
      | name |
      | Line1 |
    Then user "test2" has lines
      | name |
    Given user "test1" updates line "Line1"
      | name | Line One |
    Then user "test1" has lines
      | name |
      | Line One |
    Given user "test1" deletes line "Line1"
    Then user "test1" has lines
      | name |
