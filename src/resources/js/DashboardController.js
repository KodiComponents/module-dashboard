var Dashboard = {
    widgets: {
        gridster: null,
        init: function () {
            this.gridster = $(".gridster ul").gridster({
                widget_base_dimensions: [150, 100],
                widget_margins: [5, 5],
                autogrow_cols: true,
                resize: {
                    enabled: true,
                    start: function (e, ui, $widget) {
                        var $cont = $widget.find('.dashboard-widget');
                        $cont.trigger('resize_start', [this, ui, $cont.width(), $cont.height()])
                            .fadeTo(100, .5);
                    },
                    stop: function (e, ui, $widget) {
                        Dashboard.widgets.save_order();
                        var $cont = $widget.find('.dashboard-widget');

                        $cont
                            .fadeTo(100, 1)
                            .trigger('resize_stop', [this, ui, $cont.width(), $cont.height()]);
                    }
                },
                draggable: {
                    start: function (e, ui, $widget) {},
                    drag: function (e, ui, $widget) {},
                    stop: function (e, ui, $widget) {
                        Dashboard.widgets.save_order();
                        $('.gridster ul .preview-holder').remove();
                    }
                },
                serialize_params: function ($w, wgd) {
                    return {
                        col: wgd.col,
                        row: wgd.row,
                        x: wgd.size_x,
                        y: wgd.size_y,
                        'max-size': [wgd.max_size_x, wgd.max_size_y],
                        'min-size': [wgd.min_size_x, wgd.min_size_y],
                        id: $w.data('id')
                    };
                }
            }).data('gridster');

            $('.dashboard-widget').each(function () {
                var $cont = $(this);
                $cont.trigger('widget_init', [$cont.width(), $cont.height()]);
            });
        },
        place: function(html, id, size) {
            var $widget = this.gridster
                .add_widget
                .apply(this.gridster, [
                    $('<li data-id="'+id+'" />').append(html), size.x, size.y, size.col || false, size.row || false, size.max_size || [size.x, size.y], size.min_size || [size.x, size.y]
                ]);

            var max_size = size['max-size'] || [size.x, size.y];
            var min_size = size['min-size'] || [size.x, size.y];

            $widget.attr({
                'data-max_size_x': max_size[1] || size.x,
                'data-min_size_x': min_size[0] || size.x,
                'data-max_size_y': max_size[1] || size.y,
                'data-min_size_y': min_size[0] || size.y
            });

            var $cont = $widget.find('.dashboard-widget');
            $cont.trigger('widget_init', [$cont.width(), $cont.height()]);
        },
        add: function (html, id, size) {
            try {
                this.place(html, id, size)
            } catch (e) {
                return;
            }

            Dashboard.widgets.save_order();
        },
        remove: function (id) {
            var $widget = $('[data-id="'+id+'"]').closest('li');
            Api.delete('/api.dashboard.widget', {id: id}, function (response) {
                var $cont = $widget.find('.dashboard-widget');
                $cont.trigger('widget_destroy');

                Dashboard.widgets.gridster.remove_widget($widget, function () {
                    Dashboard.widgets.save_order();
                });
            });
        },
        save_order: function (array) {
            UserMeta.add('dashboard', this.gridster.serialize());
        },
        empty: function() {
            Dashboard.widgets.gridster.remove_all_widgets(function () {
                Dashboard.widgets.save_order();
            });
        }
    }
};

CMS.controllers.add('dashboard.get.index', function () {
    Vue.component('widgets-popup', Vue.extend({
        template: '#popupWidgetList',
        props: ['widgets'],
        ready: function () {
            CMS.ui.init();

            $('input[name="draggable"]').on('change', $.proxy(function (e) {
                if ($(e.target).is(':checked')) {
                    Dashboard.widgets.gridster.enable().enable_resize();
                    $('.remove_widget').show();
                } else {
                    Dashboard.widgets.gridster.disable().disable_resize();
                    $('.remove_widget').hide();
                }
            }, this)).change();
        },
        data: {
            widgets: []
        },
        methods: {
            show: function () {
                this.widgets = [];

                this.$http.get(Api.parseUrl('/api.dashboard.widget.available')).then(function (response) {
                    for (var type in response.data.content) {
                        var widget = response.data.content[type];

                        widget.type = type;
                        this.widgets.push(widget);
                    }

                    $('#popupList').modal('show');
                });
            },
            close: function () {
                $('#popupList').modal('close');
            },
            install: function (i, widget) {
                var self = this;
                Api.put('/api.dashboard.widget', {widget_type: widget.type}, function (response) {
                    var widget = response.content.widget,
                        template = response.content.template;

                    if (typeof widget.packages == 'object') {
                        for (i in widget.packages) {
                            getScript(widget.packages[i]);
                        }
                    }

                    setTimeout(function () {
                        Dashboard.widgets.add($(template), widget.id, widget.size);
                        self.widgets.splice(i, 1);
                    });
                });
            }
        }
    }));

    new Vue({
        el: '#dashboard-widgets',
        data: function () {
            Dashboard.widgets.init();
            this.getList();

            var self = this;

            $('#widgetSettings').on('hidden.bs.modal', function () {
                self.widget = null;
            });

            return {
                widget: this.widget
            }
        },
        methods: {
            getList: function() {
                Dashboard.widgets.empty();
                this.$http.get(Api.parseUrl('/api.dashboard.widget.list')).then(function (response) {
                    for(var i in response.data.content) {
                        var widget = response.data.content[i];
                        Dashboard.widgets.place(widget.template, widget.id, widget.data);
                    }

                    this.$compile(this.$el);
                    CMS.loader.hide();
                });
            },
            remove: function(id) {
                Dashboard.widgets.remove(id);
            },
            showSettings: function (id) {
                var self = this;
                CMS.loader.show('.gridster');
                Api.get('/api.dashboard.widget', {id: id}, function (response) {
                    self.widget = response.content;
                    CMS.loader.hide();
                    $('#widgetSettings').modal('show');
                });
            },
            saveSettings: function(widget) {
                var self = this;
                Api.post('/api.dashboard.widget', $('#widgetSettings form'), function (response) {
                    self.widget = response.content;
                    self.getList();
                });
            }
        }
    });
});

function getScript(url) {
    if ($('script[src="' + url + '"]').length > 0) return;

    var script = document.createElement('script');
    script.type = "text/javascript";
    script.src = url;

    script.onreadystatechange = function () {
        if (script.readyState === "loaded" || script.readyState === "complete") {
            script.onreadystatechange = null;
        }
    };

    document.getElementsByTagName("head")[0].appendChild(script);
}