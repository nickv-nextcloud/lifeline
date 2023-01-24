<!--
  - @copyright Copyright (c) 2021 Joas Schilling <coding@schilljs.com>
  -
  - @author Joas Schilling <coding@schilljs.com>
  -
  - @license GNU AGPL version 3 or any later version
  -
  - This program is free software: you can redistribute it and/or modify
  - it under the terms of the GNU Affero General Public License as
  - published by the Free Software Foundation, either version 3 of the
  - License, or (at your option) any later version.
  -
  - This program is distributed in the hope that it will be useful,
  - but WITHOUT ANY WARRANTY; without even the implied warranty of
  - MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  - GNU Affero General Public License for more details.
  -
  - You should have received a copy of the GNU Affero General Public License
  - along with this program. If not, see <http://www.gnu.org/licenses/>.
  -
  -->
<template>
	<NcContent app-name="lifeline">
		<NcAppNavigation :aria-label="t('lifeline', 'Life lines')">
			<template #list>
				<NcAppNavigationNewItem :title="t('lifeline', 'New life line')"
					@new-item="createLine">
					<Plus slot="icon"
						:size="16" />
				</NcAppNavigationNewItem>
				<NcAppNavigationItem v-for="line in lines"
					:key="line.id"
					:title="line.name"
					:to="routeTo(line)">
					<AccountDetails slot="icon"
						:size="16" />
				</NcAppNavigationItem>
			</template>
		</NcAppNavigation>
		<NcAppContent>
			<router-view />
		</NcAppContent>
	</NcContent>
</template>

<script>
import NcAppContent from '@nextcloud/vue/dist/Components/NcAppContent.js'
import NcAppNavigation from '@nextcloud/vue/dist/Components/NcAppNavigation.js'
import NcAppNavigationItem from '@nextcloud/vue/dist/Components/NcAppNavigationItem.js'
import NcAppNavigationNewItem from '@nextcloud/vue/dist/Components/NcAppNavigationNewItem.js'
import NcContent from '@nextcloud/vue/dist/Components/NcContent.js'
import AccountDetails from 'vue-material-design-icons/AccountDetails.vue'
import Plus from 'vue-material-design-icons/Plus.vue'
import { showError } from '@nextcloud/dialogs'

export default {
	name: 'App',

	components: {
		NcAppContent,
		NcAppNavigation,
		NcAppNavigationItem,
		NcAppNavigationNewItem,
		NcContent,
		AccountDetails,
		Plus,
	},

	data() {
		return {
		}
	},

	computed: {
		lines() {
			return this.$store.getters.getLines()
		},
	},

	mounted() {
		this.loadLines()
	},

	methods: {
		routeTo(line) {
			return {
				name: 'line',
				params: { id: line.id },
			}
		},

		loadLines() {
			try {
				this.$store.dispatch('getLines')
			} catch (e) {
				console.error(e)
				showError(t('lifeline', 'An error occurred while loading the lines'))
			}
		},
		createLine(name) {
			try {
				this.$store.dispatch('createLine', name)
			} catch (e) {
				console.error(e)
				showError(t('lifeline', 'An error occurred while creating the line'))
			}
		},
	},
}
</script>
