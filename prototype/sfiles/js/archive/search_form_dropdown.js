var Search = function(){
    this.type = null;
    this.prev_type = null;
    this.select = null;
    this.input_query = $('input[name="query"]');
    this.input = $('input[name="filter-type"]');
    this.input_after = $('#filter-btn-group div:first');
    this.attrs_to_fix = ['language', 'archive',
        ''];
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
            self.insert_select(json.data);
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
        console.log(this.input);
        var new_html = "<select name='query' class='form-control'>";
        $.each(JSON.parse(values), function(index, value){
            var value = $(this)[0];
            var verbose_name = $(this)[1];
            new_html = new_html + "<option value='" + value + "'>"
            new_html = new_html + verbose_name + "</option>"
        });
        this.select = $(new_html + "</select>");
        this.input_after.after(this.select);
    },

    switch_menu: function(element){
        var self = this;
        this.prev_type = this.input.attr('value');
        this.type = $(element).attr('data-value');
        if (this.type === 'language') {
            self.set_languages();
        } else if (this.type === 'archive') {
            self.set_archives();
        } else if (this.type === "digital-type") {
            self.set_digital_types();
        } else if (this.type === 'role') {
            self.set_roles();
        } else if (this.type === 'contributing-institution') {
            self.set_institutions();
        } else if (this.type === 'file-format') {
            self.set_file_formats();
        } else {
            // NEEDS TO BE UPDATED FOR POTENTIAL SELECTS.....
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
        console.log("New value", this.input.attr('value'));
    },

    set_archives: function() {
        var self = this;
        ajax_callback('/get-archives/', self);
    },

    set_digital_types: function(){
        var self = this;
        ajax_callback('/get-digital-types/', self);
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
    }
});

var search = new Search();