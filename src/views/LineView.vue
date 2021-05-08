<template>
	<div>
		<h2>{{ line.name }}</h2>

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
import AccountDetails from 'vue-material-design-icons/AccountDetails'

export default {
	name: 'LineView',

	components: {
		AccountDetails,
	},

	props: {
		lineId: {
			type: Number,
			required: true,
		},
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
}
</script>

<style scoped>

</style>
