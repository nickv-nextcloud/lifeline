<template>
	<EmptyContent>
		<h2>{{ t('lifeline', 'Create a new life line') }}</h2>

		<template #icon>
			<AccountDetails slot="icon"
				:size="64"
				title=""
				decorative />
		</template>

		<template #desc>
			{{ t('lifeline', 'Or edit an existing one') }}
		</template>
	</EmptyContent>
</template>

<script>
import EmptyContent from '@nextcloud/vue/dist/Components/EmptyContent'
import AccountDetails from 'vue-material-design-icons/AccountDetails'

export default {
	name: 'EmptyContentView',

	components: {
		AccountDetails,
		EmptyContent,
	},
}
</script>
