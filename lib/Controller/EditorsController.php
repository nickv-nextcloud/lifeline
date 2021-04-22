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

use OCA\LifeLine\Model\Editor;
use OCA\LifeLine\Model\EditorMapper;
use OCA\LifeLine\Model\LineMapper;
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

class EditorsController extends OCSController {

	/** @var LineMapper */
	protected $lineMapper;
	/** @var EditorMapper */
	protected $editorMapper;
	/** @var IUserManager */
	protected $userManager;
	/** @var IUserSession */
	protected $userSession;

	public function __construct(string $appName,
								IRequest $request,
								LineMapper $lineMapper,
								EditorMapper $editorMapper,
								IUserManager $userManager,
								IUserSession $userSession) {
		parent::__construct($appName, $request);
		$this->lineMapper = $lineMapper;
		$this->editorMapper = $editorMapper;
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

		$editors = $this->editorMapper->findEditorsForLine($lineId);
		$editorUserIds = array_map(static function (Editor $editor) {
			return $editor->getUserId();
		}, $editors);

		if (!in_array($user->getUID(), $editorUserIds, true)) {
			throw new OCSNotFoundException();
		}

		return new DataResponse($editorUserIds);
	}

	/**
	 * @NoAdminRequired
	 * @param int $lineId
	 * @param string $id
	 * @return DataResponse
	 * @throws OCSBadRequestException
	 */
	public function show(
		int $lineId,
		string $id
	): DataResponse {
		throw new OCSBadRequestException('Not implemented');
	}

	/**
	 * @NoAdminRequired
	 * @param int $lineId
	 * @param string $id
	 * @return DataResponse
	 * @throws OCSForbiddenException
	 * @throws OCSNotFoundException
	 */
	public function create(
		int $lineId,
		string $id
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

		$newUser = $this->userManager->get($id);
		if (!$newUser instanceof IUser) {
			throw new OCSNotFoundException('user');
		}

		try {
			$editor = $this->editorMapper->findEditorForLine($lineId, $newUser->getUID());
		} catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
			$editor = new Editor();
			$editor->setLineId($lineId);
			$editor->setUserId($newUser->getUID());
			$this->editorMapper->insert($editor);
		}
		return new DataResponse([
			'line_id' => $editor->getLineId(),
			'user_id' => $editor->getUserId(),
		]);
	}

	/**
	 * @NoAdminRequired
	 * @param int $lineId
	 * @param string $id
	 * @return DataResponse
	 * @throws OCSBadRequestException
	 */
	public function update(
		int $lineId,
		string $id
	): DataResponse {
		throw new OCSBadRequestException('Not implemented');
	}

	/**
	 * @NoAdminRequired
	 * @param int $lineId
	 * @param string $id
	 * @return DataResponse
	 * @throws OCSForbiddenException
	 * @throws OCSBadRequestException
	 * @throws OCSNotFoundException
	 */
	public function destroy(
		int $lineId,
		string $id
	): DataResponse {
		$user = $this->userSession->getUser();
		if (!$user instanceof IUser) {
			throw new OCSForbiddenException('Not logged in');
		}

		if ($user->getUID() === $id) {
			throw new OCSBadRequestException('self');
		}

		try {
			$this->editorMapper->findEditorForLine($lineId, $user->getUID());
		} catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
			throw new OCSNotFoundException();
		}

		try {
			$editor = $this->editorMapper->findEditorForLine($lineId, $id);
			$this->editorMapper->delete($editor);
		} catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
		}

		return new DataResponse();
	}
}
