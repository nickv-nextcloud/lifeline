<template>
	<div>
		<h2>{{ line.name }}</h2>

		<div>
			<button class="primary"
				@click="showModal">
				<Plus :size="16" />
				{{ t('lifeline', 'Create new point') }}
			</button>
			<NcModal v-if="modal"
				@close="closeModal">
				<CreationModal :line-id="lineId"
					@close="closeModal" />
			</NcModal>
		</div>

		<Point v-for="point in points"
			v-bind="point"
			:key="point.id" />
	</div>
</template>

<script>
import CreationModal from '../components/CreationModal.vue'
import Point from '../components/Point.vue'
import NcModal from '@nextcloud/vue/dist/Components/NcModal.js'
import moment from '@nextcloud/moment'
import Plus from 'vue-material-design-icons/Plus.vue'

export default {
	name: 'LineView',

	components: {
		CreationModal,
		Point,
		NcModal,
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
			return this.$store.getters.getLine(this.lineId) || {}
		},
		points() {
			const r = Object.values(this.$store.getters.getPoints(this.lineId))
			return r.slice().sort(this.sortPointsByDate)
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
		},

		/**
		 *
		 * @param {object} point1 First point
		 * @param {string} point1.datetime First point date time in ATOM
		 * @param {object} point2 Second point
		 * @param {string} point2.datetime Second point date time in ATOM
		 * @return {number}
		 */
		sortPointsByDate(point1, point2) {
			const date1 = moment(point1.datetime)
			const date2 = moment(point2.datetime)
			return date2 - date1
		},
	},
}
</script>
