<div id="dashboard-widgets">
	<div class="page-header">
		<div class="row">
			<div class="col-xs-6">
				<h1 data-icon="dashboard">@lang('dashboard::core.title.dashboard')</h1>
			</div>

			@can('dashboard::manage')
			<widgets-popup></widgets-popup>
			@endcan
		</div>
	</div>

	<div class="gridster">
		<ul class="list-unstyled"></ul>
	</div>

	<div class="modal" tabindex="-1" role="dialog" id="widgetSettings">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="@lang('dashboard::core.buttons.popup_close')">
						<span aria-hidden="true">&times;</span>
					</button>

					@lang('dashboard::core.title.settings')
				</div>
				<div class="modal-body">
					{!! Form::open() !!}
					<input type="hidden" name="id" value="@{{ widget.widget.id }}" />
					@{{{ widget.template }}}

					{!! Form::close() !!}
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" v-on:click="saveSettings()">
						@lang('dashboard::core.buttons.save')
					</button>
					<button type="button" class="btn btn-danger" data-dismiss="modal">
						@lang('dashboard::core.buttons.popup_close')
					</button>
				</div>
			</div>
		</div>
	</div>
</div>

<template id="popupWidgetList">
	<div class="col-xs-6 text-right">
		{!! Form::checkbox('draggable', 1, 0, [
            'class' => 'form-switcher', 'data-size' => 'mini', 'id' => 'cache',
            'data-on' => trans('dashboard::core.buttons.draggable.enabled'),
            'data-off' => trans('dashboard::core.buttons.draggable.disabled'),
            'data-onstyle' => 'success'
        ]) !!}

		<button class="btn btn-primary btn-labeled" v-on:click="show">
			<span class="btn-label icon fa fa-cubes"></span>
			@lang('dashboard::core.buttons.add_widget')
		</button>
	</div>

	<div class="modal" tabindex="-1" role="dialog" id="popupList">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="@lang('dashboard::core.buttons.popup_close')">
						<span aria-hidden="true">&times;</span>
					</button>

					<h5>@lang('dashboard::core.title.available')</h5>
				</div>
				<div class="modal-body" v-if="widgets.length">
					<div class="row">
						<div class="col-sm-6" v-for="widget in widgets">
							<div class="panel">
								<div class="panel-body padding-sm">
									<button
											class="btn btn-success btn-xs pull-right"
											v-on:click="install($index, widget)"
											:disabled="widget.install"
									>
										@lang('dashboard::core.buttons.place_widget')
									</button>

									<h4>
										<i v-if="widget.icon" class="fa fa-@{{ widget.icon }} fa-lg"></i>
										@{{ widget.title }}
									</h4>

									<p v-if="widget.description" class="text-muted">@{{ widget.description }}</p>
								</div>
							</div>
						</div>
					</div>
				</div>

				<h3 class="alert alert-info alert-dark no-margin text-center" v-if="!widgets.length">
					@lang('dashboard::core.messages.no_widgets')
				</h3>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">
						@lang('dashboard::core.buttons.popup_close')
					</button>
				</div>
			</div>
		</div>
	</div>
</template>