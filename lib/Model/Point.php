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
 * @method void setIcon(string $icon)
 * @method string getIcon()
 * @method void setTitle(string $title)
 * @method string getTitle()
 * @method void setDescription(?string $description)
 * @method string|null getDescription()
 * @method void setHighlight(bool $highlight)
 * @method bool isHighlight()
 * @method void setDatetime(string $datetime)
 * @method \DateTime getDatetime()
 * @method void setFileId(?int $fileId)
 * @method int|null getFileId()
 */
class Point extends Entity {

	/** @var int */
	protected $lineId;
	/** @var string */
	protected $icon;
	/** @var string */
	protected $title;
	/** @var string|null */
	protected $description;
	/** @var bool */
	protected $highlight;
	/** @var \DateTime */
	protected $datetime;
	/** @var int|null */
	protected $fileId;

	public function __construct() {
		$this->addType('lineId', 'integer');
		$this->addType('icon', 'string');
		$this->addType('title', 'string');
		$this->addType('description', 'string');
		$this->addType('highlight', 'bool');
//		$this->addType('datetime', 'datetime');
		$this->addType('fileId', 'integer');
	}

	public function toArray(): array {
		return [
			'id' => $this->getId(),
			'line_id' => $this->getLineId(),
			'icon' => $this->getIcon(),
			'title' => $this->getTitle(),
			'description' => $this->getDescription(),
			'highlight' => $this->isHighlight(),
			'datetime' => $this->getDatetime(),
			'fileId' => $this->getFileId(),
		];
	}
}
