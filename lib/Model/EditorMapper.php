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

namespace OCA\LifeLine\Model;

use OCP\AppFramework\Db\DoesNotExistException;
use OCP\AppFramework\Db\MultipleObjectsReturnedException;
use OCP\AppFramework\Db\QBMapper;
use OCP\DB\QueryBuilder\IQueryBuilder;
use OCP\IDBConnection;

/**
 * @template-extends QBMapper<Editor>
 * @method Editor mapRowToEntity(array $row)
 */
class EditorMapper extends QBMapper {

	public function __construct(IDBConnection $db) {
		parent::__construct($db, 'lifeline_editors', Editor::class);
	}

	public function createEditorFromRow(array $row): Editor {
		return $this->mapRowToEntity([
			'id' => (int) $row['id'],
			'line_id' => $row['line_id'],
			'user_id' => $row['user_id'],
		]);
	}

	/**
	 * @param int $lineId
	 * @param string $userId
	 * @return Editor
	 * @throws DoesNotExistException
	 * @throws MultipleObjectsReturnedException
	 */
	public function findEditorForLine(int $lineId, string $userId): Editor {
		$query = $this->db->getQueryBuilder();
		$query->select('*')
			->from($this->getTableName())
			->where($query->expr()->eq('line_id', $query->createNamedParameter($lineId), IQueryBuilder::PARAM_INT))
			->andWhere($query->expr()->eq('user_id', $query->createNamedParameter($userId)));

		return $this->findEntity($query);
	}

	/**
	 * @param int $lineId
	 * @return Editor[]
	 */
	public function findEditorsForLine(int $lineId): array {
		$query = $this->db->getQueryBuilder();
		$query->select('*')
			->from($this->getTableName())
			->where($query->expr()->eq('line_id', $query->createNamedParameter($lineId), IQueryBuilder::PARAM_INT));

		return $this->findEntities($query);
	}

	/**
	 * @param string $userId
	 * @return Editor[]
	 */
	public function findLinesForEditor(string $userId): array {
		$query = $this->db->getQueryBuilder();
		$query->select('*')
			->from($this->getTableName())
			->where($query->expr()->eq('user_id', $query->createNamedParameter($userId)));

		return $this->findEntities($query);
	}

	public function deleteByLineId(int $lineId): void {
		$query = $this->db->getQueryBuilder();
		$query->delete($this->getTableName())
			->where($query->expr()->eq('lind_id', $query->createNamedParameter($lineId), IQueryBuilder::PARAM_INT));
		$query->executeUpdate();
	}
}
