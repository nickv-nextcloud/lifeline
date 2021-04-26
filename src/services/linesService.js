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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

import axios from '@nextcloud/axios'
import { generateOcsUrl } from '@nextcloud/router'

/**
 * Get lines
 *
 * @returns {Object} The axios response
 */
const getLines = async function() {
	return axios.get(generateOcsUrl('apps/lifeline/api/v1/lines'))
}

/**
 * Create a new life line
 *
 * @param {string} name The name of the line
 * @returns {Object} The axios response
 */
const createLine = async function(name) {
	return axios.post(generateOcsUrl('apps/lifeline/api/v1/lines'), {
		name,
	})
}

export {
	getLines,
	createLine,
}
