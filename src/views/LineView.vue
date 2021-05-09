<template>
	<div>
		<h2>{{ line.name }}</h2>

		<div>
			<button
				class="primary"
				@click="showModal">
				<Plus
					:size="16"
					title=""
					decorative />
				{{ t('lifeline', 'Create new point') }}
			</button>
			<Modal
				v-if="modal"
				@close="closeModal">
				<CreationModal />
			</Modal>
		</div>

		<div
			v-for="point in points"
			:key="point.id">
			<AccountDetails
				slot="icon"
				:size="16"
				title=""
				decorative />
			{{ point.title }} - {{ point.datetime }}
		</div>
	</div>
</template>

<script>
import CreationModal from '../components/CreationModal'
import Modal from '@nextcloud/vue/dist/Components/Modal'
import AccountDetails from 'vue-material-design-icons/AccountDetails'
import Plus from 'vue-material-design-icons/Plus'

export default {
	name: 'LineView',

	components: {
		AccountDetails,
		CreationModal,
		Modal,
		Plus,
	},

	props: {
		lineId: {
			type: Number,
			required: true,
		},
	},

	data() {
		return {
			modal: false,
		}
	},

	computed: {
		line() {
			return this.$store.getters.getLine(this.lineId)
		},
		points() {
			const r = this.$store.getters.getPoints(this.lineId)
			console.error(r)
			return r
		},
	},

	mounted() {
		this.$store.dispatch('getPoints', { lineId: this.lineId })
		const rand = Date.now()
		this.$store.dispatch('createPoint', {
			lineId: this.lineId,
			point: {
				icon: 'AccountDetails',
				title: '$title:' + rand,
				description: '$description:' + rand,
				highlight: rand % 2 === 0,
				datetime: (new Date()).toUTCString(),
				fileId: null,
			},
		})
	},

	methods: {
		showModal() {
			this.modal = true
		},
		closeModal() {
			this.modal = false
		}
	},
}
</script>

<style scoped>

</style>
