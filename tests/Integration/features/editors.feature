Feature: Editors
  Background:
    Given user "test1" exists
    Given user "test2" exists
    Given user "test3" exists

  Scenario: Handle editor
    Given user "test1" creates line with 200
      | name | Line1 |
    Then user "test1" sees line "Line1" has editors with 200
      | user_id |
      | test1 |
    And user "test2" sees line "Line1" has editors with 404
      | user_id |
    Then user "test2" has lines
      | name |
    When user "test2" adds "test3" as editor to line "Line1" with 404
    When user "test1" adds "test2" as editor to line "Line1" with 200
    Then user "test2" has lines
      | name |
      | Line1 |
    Then user "test1" sees line "Line1" has editors with 200
      | user_id |
      | test1 |
      | test2 |
    And user "test2" sees line "Line1" has editors with 200
      | user_id |
      | test1 |
      | test2 |
    When user "test2" removes "test3" as editor from line "Line1" with 200
    When user "test2" removes "test2" as editor from line "Line1" with 400
    When user "test1" removes "test2" as editor from line "Line1" with 200
    Then user "test2" has lines
      | name |
    Then user "test1" sees line "Line1" has editors with 200
      | user_id |
      | test1 |
    And user "test2" sees line "Line1" has editors with 404
      | user_id |
