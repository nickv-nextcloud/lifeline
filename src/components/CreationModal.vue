<template>
	<div class="modal__content">
		<h2>{{ t('lifeline', 'Create new point') }}</h2>

		<p>
			<label for="icon">{{ t('lifeline', 'Icon') }}</label>
			<input id="icon"
				v-model="icon"
				type="text"
				placeholder="Multiselect">
		</p>

		<p>
			<label for="title">{{ t('lifeline', 'Headline') }}</label>
			<input id="title"
				v-model="title"
				type="text">
		</p>

		<p class="modal__content__highlight">
			<NcCheckboxRadioSwitch id="highlight"
				:checked.sync="highlight"
				type="switch">
				{{ t('lifeline', 'Highlight') }}
			</NcCheckboxRadioSwitch>
		</p>

		<p>
			<label for="title">{{ t('lifeline', 'Story') }}</label>
			<NcRichContenteditable :value.sync="description"
				:auto-complete="() => {}"
				:maxlength="400"
				:multiline="true"
				placeholder="Try mentioning the user Test01" />
		</p>

		<p>
			<label for="datetime">{{ t('lifeline', 'Date') }}</label>
			<NcDatetimePicker v-model="datetime"
				:input-attr="{
					id: 'datetime'
				}"
				type="date" />
		</p>

		<button class="modal__content__submit primary"
			@click="createPoint">
			{{ t('lifeline', 'Create point') }}
		</button>
	</div>
</template>

<script>
import NcCheckboxRadioSwitch from '@nextcloud/vue/dist/Components/NcCheckboxRadioSwitch.js'
import NcDatetimePicker from '@nextcloud/vue/dist/Components/NcDatetimePicker.js'
import NcRichContenteditable from '@nextcloud/vue/dist/Components/NcRichContenteditable.js'
import { showError } from '@nextcloud/dialogs'

export default {
	name: 'CreationModal',

	components: {
		NcCheckboxRadioSwitch,
		NcDatetimePicker,
		NcRichContenteditable,
	},

	props: {
		lineId: {
			type: Number,
			required: true,
		},
	},

	data() {
		return {
			icon: 'AccountDetails',
			title: t('lifeline', 'Headline'),
			description: '',
			datetime: null,
			highlight: false,
		}
	},

	methods: {
		createPoint() {
			if (!this.datetime) {
				showError(t('lifeline', 'Please select a date'))
				return
			}

			try {
				this.$store.dispatch('createPoint', {
					lineId: this.lineId,
					point: {
						icon: this.icon,
						title: this.title,
						description: this.description,
						highlight: this.highlight,
						datetime: this.datetime.toUTCString(),
						fileId: null,
					},
				})
				this.$emit('close')
			} catch (e) {
				showError(t('lifeline', 'An error occurred while sending the request'))
				console.error(e)
			}
		},
	},
}
</script>

<style lang="scss" scoped>
.modal__content {
	width: 50vw;
	margin: 10vw 2vw;

	label {
		width: 10vw;
		display: inline-block;
	}

	&__highlight,
	&__submit {
		margin-left: 10vw;
	}
}
</style>
