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
use OCA\LifeLine\Model\Line;
use OCA\LifeLine\Model\LineMapper;
use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\AppFramework\Http\DataResponse;
use OCP\AppFramework\OCS\OCSBadRequestException;
use OCP\AppFramework\OCS\OCSException;
use OCP\AppFramework\OCS\OCSForbiddenException;
use OCP\AppFramework\OCS\OCSNotFoundException;
use OCP\AppFramework\OCSController;
use OCP\IRequest;
use OCP\IUser;
use OCP\IUserSession;

class LinesController extends OCSController {

	/** @var LineMapper */
	protected $lineMapper;
	/** @var EditorMapper */
	protected $editorMapper;
	/** @var IUserSession */
	protected $userSession;

	public function __construct(string $appName,
								IRequest $request,
								LineMapper $lineMapper,
								EditorMapper $editorMapper,
								IUserSession $userSession) {
		parent::__construct($appName, $request);
		$this->lineMapper = $lineMapper;
		$this->editorMapper = $editorMapper;
		$this->userSession = $userSession;
	}

	/**
	 * @NoAdminRequired
	 * @return DataResponse
	 * @throws OCSForbiddenException
	 */
	public function index(): DataResponse {
		$user = $this->userSession->getUser();
		if (!$user instanceof IUser) {
			throw new OCSForbiddenException('Not logged in');
		}

		$editors = $this->editorMapper->findLinesForEditor($user->getUID());

		$lines = [];
		foreach ($editors as $editor) {
			$lines[] = $this->lineMapper->findById($editor->getLineId());
		}

		return new DataResponse(array_map(static function(Line $line) {
			return [
				'id' => $line->getId(),
				'name' => $line->getName(),
			];
		}, $lines));
	}

	/**
	 * @NoAdminRequired
	 * @param int $id
	 * @return DataResponse
	 * @throws OCSForbiddenException
	 * @throws OCSNotFoundException
	 */
	public function show(int $id): DataResponse {
		$user = $this->userSession->getUser();
		if (!$user instanceof IUser) {
			throw new OCSForbiddenException('Not logged in');
		}

		try {
			$this->editorMapper->findEditorForLine($id, $user->getUID());
		} catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
			throw new OCSNotFoundException('', $e);
		}

		try {
			$line = $this->lineMapper->findById($id);

			$editors = $this->editorMapper->findEditorsForLine($line->getId());
			$editorUserIds = array_map(static function(Editor $editor) {
				return $editor->getUserId();
			}, $editors);

			return new DataResponse([
				'id' => $line->getId(),
				'name' => $line->getName(),
				'editors' => $editorUserIds,
			]);
		} catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
			throw new OCSNotFoundException('', $e);
		}
	}

	/**
	 * @NoAdminRequired
	 * @param string $name
	 * @return DataResponse
	 * @throws OCSBadRequestException
	 * @throws OCSForbiddenException
	 */
	public function create(
		string $name
	): DataResponse {
		$user = $this->userSession->getUser();
		if (!$user instanceof IUser) {
			throw new OCSForbiddenException('Not logged in');
		}

		$name = trim($name);
		if ($name === '') {
			throw new OCSBadRequestException('name');
		}

		$line = new Line();
		$line->setName($name);
		$this->lineMapper->insert($line);

		$editor = new Editor();
		$editor->setLineId($line->getId());
		$editor->setUserId($user->getUID());
		$this->editorMapper->insert($editor);

		return new DataResponse([
			'id' => $line->getId(),
			'name' => $line->getName(),
		]);
	}

	/**
	 * @NoAdminRequired
	 * @param int $id
	 * @param string $name
	 * @return DataResponse
	 * @throws OCSBadRequestException
	 * @throws OCSForbiddenException
	 * @throws OCSNotFoundException
	 */
	public function update(
		int $id,
		string $name
	): DataResponse {
		$user = $this->userSession->getUser();
		if (!$user instanceof IUser) {
			throw new OCSForbiddenException('Not logged in');
		}

		try {
			$this->editorMapper->findEditorForLine($id, $user->getUID());
		} catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
			throw new OCSNotFoundException('', $e);
		}

		$name = trim($name);
		if ($name === '') {
			throw new OCSBadRequestException('name');
		}

		try {
			$line = $this->lineMapper->findById($id);

			if ($name !== $line->getName()) {
				$line->setName($name);
				$this->lineMapper->update($line);
			}

			return new DataResponse([
				'id' => $line->getId(),
				'name' => $line->getName(),
			]);
		} catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
			throw new OCSNotFoundException('', $e);
		}
	}

	/**
	 * @NoAdminRequired
	 * @param int $id
	 * @return DataResponse
	 * @throws OCSForbiddenException
	 * @throws OCSNotFoundException
	 */
	public function destroy(int $id): DataResponse {
		$user = $this->userSession->getUser();
		if (!$user instanceof IUser) {
			throw new OCSForbiddenException('Not logged in');
		}

		try {
			$this->editorMapper->findEditorForLine($id, $user->getUID());
		} catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
			throw new OCSNotFoundException('', $e);
		}

		try {
			$line = $this->lineMapper->findById($id);
			$this->lineMapper->delete($line);

			$this->editorMapper->deleteByLineId($id);

			return new DataResponse([
				'id' => $line->getId(),
				'name' => $line->getName(),
			]);
		} catch (DoesNotExistException | MultipleObjectsReturnedException $e) {
			throw new OCSNotFoundException('', $e);
		}
	}
}
