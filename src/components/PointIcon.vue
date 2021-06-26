<template>
	<Account
		v-if="icon === 'Account'"
		slot="icon"
		:size="16"
		title=""
		decorative />
	<AccountDetails
		v-else-if="icon === 'AccountDetails'"
		slot="icon"
		:size="16"
		title=""
		decorative />
	<AccountVoice
		v-else-if="icon === 'AccountVoice'"
		slot="icon"
		:size="16"
		title=""
		decorative />
	<Bike
		v-else-if="icon === 'Bike'"
		slot="icon"
		:size="16"
		title=""
		decorative />
	<Cake
		v-else-if="icon === 'Cake'"
		slot="icon"
		:size="16"
		title=""
		decorative />
	<CommentPlus
		v-else-if="icon === 'CommentPlus'"
		slot="icon"
		:size="16"
		title=""
		decorative />
	<FoodVariant
		v-else-if="icon === 'FoodVariant'"
		slot="icon"
		:size="16"
		title=""
		decorative />
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
