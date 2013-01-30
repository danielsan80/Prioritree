(function ($) {
    
    AppView = Backbone.View.extend({
        el: $("body"),
        initialize: function(){
            this.getTree();
        },        
        events: {
            "keyup #tree":       "updateTree"
        },
        updateTree: function (el) {
            var id = $(el.srcElement).attr('data-id');
            var url = '/tree/'+id;
            var data = $(el.srcElement).val();
            $.ajax({
                url: url,
                data: data,
                dataType: 'text',
                type: 'PUT',
                error: function(data) {
                    $(el.srcElement).addClass('error');
                    $(el.srcElement).removeClass('edit');
                },
                statusCode: {
                    200: function(data) {
                        $(el.srcElement).val(data);
                        $(el.srcElement).removeClass('error');
                        $(el.srcElement).removeClass('edit');
                    },
                    204: function(data) {
                        $(el.srcElement).addClass('edit');
                        $(el.srcElement).removeClass('error');
                    }
                }
            });
            return false;
        },    
        getTree: function () {
            var el = $("#tree");
            var id = el.attr('data-id');
            var url = '/tree/'+id;
            $.ajax({
                url: url,
                type: 'GET',
                success: function(data) {
                    $("#tree").html(data);
                }
            });
            return false;
        }    
        
    });
    
    var appview = new AppView;

})(jQuery);