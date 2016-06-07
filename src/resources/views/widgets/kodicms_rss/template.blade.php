<div class="panel dashboard-widget rss-feed-widget" data-id="{{ $widget->getId() }}">
	<div class="panel-heading">
		<span class="panel-title" data-icon="rss">@lang('dashboard::types.kodicms_rss.title')</span>
		<div class="panel-heading-controls">
			<button type="button" class="btn btn-xs btn-default remove_widget">{!! UI::icon('times') !!}</button>
		</div>
	</div>

	<ul class="list-group">
		@foreach ($feed as $item)
		<a class="list-group-item" href="{{ $item['link']['href'] }}" target="_blank">
			{{ $item['title'] }} <small class="text-muted">{{ Date::format($item['updated']) }}</small>
		</a>
		@endforeach
	</ul>
</div>

<script type="text/javascript">
$('[data-id="{{ $widget->getId() }}"]').on('widget_init', function () {
	Scroll.addToWidget(this, '.list-group', ['.panel-heading']);
});
</script>