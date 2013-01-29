(function ($) {
    
    AppView = Backbone.View.extend({
        el: $("body"),
        initialize: function(){
            this.getTree();
        },        
        events: {
            "change #tree":       "updateTree"
        },
        updateTree: function (el) {
            var id = $(el.srcElement).attr('data-id');
            var url = '/tree/'+id;
            var data = $(el.srcElement).html();
            $.ajax({
                url: url,
                data: data,
                dataType: 'text',
                type: 'PUT',
                success: function(data) {
                    $(el.srcElement).html();
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