(function ($) {
    
    AppView = Backbone.View.extend({
        el: $("body"),
        initialize: function(){
        },        
        events: {
            "change #tree":       "onTreeChange"
        },
        onTreeChange: function (el) {
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
        }    
        
    });
    
    var appview = new AppView;

})(jQuery);