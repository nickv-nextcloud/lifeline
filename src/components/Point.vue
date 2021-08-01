<template>
	<div
		:key="id"
		class="point">
		<div class="point__date">
			{{ dateLabel }}
		</div>
		<PointIcon
			:highlight="highlight"
			:icon="icon"
			:size="32" />
		<div class="point__content">
			<h3
				class="point__title"
				:class="{ 'point__title--highlight': highlight }">
				{{ title }}
			</h3>
			{{ description }}
		</div>
	</div>
</template>

<script>
import PointIcon from './PointIcon'
import moment from '@nextcloud/moment'

export default {
	name: 'Point',

	components: {
		PointIcon,
	},

	props: {
		id: {
			type: Number,
			required: true,
		},
		icon: {
			type: String,
			required: true,
		},
		title: {
			type: String,
			required: true,
		},
		description: {
			type: String,
			required: true,
		},
		highlight: {
			type: Boolean,
			required: true,
		},
		datetime: {
			type: String,
			required: true,
		},
		fileId: {
			type: Number,
			default: null,
		},
	},

	computed: {
		dateLabel() {
			return moment(this.datetime).format('DD/MMM/YY')
		},
	},
}
</script>

<style scoped lang="scss">
.point {
	min-height: 44px;
	width: 670px;
	margin: 0 auto;
	vertical-align: top;
	display: flex;
	padding: 10px 0;

	&__date {
		width: 120px;
		display: inline-block;
		color: var(--color-text-maxcontrast);
		line-height: calc(1.5 * var(--default-line-height));
	}

	&__icon {
		display: inline-block;
		position: relative; // Puts the icon background over the line
		background-color: var(--color-main-background) ;
		height: 52px;
		margin-top: -10px;
		padding: 10px 0;
	}

	&__content {
		display: inline-block;
		margin: -22px 0 0 -22px;
		padding: 25px 0 15px 32px;
		border-left: 5px solid var(--color-primary-light);
	}

	&__title {
		margin-top: 0;
		color: var(--color-text-maxcontrast);
		&--highlight {
			color: var(--color-main-text);
		}
	}
}
</style>
