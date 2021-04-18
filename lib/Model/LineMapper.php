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
 * @template-extends QBMapper<Line>
 * @method Line mapRowToEntity(array $row)
 */
class LineMapper extends QBMapper {

	public function __construct(IDBConnection $db) {
		parent::__construct($db, 'lifeline_lines', Line::class);
	}

	public function createLineFromRow(array $row): Line {
		return $this->mapRowToEntity([
			'id' => (int) $row['id'],
			'name' => $row['name'],
		]);
	}

	/**
	 * @param int $id
	 * @return Line
	 * @throws DoesNotExistException
	 * @throws MultipleObjectsReturnedException
	 */
	public function findById(int $id): Line {
		$query = $this->db->getQueryBuilder();
		$query->select('*')
			->from($this->getTableName())
			->where($query->expr()->eq('id', $query->createNamedParameter($id), IQueryBuilder::PARAM_INT));

		return $this->findEntity($query);
	}
}
