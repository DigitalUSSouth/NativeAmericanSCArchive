var Search = function(){
    this.type = null;
    this.prev_type = null;
    this.select = null;
    this.input_query = $('input[name="query"]');
    this.input = $('input[name="filter-type"]');
    this.input_after = $('#filter-btn-group div:first');
    this.search_btn = $('#search-btn');
    this.search_btn_disabled = false;
    $.ajaxSetup({
      beforeSend: function(xhr, settings) {
          if (!csrfSafeMethod(settings.type) && !this.crossDomain) {
              xhr.setRequestHeader("X-CSRFToken", csrftoken);
          }
      }
    });
}

var ajax_callback =  function(url, self){
    $.ajax({
        url: url,
        type: 'GET',
        dataType: 'json',
        success: function(json) {
            self.insert_select(JSON.parse(json.data));
        },
        error: function(xhr, status, errorThrown) {
            if (xhr.status === 403) {
                document.open();
                document.write(xhr.responseText);
                document.close();
            } else {
              console.log("XHR", xhr, "STATUS", status, "ERROR THROWN", errorThrown);
            }
        },
    });
}

$.extend(Search.prototype, {

    insert_select: function(values) {
        if (this.select){
            this.select.remove();
        }
        this.input_query.remove();
        var new_html = "<select name='query' class='form-control'>";
        $.each(values, function(index, value){
            if(value.constructor == Array){
                var value = $(this)[0]
                var verbose_name = $(this)[1]
            }
            else if(value.constructor == Object){
                for (var verbose_name in value) break;
                var value = value[verbose_name];
            }
            new_html = new_html + "<option value='" + value + "'>"
            new_html = new_html + verbose_name + "</option>"
        });
        if (this.type === "role"){
            this.select = $(
                "<div class='form-group'>" +
                    "<div class='col-xs-6 no-padding'>"+ 
                        new_html + "</select>" +
                    "</div>" +
                    "<div class='col-xs-6 no-padding'>" +
                        "<input type='text' name='role-name' placeholder='Name' class='form-control'>" +
                    "</div>" +
                "</div>")
        } else {
            this.select = $(new_html + "</select>");        
        }
        if ((values[0][0] == "" && values[0][1] == "")) {
            this.select.prop('disabled', true);
            if (this.type == "role"){
                $('input[name="role-name"').prop('disabled', true);
            }
            this.search_btn_disabled = true;
            this.search_btn.prop('disabled', true);
        } else {
            if (this.search_btn_disabled) {
                this.search_btn.prop('disabled', false);
            }
        }
        this.input_after.after(this.select);
    },

    switch_menu: function(element){
        var self = this;
        this.prev_type = this.input.attr('value');
        this.type = $(element).attr('data-value');
        if (this.type === 'language') {
            self.set_languages();
        } else if (this.type === 'collection') {
            self.set_collections();
        } else if (this.type === "digital-type") {
            self.set_digital_types();
        } else if (this.type == 'content-type') {
            self.set_content_types();
        } else if (this.type === 'role') {
            self.set_roles();
        } else if (this.type === 'contributing-institution') {
            self.set_institutions();
        } else if (this.type === 'file-format') {
            self.set_file_formats();
        } else if (this.type === 'genre') {
            self.set_genres();
        } else if (this.type === 'copyright-holder') {
            self.set_copyright_holders();
        } else if (this.type === 'physical-type') {
            self.set_physical_types();
        } else {
            if (this.prev_type !== "all"){
                if (this.select){
                    this.select.remove();
                }
                this.input_after.after(this.input_query);
            }
            var $btn = $(this).parents('.btn-group').find('.btn:first-child');
            var $span = $btn.find('span');
            $btn.html($(this).text());
            $btn.append("&nbsp;");
            $btn.append($span);
        }
        this.input.attr('value', this.type);
    },

    set_collections: function() {
        var self = this;
        ajax_callback('/get-collections/', self);
    },

    set_digital_types: function(){
        var self = this;
        ajax_callback('/get-digital-types/', self);
    },

    set_content_types: function(){
        var self = this;
        ajax_callback('/get-content-types/', self);
    },

    set_physical_types: function(){
        var self = this;
        ajax_callback('/get-physical-types/', self);
    },

    set_languages: function() {
        var self = this;
        ajax_callback('/get-languages/', self);
    },

    set_institutions: function(){
        var self = this;
        ajax_callback('/get-contributing-institutions/', self);
    },

    set_roles: function(){
        var self = this;
        ajax_callback('/get-roles/', self);
    },

    set_file_formats: function(){
        var self = this;
        ajax_callback('/get-file-formats/', self);
    },

    set_genres: function(){
        var self = this;
        ajax_callback('/get-genres/', self);
    },

    set_copyright_holders: function(){
        var self = this;
        ajax_callback('/get-copyright-holders/', self);
    },

});

var search = new Search();