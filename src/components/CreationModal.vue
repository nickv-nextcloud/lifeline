<template>
	<div class="modal__content">
		<h2>{{ t('lifeline', 'Create new point') }}</h2>

		<p>
			<label for="icon">{{ t('lifeline', 'Icon') }}</label>
			<input
				id="icon"
				v-model="icon"
				type="text"
				placeholder="Multiselect">
		</p>

		<p>
			<label for="title">{{ t('lifeline', 'Headline') }}</label>
			<input
				id="title"
				v-model="title"
				type="text">
		</p>

		<p>
			<label for="title">{{ t('lifeline', 'Story') }}</label>
			<RichContenteditable
				v-model="description"
				:auto-complete="() => {}"
				:maxlength="400"
				:multiline="true"
				placeholder="Try mentioning the user Test01" />
		</p>

		<p>
			<label for="date">{{ t('lifeline', 'Date') }}</label>
			<DatetimePicker
				v-model="date"
				:input-attr="{
					id: 'date'
				}"
				type="date" />
		</p>
	</div>
</template>

<script>
import DatetimePicker from '@nextcloud/vue/dist/Components/DatetimePicker'
import RichContenteditable from '@nextcloud/vue/dist/Components/RichContenteditable'

export default {
	name: 'CreationModal',

	components: {
		DatetimePicker,
		RichContenteditable,
	},

	data() {
		return {
			icon: '',
			title: '',
			description: '',
			date: null,
		}
	},
}
</script>

<style scoped>
.modal__content {
	width: 50vw;
	margin: 10vw 0;
	text-align: center;
}
</style>
