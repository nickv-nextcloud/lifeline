<?php

declare(strict_types=1);
/**
 * @copyright Copyright (c) 2021, Joas Schilling <coding@schilljs.com>
 *
 * @author Joas Schilling <coding@schilljs.com>
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

require __DIR__ . '/../../vendor/autoload.php';

use Behat\Behat\Context\Context;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Gherkin\Node\TableNode;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\ClientException;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\ResponseInterface;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context, SnippetAcceptingContext {

	/** @var array[] */
	protected static $lineToId = [];
	/** @var array[] */
	protected static $idToLine = [];
	/** @var array[] */
	protected static $pointToId = [];
	/** @var array[] */
	protected static $idToPoint = [];

	/** @var int */
	protected $deletedNotification;

	/** @var string */
	protected $currentUser;

	/** @var ResponseInterface */
	private $response = null;

	/** @var CookieJar */
	private $cookieJar;

	/** @var string */
	protected $baseUrl;

	/** @var string */
	protected $lastEtag;

	/**
	 * FeatureContext constructor.
	 */
	public function __construct() {
		$this->cookieJar = new CookieJar();
		$this->baseUrl = getenv('TEST_SERVER_URL');
	}

	/**
	 * @Given /^user "([^"]*)" creates line with (\d+)$/
	 *
	 * @param string $user
	 * @param int $status
	 * @param TableNode|null $formData
	 */
	public function createsLine(string $user, int $status, TableNode $formData): void {
		$this->setCurrentUser($user);
		$this->sendingToWith('POST', '/apps/lifeline/api/v1/lines', $formData);
		$this->assertStatusCode($this->response, $status);

		$line = $this->getArrayOfDataResponded($this->response);

		self::$lineToId[$line['name']] = $line['id'];
		self::$idToLine[$line['id']] = $line['name'];
	}

	/**
	 * @Given /^user "([^"]*)" updates line "([^"]*)" with (\d+)$/
	 *
	 * @param string $user
	 * @param string $line
	 * @param int $status
	 */
	public function updateLine(string $user, string $line, int $status, TableNode $formData): void {
		$this->setCurrentUser($user);
		$this->sendingToWith('PUT', '/apps/lifeline/api/v1/lines/' . self::$lineToId[$line], $formData);
		$this->assertStatusCode($this->response, $status);
	}

	/**
	 * @Given /^user "([^"]*)" deletes line "([^"]*)" with (\d+)$/
	 *
	 * @param string $user
	 * @param string $line
	 * @param int $status
	 */
	public function deleteLine(string $user, string $line, int $status): void {
		$this->setCurrentUser($user);
		$this->sendingToWith('DELETE', '/apps/lifeline/api/v1/lines/' . self::$lineToId[$line]);
		$this->assertStatusCode($this->response, $status);
	}

	/**
	 * @Then /^user "([^"]*)" has lines$/
	 *
	 * @param string $user
	 * @param TableNode|null $formData
	 */
	public function hasLines(string $user, TableNode $formData) {
		$this->setCurrentUser($user);
		$this->sendingToWith('GET', '/apps/lifeline/api/v1/lines', $formData);
		$this->assertStatusCode($this->response, 200);

		$lines = $this->getArrayOfDataResponded($this->response);
		$this->assertLines($formData, $lines);
	}

	/**
	 * @Given /^user "([^"]*)" adds "([^"]*)" as editor to line "([^"]*)" with (\d+)$/
	 *
	 * @param string $user
	 * @param string $editor
	 * @param string $line
	 * @param int $status
	 */
	public function addsEditor(string $user, string $editor, string $line, int $status): void {
		$formData = new TableNode([
			['id', $editor],
		]);

		$this->setCurrentUser($user);
		$this->sendingToWith('POST', '/apps/lifeline/api/v1/lines/' . self::$lineToId[$line] . '/editors', $formData);
		$this->assertStatusCode($this->response, $status);
	}

	/**
	 * @Given /^user "([^"]*)" removes "([^"]*)" as editor from line "([^"]*)" with (\d+)$/
	 *
	 * @param string $user
	 * @param string $line
	 * @param int $status
	 */
	public function removesEditor(string $user, string $editor, string $line, int $status): void {
		$this->setCurrentUser($user);
		$this->sendingToWith('DELETE', '/apps/lifeline/api/v1/lines/' . self::$lineToId[$line] . '/editors/' . $editor);
		$this->assertStatusCode($this->response, $status);
	}

	/**
	 * @Then /^user "([^"]*)" sees line "([^"]*)" has editors with (\d+)$/
	 *
	 * @param string $user
	 * @param string $line
	 * @param int $status
	 * @param TableNode|null $formData
	 */
	public function seesEditors(string $user, string $line, int $status, TableNode $formData) {
		$this->setCurrentUser($user);
		$this->sendingToWith('GET', '/apps/lifeline/api/v1/lines/' . self::$lineToId[$line] . '/editors', $formData);
		$this->assertStatusCode($this->response, $status);

		if ($status === 200) {
			$editors = $this->getArrayOfDataResponded($this->response);
			$this->assertEditors($formData, $editors);
		} else {
			Assert::assertEmpty($this->getArrayOfDataResponded($this->response));
		}
	}

	/**
	 * @Given /^user "([^"]*)" adds point to line "([^"]*)" with (\d+)$/
	 *
	 * @param string $user
	 * @param string $line
	 * @param int $status
	 * @param TableNode|null $formData
	 */
	public function addsPoint(string $user, string $line, int $status, TableNode $formData): void {
		$this->setCurrentUser($user);
		$this->sendingToWith('POST', '/apps/lifeline/api/v1/lines/' . self::$lineToId[$line] . '/points', $formData);
		$this->assertStatusCode($this->response, $status);

		if ($status === 200) {
			$point = $this->getArrayOfDataResponded($this->response);
			self::$pointToId[$point['title']] = $point['id'];
			self::$idToPoint[$point['id']] = $point['title'];
		}
	}

	/**
	 * @Given /^user "([^"]*)" removes point "([^"]*)" from line "([^"]*)" with (\d+)$/
	 *
	 * @param string $user
	 * @param string $point
	 * @param string $line
	 * @param int $status
	 */
	public function removedPoint(string $user, string $point, string $line, int $status): void {
		$this->setCurrentUser($user);
		$this->sendingToWith('DELETE', '/apps/lifeline/api/v1/lines/' . self::$lineToId[$line] . '/points/' . self::$pointToId[$point]);
		$this->assertStatusCode($this->response, $status);
	}

	/**
	 * @Then /^user "([^"]*)" sees the following points on line "([^"]*)" with (\d+)$/
	 *
	 * @param string $user
	 * @param string $line
	 * @param int $status
	 * @param TableNode|null $formData
	 */
	public function seesPoints(string $user, string $line, int $status, TableNode $formData) {
		$this->setCurrentUser($user);
		$this->sendingToWith('GET', '/apps/lifeline/api/v1/lines/' . self::$lineToId[$line] . '/points', $formData);
		$this->assertStatusCode($this->response, $status);

		if ($status === 200) {
			$editors = $this->getArrayOfDataResponded($this->response);
			$this->assertPoints($formData, $editors);
		} else {
			Assert::assertEmpty($this->getArrayOfDataResponded($this->response));
		}
	}

	protected function assertLines(TableNode $formData, array $actual) {
		Assert::assertCount(count($formData->getHash()), $actual, 'Lines count does not match');
		Assert::assertEquals($formData->getHash(), array_map(function ($line, $expectedLine) {
			if (!isset(self::$lineToId[$line['name']])) {
				self::$lineToId[$line['name']] = $line['id'];
			}
			if (!isset(self::$idToLine[$line['id']])) {
				self::$idToLine[$line['id']] = $line['name'];
			}

			$data = [];
			if (isset($expectedLine['name'])) {
				$data['name'] = $line['name'];
			}

			return $data;
		}, $actual, $formData->getHash()));
	}

	protected function assertEditors(TableNode $formData, array $actual) {
		Assert::assertCount(count($formData->getHash()), $actual, 'Editors count does not match');
		Assert::assertEquals($formData->getHash(), array_map(function ($editor, $expectedEditor) {
			$data = [];
			if (isset($expectedEditor['user_id'])) {
				$data['user_id'] = $editor['user_id'];
			}

			return $data;
		}, $actual, $formData->getHash()));
	}

	protected function assertPoints(TableNode $formData, array $actual) {
		Assert::assertCount(count($formData->getHash()), $actual, 'Points count does not match');
		Assert::assertEquals($formData->getHash(), array_map(function ($point, $expectedPoint) {
			$data = [];
			if (isset($expectedPoint['icon'])) {
				$data['icon'] = $point['icon'];
			}
			if (isset($expectedPoint['title'])) {
				$data['title'] = $point['title'];
			}
			if (isset($expectedPoint['description'])) {
				$data['description'] = $point['description'];
			}
			if (isset($expectedPoint['highlight'])) {
				$data['highlight'] = (int) $point['highlight'];
			}
			if (isset($expectedPoint['datetime'])) {
				$data['datetime'] = $point['datetime'];
			}
			if (isset($expectedPoint['fileId'])) {
				$data['fileId'] = $point['fileId'];
			}

			return $data;
		}, $actual, $formData->getHash()));
	}

	/**
	 * Parses the JSON answer to get the array of users returned.
	 * @param ResponseInterface $response
	 * @return array
	 */
	protected function getArrayOfDataResponded(ResponseInterface $response): array {
		$jsonBody = json_decode($response->getBody()->getContents(), true);
		return $jsonBody['ocs']['data'];
	}

	/**
	 * @Then /^status code is ([0-9]*)$/
	 *
	 * @param int $statusCode
	 */
	public function isStatusCode(int $statusCode) {
		$this->assertStatusCode($this->response, $statusCode);
	}

	/**
	 * @BeforeScenario
	 * @AfterScenario
	 */
	public function clearEverything(): void {
		$this->setCurrentUser('admin');
		$this->sendingTo('DELETE', '/apps/lifelineintegrationtesting');
		$this->isStatusCode(200);
	}

	/*
	 * User management
	 */

	/**
	 * @Given /^as user "([^"]*)"$/
	 * @param string $user
	 */
	public function setCurrentUser(string $user) {
		$this->currentUser = $user;
	}

	/**
	 * @Given /^user "([^"]*)" exists$/
	 * @param string $user
	 */
	public function assureUserExists(string $user) {
		try {
			$this->userExists($user);
		} catch (ClientException $ex) {
			$this->createUser($user);
		}
		$response = $this->userExists($user);
		$this->assertStatusCode($response, 200);
	}

	private function userExists(string $user): ResponseInterface {
		$client = new Client();
		$options = [
			'auth' => ['admin', 'admin'],
			'headers' => [
				'OCS-APIREQUEST' => 'true',
			],
		];
		return $client->get($this->baseUrl . 'ocs/v2.php/cloud/users/' . $user, $options);
	}

	private function createUser(string $user) {
		$previous_user = $this->currentUser;
		$this->currentUser = 'admin';

		$userProvisioningUrl = $this->baseUrl . 'ocs/v2.php/cloud/users';
		$client = new Client();
		$options = [
			'auth' => ['admin', 'admin'],
			'form_params' => [
				'userid' => $user,
				'password' => '123456'
			],
			'headers' => [
				'OCS-APIREQUEST' => 'true',
			],
		];
		$client->post($userProvisioningUrl, $options);

		//Quick hack to login once with the current user
		$options2 = [
			'auth' => [$user, '123456'],
			'headers' => [
				'OCS-APIREQUEST' => 'true',
			],
		];
		$client->get($userProvisioningUrl . '/' . $user, $options2);

		$this->currentUser = $previous_user;
	}

	/*
	 * Requests
	 */

	/**
	 * @When /^sending "([^"]*)" to "([^"]*)"$/
	 * @param string $verb
	 * @param string $url
	 */
	public function sendingTo(string $verb, string $url) {
		$this->sendingToWith($verb, $url, null);
	}

	/**
	 * @When /^sending "([^"]*)" to "([^"]*)" with$/
	 * @param string $verb
	 * @param string $url
	 * @param TableNode $body
	 * @param array $headers
	 */
	public function sendingToWith(string $verb, string $url, TableNode $body = null, array $headers = []) {
		$fullUrl = $this->baseUrl . 'ocs/v2.php' . $url . '?format=json';
		$client = new Client();
		$options = [];
		if ($this->currentUser === 'admin') {
			$options['auth'] = ['admin', 'admin'];
		} else {
			$options['auth'] = [$this->currentUser, '123456'];
		}
		if ($body instanceof TableNode) {
			$fd = $body->getRowsHash();
			$options['form_params'] = $fd;
		} elseif (is_array($body)) {
			$options['form_params'] = $body;
		}

		$options['headers'] = array_merge($headers, [
			'OCS-APIREQUEST' => 'true',
		]);

		try {
			$this->response = $client->request($verb, $fullUrl, $options);
		} catch (ClientException $ex) {
			$this->response = $ex->getResponse();
		}
	}

	/**
	 * @param ResponseInterface $response
	 * @param int $statusCode
	 */
	protected function assertStatusCode(ResponseInterface $response, int $statusCode) {
		Assert::assertEquals($statusCode, $response->getStatusCode());
	}
}
