function Pagination() {
    var afterPagination = new Array();
    this.appendAfterPagination = function (appendor) {
        afterPagination.push(appendor);
    }

    this.pagination = function (object) {
        var self = this;
        var url = object.attr('url');
        var loading = $(".loading_box");
        if(loading.length > 0) {
            loading.show();
        }
        $.get(
            url,
            function(data, status) {
                if(status !== 'success') {
                    return ;
                }
                if(loading.length > 0) {
                    loading.hide();
                }
                var container = object.parents('.list');
                container.html(data);

                $.each(afterPagination, function (key, item) {
                    $(item[0]).on(item[2], item[1], eval(item[3]));
                })

                container.find('.pagination').on('click', 'li', function() {
                    self.pagination($(this));
                });
        });
    }

    var afterListFilterFormSubmit = new Array();
    this.appendAfterListFilterFormSubmit = function (appendor) {
        afterListFilterFormSubmit.push(appendor);
    }
    this.listFilterFormSubmit = function (object) {
        var self = this;
        var form = object.parents('form');
        var url = form.attr('action');
        var loading = $(".loading_box");
        if(loading.length > 0) {
            loading.show();
        }
        $.ajax({
            url: url,
            type: 'post',
            data: form.serialize(),
            success: function (data) {
                var panel = form.parents('.my-panel');
                var container = panel.find('.list');
                container.html(data);

                if(loading.length > 0) {
                    loading.hide();
                }

                $.each(afterListFilterFormSubmit, function (key, item) {
                    $(item[0]).on(item[2], item[1], eval(item[3]));
                })

                panel.find('.pagination').on('click', 'li', function() {
                    self.pagination($(this));
                });
            }
        });
    }
}