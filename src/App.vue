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
	<Content
		app-name="lifeline">
		<AppNavigation :aria-label="t('lifeline', 'Life lines')">
			<template #list>
				<AppNavigationNewItem
					:title="t('lifeline', 'Create new line')"
					@new-item="createLine">
					<Plus
						slot="icon"
						:size="16"
						title=""
						decorative />
				</AppNavigationNewItem>
				<AppNavigationItem
					v-for="line in lines"
					:key="line.id"
					:title="line.name">
					<AccountDetails
						slot="icon"
						:size="16"
						title=""
						decorative />
				</AppNavigationItem>
			</template>
		</AppNavigation>
		<AppContent>
			<h2>{{ t('lifeline', 'Life line') }}</h2>
		</AppContent>
	</Content>
</template>

<script>
import AppContent from '@nextcloud/vue/dist/Components/AppContent'
import AppNavigation from '@nextcloud/vue/dist/Components/AppNavigation'
import AppNavigationItem from '@nextcloud/vue/dist/Components/AppNavigationItem'
import AppNavigationNewItem from '@nextcloud/vue/dist/Components/AppNavigationNewItem'
import Content from '@nextcloud/vue/dist/Components/Content'
import AccountDetails from 'vue-material-design-icons/AccountDetails'
import Plus from 'vue-material-design-icons/Plus'
import { showError } from '@nextcloud/dialogs'

export default {
	name: 'App',

	components: {
		AccountDetails,
		AppContent,
		AppNavigation,
		AppNavigationItem,
		AppNavigationNewItem,
		Content,
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

<style scoped lang="scss">

</style>
