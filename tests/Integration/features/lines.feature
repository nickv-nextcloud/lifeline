Feature: Lines
  Background:
    Given user "test1" exists
    Given user "test2" exists

  Scenario: Handle line
    Given user "test1" creates line with 200
      | name | Line1 |
    Then user "test1" has lines
      | name |
      | Line1 |
    Then user "test2" has lines
      | name |
    Given user "test1" updates line "Line1" with 200
      | name | Line One |
    Given user "test2" updates line "Line1" with 404
      | name | Line 1 |
    Then user "test1" has lines
      | name |
      | Line One |
    Given user "test2" deletes line "Line1" with 404
    Then user "test1" has lines
      | name |
      | Line One |
    Given user "test1" deletes line "Line1" with 200
    Then user "test1" has lines
      | name |
