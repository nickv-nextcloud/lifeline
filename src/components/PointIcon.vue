<template>
	<div
		class="point__icon"
		:class="{ 'point__icon--highlight': highlight }">
		<Account
			v-if="icon === 'Account'"
			slot="icon"
			:size="size"
			title=""
			decorative />
		<AccountDetails
			v-else-if="icon === 'AccountDetails'"
			slot="icon"
			:size="size"
			title=""
			decorative />
		<AccountVoice
			v-else-if="icon === 'AccountVoice'"
			slot="icon"
			:size="size"
			title=""
			decorative />
		<Bike
			v-else-if="icon === 'Bike'"
			slot="icon"
			:size="size"
			title=""
			decorative />
		<Cake
			v-else-if="icon === 'Cake'"
			slot="icon"
			:size="size"
			title=""
			decorative />
		<CommentPlus
			v-else-if="icon === 'CommentPlus'"
			slot="icon"
			:size="size"
			title=""
			decorative />
		<FoodVariant
			v-else-if="icon === 'FoodVariant'"
			slot="icon"
			:size="size"
			title=""
			decorative />
		<AccountDetails
			v-else
			slot="icon"
			:size="size"
			title=""
			decorative />
	</div>
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
		size: {
			type: Number,
			default: 32,
		},
		highlight: {
			type: Boolean,
			default: false,
		},
	},
}
</script>

<style scoped lang="scss">
.point__icon {
	color: var(--color-text-maxcontrast);
	&--highlight {
		color: var(--color-primary-element);
	}
}
</style>
