<template>
	<template
		v-if="icon === 'AccountDetails'">
		<AccountDetails
			slot="icon"
			:size="16"
			title=""
			decorative />
	</template>
	<template
		v-else-if="icon === 'AccountDetails'">
		<AccountDetails
			slot="icon"
			:size="16"
			title=""
			decorative />
	</template>
</template>

<script>
import Account from 'vue-material-design-icons/Account'
import AccountDetails from 'vue-material-design-icons/AccountDetails'
import AccountVoice from 'vue-material-design-icons/AccountVoice'
import Bike from 'vue-material-design-icons/Bike'
import Cake from 'vue-material-design-icons/Cake'
import CommentPlus from 'vue-material-design-icons/CommentPlus'
import FoodVariant from 'vue-material-design-icons/FoodVariant'

export default {
	name: 'PointIcon',

	components: {
		Account,
		AccountDetails,
		AccountVoice,
		Bike,
		Cake,
		CommentPlus,
		FoodVariant,
	},

	props: {
		icon: {
			type: String,
			required: true,
		},
	},
}
</script>

<style scoped>

</style>
