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
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */

import Vue from 'vue'
import Router from 'vue-router'
import { getRootUrl, generateUrl } from '@nextcloud/router'
import EmptyContentView from './views/EmptyContentView.vue'
import LineView from './views/LineView.vue'

Vue.use(Router)

const webRootWithIndexPHP = getRootUrl() + '/index.php'
const doesURLContainIndexPHP = window.location.pathname.startsWith(webRootWithIndexPHP)
const base = generateUrl('/apps/lifeline/', {}, {
	noRewrite: doesURLContainIndexPHP,
})

export default new Router({
	// if index.php is in the url AND we got this far, then it's working:
	// let's keep using index.php in the url
	base,
	linkActiveClass: 'active',
	routes: [
		{
			path: '/',
			name: 'main',
			component: EmptyContentView,
		},
		{
			path: '/line/:id',
			name: 'line',
			components: {
				default: LineView,
			},
			props: {
				default: (route) => {
					return {
						lineId: parseInt(route.params.id, 10),
					}
				},
			},
		},
	],
})
