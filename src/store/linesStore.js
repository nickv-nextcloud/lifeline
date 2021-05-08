/**
 * @copyright Copyright (c) 2021 Joas Schilling <coding@schilljs.com>
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

import {
	createLine,
	getLines,
} from '../services/linesService'
import Vue from 'vue'

const state = {
	lines: {},
}

const getters = {
	getLines: (state) => () => {
		return state.lines
	},
	getLine: (state) => (id) => {
		if (state.lines[id]) {
			return state.lines[id]
		}
		return null
	},
}

const mutations = {
	/**
	 * Add a line to the store
	 *
	 * @param {object} state current store state;
	 * @param {object} line The line to be added to the store
	 */
	addLine(state, line) {
		Vue.set(state.lines, line.id, line)
	},
}

const actions = {
	/**
	 * Create a new life line
	 *
	 * @param {object} context default store context;
	 * @param {string} name The name of the new line
	 */
	async createLine(context, name) {
		const response = await createLine(name)
		context.commit('addLine', response.data.ocs.data)
	},

	async getLines(context) {
		const response = await getLines()

		response.data.ocs.data.forEach((line) => {
			context.commit('addLine', line)
		})
	},
}

export default { state, mutations, getters, actions }
