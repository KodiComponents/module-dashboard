<div class="panel dashboard-widget panel-info panel-body-colorful panel-dark" data-id="{{ $widget->getId() }}">
	<button type="button" class="close" v-on:click="remove('{{ $widget->getId() }}')">
		{!! UI::icon('times') !!}
	</button>

	<button type="button" class="settings" v-on:click="showSettings('{{ $widget->getId() }}')">
		{!! UI::icon('cog') !!}
	</button>

	<div class="panel-body text-lg handle">
		<i class="fa fa-calendar fa-2x"></i>&nbsp;&nbsp;<span class="time-container"></span>
	</div>
</div>

<script type="text/javascript">
$('[data-id="{{ $widget->getId() }}"]').on('widget_init', function () {
	var update_dashboard_calendar = function () {
		$('.time-container').html(moment(new Date()).format('{{ $format }}'));
		setTimeout(update_dashboard_calendar, 60000);
	};
	update_dashboard_calendar();
});
</script>