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

use OCA\LifeLine\Model\EditorMapper;
use OCA\LifeLine\Model\LineMapper;
use OCA\LifeLine\Model\Point;
use OCA\LifeLine\Model\PointMapper;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCS\OCSBadRequestException;
use OCP\AppFramework\OCS\OCSForbiddenException;
use OCP\AppFramework\OCS\OCSNotFoundException;
use OCP\AppFramework\OCSController;
use OCP\IRequest;
use OCP\IUser;
use OCP\IUserManager;
use OCP\IUserSession;

class PointsController extends OCSController {

	/** @var LineMapper */
	protected $lineMapper;
	/** @var EditorMapper */
	protected $editorMapper;
	/** @var PointMapper */
	protected $pointMapper;
	/** @var IUserManager */
	protected $userManager;
	/** @var IUserSession */
	protected $userSession;

	public function __construct(string $appName,
								IRequest $request,
								LineMapper $lineMapper,
								EditorMapper $editorMapper,
								PointMapper $pointMapper,
								IUserManager $userManager,
								IUserSession $userSession) {
		parent::__construct($appName, $request);
		$this->lineMapper = $lineMapper;
		$this->editorMapper = $editorMapper;
		$this->pointMapper = $pointMapper;
		$this->userManager = $userManager;
		$this->userSession = $userSession;
	}

	/**
	 * @NoAdminRequired
	 * @param int $lineId
	 * @return DataResponse
	 * @throws OCSForbiddenException
	 * @throws OCSNotFoundException
	 */
	public function index(
		int $lineId
	): DataResponse {
		$user = $this->userSession->getUser();
		if (!$user instanceof IUser) {
			throw new OCSForbiddenException('Not logged in');
		}

		try {
			$this->editorMapper->findEditorForLine($lineId, $user->getUID());
		} catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
			throw new OCSNotFoundException('', $e);
		}

		$points = $this->pointMapper->findPointsForLine($lineId);
		return new DataResponse(array_map(static function (Point $point) {
			return $point->toArray();
		}, $points));
	}

	/**
	 * @NoAdminRequired
	 * @param int $lineId
	 * @param int $id
	 * @return DataResponse
	 * @throws OCSBadRequestException
	 */
	public function show(
		int $lineId,
		int $id
	): DataResponse {
		throw new OCSBadRequestException('Not implemented');
	}

	/**
	 * @NoAdminRequired
	 * @param int $lineId
	 * @param string $icon
	 * @param string $title
	 * @param string|null $description
	 * @param bool $highlight
	 * @param string $datetime
	 * @param int|null $fileId
	 * @return DataResponse
	 * @throws OCSForbiddenException
	 * @throws OCSNotFoundException
	 */
	public function create(
		int $lineId,
		string $icon,
		string $title,
		?string $description,
		bool $highlight,
		string $datetime,
		?int $fileId
	): DataResponse {
		$user = $this->userSession->getUser();
		if (!$user instanceof IUser) {
			throw new OCSForbiddenException('Not logged in');
		}

		try {
			$this->editorMapper->findEditorForLine($lineId, $user->getUID());
		} catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
			throw new OCSNotFoundException('', $e);
		}

		$date = new \DateTime($datetime);
		$date->setTimeZone(new \DateTimeZone('UTC'));

		$point = new Point();
		$point->setLineId($lineId);
		$point->setIcon($icon);
		$point->setTitle($title);
		$point->setDescription($description ?? '');
		$point->setHighlight($highlight);
		$point->setDatetime($date);
		$point->setFileId($fileId);
		$this->pointMapper->insert($point);

		return new DataResponse($point->toArray());
	}

	/**
	 * @NoAdminRequired
	 * @param int $lineId
	 * @param int $id
	 * @return DataResponse
	 * @throws OCSBadRequestException
	 */
	public function update(
		int $lineId,
		int $id
	): DataResponse {
		throw new OCSBadRequestException('Not implemented');
	}

	/**
	 * @NoAdminRequired
	 * @param int $lineId
	 * @param int $id
	 * @return DataResponse
	 * @throws OCSForbiddenException
	 * @throws OCSNotFoundException
	 */
	public function destroy(
		int $lineId,
		int $id
	): DataResponse {
		$user = $this->userSession->getUser();
		if (!$user instanceof IUser) {
			throw new OCSForbiddenException('Not logged in');
		}

		try {
			$point = $this->pointMapper->findById($id);
			if ($lineId !== $point->getLineId()) {
				throw new OCSNotFoundException();
			}
		} catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
			throw new OCSNotFoundException();
		}

		try {
			$this->editorMapper->findEditorForLine($lineId, $user->getUID());
		} catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
			throw new OCSNotFoundException();
		}

		$this->pointMapper->delete($point);

		return new DataResponse();
	}
}
