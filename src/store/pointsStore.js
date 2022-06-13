/**
 * @copyright Copyright (c) 2021 Joas Schilling <coding@schilljs.com>
 *
 * @license AGPL-3.0-or-later
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

import {
	createPoint,
	getPoints,
} from '../services/pointsService.js'
import Vue from 'vue'

const state = {
	points: {},
}

const getters = {
	getPoints: (state) => (lineId) => {
		if (state.points[lineId]) {
			return state.points[lineId]
		}
		return state.points
	},
}

const mutations = {
	/**
	 * Add a line to the store
	 *
	 * @param {object} state current store state;
	 * @param {object} point The point to be added to the store
	 */
	addPoint(state, point) {
		if (!state.points[point.line_id]) {
			Vue.set(state.points, point.line_id, {})
		}
		Vue.set(state.points[point.line_id], point.id, point)
	},
}

const actions = {
	async getPoints(context, { lineId }) {
		const response = await getPoints(lineId)

		response.data.ocs.data.forEach((point) => {
			context.commit('addPoint', point)
		})
	},

	async createPoint(context, { lineId, point }) {
		const response = await createPoint(lineId, point)
		context.commit('addPoint', response.data.ocs.data)
	},
}

export default { state, mutations, getters, actions }
