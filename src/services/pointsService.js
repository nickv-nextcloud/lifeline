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
 * Get points
 *
 * @param {int} lineId The life line id to get the points for
 * @returns {Object} The axios response
 */
const getPoints = async function(lineId) {
	return axios.get(generateOcsUrl('apps/lifeline/api/v1/lines/{lineId}/points', { lineId }))
}

/**
 * Create a new point
 *
 * @param {int} lineId The life line id to add a new point on
 * @param {Object} point The point for the line
 * @returns {Object} The axios response
 */
const createPoint = async function(lineId, point) {
	return axios.post(generateOcsUrl('apps/lifeline/api/v1/lines/{lineId}/points', { lineId }), point)
}

export {
	getPoints,
	createPoint,
}
