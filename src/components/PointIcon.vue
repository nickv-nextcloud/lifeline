<template>
	<div class="point__icon"
		:class="{ 'point__icon--highlight': highlight }">
		<Account v-if="icon === 'Account'"
			:size="size" />
		<AccountDetails v-else-if="icon === 'AccountDetails'"
			:size="size" />
		<AccountVoice v-else-if="icon === 'AccountVoice'"
			:size="size" />
		<Bike v-else-if="icon === 'Bike'"
			:size="size" />
		<Cake v-else-if="icon === 'Cake'"
			:size="size" />
		<CommentPlus v-else-if="icon === 'CommentPlus'"
			:size="size" />
		<FoodVariant v-else-if="icon === 'FoodVariant'"
			:size="size" />
		<AccountDetails v-else
			:size="size" />
	</div>
</template>

<script>
import Account from 'vue-material-design-icons/Account.vue'
import AccountDetails from 'vue-material-design-icons/AccountDetails.vue'
import AccountVoice from 'vue-material-design-icons/AccountVoice.vue'
import Bike from 'vue-material-design-icons/Bike.vue'
import Cake from 'vue-material-design-icons/Cake.vue'
import CommentPlus from 'vue-material-design-icons/CommentPlus.vue'
import FoodVariant from 'vue-material-design-icons/FoodVariant.vue'

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
