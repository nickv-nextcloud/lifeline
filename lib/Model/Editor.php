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

use OCP\AppFramework\Db\Entity;

/**
 * @method void setLineId(int $lineId)
 * @method int getLineId()
 * @method void setUserId(string $userId)
 * @method string getUserId()
 */
class Editor extends Entity {

	/** @var int */
	protected $lineId;
	/** @var string */
	protected $userId;

	public function __construct() {
		$this->addType('line_id', 'int');
		$this->addType('user_id', 'string');
	}
}
