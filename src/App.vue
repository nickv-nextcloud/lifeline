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
					@new-item="function(value){alert(value)}" />
				<AppNavigationItem
					:title="t('lifeline', 'Line1')" />
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

export default {
	name: 'App',

	components: {
		AppContent,
		AppNavigation,
		AppNavigationItem,
		AppNavigationNewItem,
		Content,
	},

	data() {
		return {
		}
	},

	computed: {
	},

	mounted() {
	},

	methods: {
	},
}
</script>

<style scoped lang="scss">

</style>
