<div class="panel dashboard-widget" data-id="{{ $widget->getId() }}">
	<button type="button" class="close" v-on:click="remove('{{ $widget->getId() }}')" v-if="settings" data-icon="times"></button>
	<div class="panel-body text-center handle">
		{!! Form::button(trans('dashboard::types.cache_button.clear_cache'), [
			'data-icon' => 'trash-o fa-lg',
			'class' => 'btn btn-lg btn-danger btn-flat btn-block',
			'data-api-url' => '/api.cache.clear',
			'data-method' => 'DELETE',
			'style' => 'height: 58px'
		]) !!}
	</div>
</div>