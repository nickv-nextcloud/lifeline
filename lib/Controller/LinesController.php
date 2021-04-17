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

namespace OCA\LifeLine\Controller;

use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCS\OCSBadRequestException;
use OCP\AppFramework\OCS\OCSException;
use OCP\AppFramework\OCS\OCSForbiddenException;
use OCP\AppFramework\OCSController;
use OCP\IRequest;

class LinesController extends OCSController {

	public function __construct(string $appName,
								IRequest $request) {
		parent::__construct($appName, $request);
	}

	/**
	 * @NoAdminRequired
	 * @return DataResponse
	 * @throws OCSForbiddenException
	 */
	public function index(): DataResponse {
		return new DataResponse([]);
	}

	/**
	 * @NoAdminRequired
	 * @param int $id
	 * @return DataResponse
	 * @throws OCSForbiddenException
	 */
	public function show(int $id): DataResponse {
		return new DataResponse([]);
	}

	/**
	 * @NoAdminRequired
	 * @return DataResponse
	 * @throws OCSBadRequestException
	 * @throws OCSForbiddenException
	 * @throws OCSException
	 */
	public function create(
	): DataResponse {
		try {
			return new DataResponse([]);
		} catch (\UnexpectedValueException $e) {
			throw new OCSBadRequestException($e->getMessage(), $e);
		} catch (\DomainException $e) {
			throw new OCSForbiddenException($e->getMessage(), $e);
		} catch (\Exception $e) {
			throw new OCSException('An internal error occurred', $e->getCode(), $e);
		}
	}

	/**
	 * @NoAdminRequired
	 * @param int $id
	 * @return DataResponse
	 * @throws OCSBadRequestException
	 * @throws OCSForbiddenException
	 * @throws OCSException
	 */
	public function update(
		int $id
	): DataResponse {
		try {
			return new DataResponse([]);
		} catch (\UnexpectedValueException $e) {
			throw new OCSBadRequestException($e->getMessage(), $e);
		} catch (\DomainException $e) {
			throw new OCSForbiddenException($e->getMessage(), $e);
		} catch (\Exception $e) {
			throw new OCSException('An internal error occurred', $e->getCode(), $e);
		}
	}

	/**
	 * @NoAdminRequired
	 * @param int $id
	 * @return DataResponse
	 * @throws OCSBadRequestException
	 * @throws OCSForbiddenException
	 * @throws OCSException
	 */
	public function destroy(int $id): DataResponse {
		try {
			return new DataResponse([]);
		} catch (\UnexpectedValueException $e) {
			throw new OCSBadRequestException($e->getMessage(), $e);
		} catch (\DomainException $e) {
			throw new OCSForbiddenException($e->getMessage(), $e);
		} catch (\Exception $e) {
			throw new OCSException('An internal error occurred', $e->getCode(), $e);
		}
	}
}
