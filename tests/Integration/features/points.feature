Feature: Points
  Background:
    Given user "test1" exists
    Given user "test2" exists

  Scenario: Handle points
    Given user "test1" creates line with 200
      | name | Line1 |
    Then user "test1" sees the following points on line "Line1" with 200
      | title |
    Then user "test2" sees the following points on line "Line1" with 404
      | title |
    When user "test2" adds point to line "Line1" with 404
      | icon | cake |
      | title | Birth |
      | description | Born with weight X and height Y at Z:ZZpm |
      | highlight | 1 |
      | datetime | 2021-03-10 13:48:38 |
    When user "test1" adds point to line "Line1" with 200
      | icon | cake |
      | title | Birth |
      | description | Born with weight X and height Y at Z:ZZpm |
      | highlight | 1 |
      | datetime | 2021-03-10 13:48:38 |
    Then user "test1" sees the following points on line "Line1" with 200
      | icon | title | highlight | datetime | description |
      | cake | Birth | 1         | 2021-03-10 13:48:38 |Born with weight X and height Y at Z:ZZpm |
    Then user "test2" sees the following points on line "Line1" with 404
      | title |
    When user "test1" adds "test2" as editor to line "Line1" with 200
    Then user "test2" sees the following points on line "Line1" with 200
      | icon | title | highlight | datetime | description |
      | cake | Birth | 1         | 2021-03-10 13:48:38 |Born with weight X and height Y at Z:ZZpm |
    When user "test2" adds point to line "Line1" with 200
      | icon | tooth |
      | title | First tooth |
      | highlight | 0 |
      | datetime | 2021-10-03 13:48:38 |
    Then user "test1" sees the following points on line "Line1" with 200
      | icon | title | highlight | datetime | description |
      | tooth | First tooth  | 0 | 2021-10-03 13:48:38 |             |
      | cake | Birth | 1         | 2021-03-10 13:48:38 |Born with weight X and height Y at Z:ZZpm |
    And user "test1" removes "test2" as editor from line "Line1" with 200
    When user "test2" removes point "First tooth" from line "Line1" with 404
    Given user "test1" creates line with 200
      | name | Line2 |
    When user "test1" removes point "First tooth" from line "Line2" with 404
    When user "test1" removes point "First tooth" from line "Line1" with 200
      | icon | title | highlight | datetime | description |
      | cake | Birth | 1         | 2021-03-10 13:48:38 | Born with weight X and height Y at Z:ZZpm |
