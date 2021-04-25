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

namespace OCA\LifeLineIntegrationTesting\Controller;

use OCP\AppFramework\OCSController;
use OCP\AppFramework\Http\DataResponse;
use OCP\IDBConnection;
use OCP\IRequest;

class EndpointController extends OCSController {

	/** @var IDBConnection */
	private $connection;

	public function __construct(string $appName, IRequest $request, IDBConnection $connection) {
		parent::__construct($appName, $request);
		$this->connection = $connection;
	}


	/**
	 * @NoCSRFRequired
	 *
	 * @return DataResponse
	 */
	public function deleteAll(): DataResponse {
		$query = $this->connection->getQueryBuilder();
		$query->delete('lifeline_lines');
		$query->executeUpdate();

		$query->delete('lifeline_editors');
		$query->executeUpdate();

		$query->delete('lifeline_points');
		$query->executeUpdate();

		return new DataResponse();
	}
}
