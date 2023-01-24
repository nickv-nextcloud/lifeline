<template>
	<NcEmptyContent :title="t('lifeline', 'Create a new life line')"
		:description="t('lifeline', 'Or edit an existing one')">
		<template #icon>
			<AccountDetails :size="64" />
		</template>
	</NcEmptyContent>
</template>

<script>
import NcEmptyContent from '@nextcloud/vue/dist/Components/NcEmptyContent.js'
import AccountDetails from 'vue-material-design-icons/AccountDetails.vue'

export default {
	name: 'EmptyContentView',

	components: {
		AccountDetails,
		NcEmptyContent,
	},
}
</script>
