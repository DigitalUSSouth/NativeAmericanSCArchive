$(document).ready(function() {

    var parent_ref = '.alternative-title-field';
    var delete_btn_ref = '.delete';
    var prefix = 'form';
    var base_meta = '#id_' + prefix;
    var insert_ref = '.form-actions';

    function fix_index(element, index){

      var attrs = new Array('for', 'id', 'name');
      var regex = new RegExp('(' + prefix + '-\\d+-)');
      var replacement = prefix + '-' + index + '-';

      $.each(attrs, function(index, attr) {
        var el_attr = $(element).attr(attr);
        if (el_attr){
          $(element).attr(attr, el_attr.replace(regex, replacement)
          );
        }
      });
    }

    function get_metadata_field(suffix) {

      return $(base_meta + suffix);
    }

    function get_metadata(suffix){

      return parseInt(get_metadata_field(suffix).val());
    }


    function delete_formset(count, min, button){

      if (count > 1) {
        if (count > min) {
          // Remove the form for the button clicked...
          $(button).parents(parent_ref).remove();
          // Get all the forms...
          var forms = $(parent_ref);
         
          get_metadata_field('-TOTAL_FORMS').val(forms.length);
         

         
          // POSSIBLY MOVE THIS SINCE I NEED TO UPDATE LABEL IN
          // ADD FORM AS WELL????!!!!!



          $(forms).each(function (index){
            var form_group = $(this).children().children('.form-group');
            var label = form_group.children('label');
            var input = form_group.children('input');
            fix_index(label, index);
            fix_index(input, index);
          });
          
        }
        else {
          console.log("You may not fall below the min forms");
        }
      }
      else {
        console.log("YOU NEED AT LEAST TWO FORMS TO DELETE");
      }
    }

    function add_formset(count, max, button){

      if (count < max){
        var form = $(parent_ref).clone(false).get(0);
        $(form).insertBefore(insert_ref);

        // CHECK THIS!!!!! Update new form indices.
        $(form).find(':input').each(function() {
          fix_index(this, count);
          $(this).val("");
        });

        // Need to rebind the delete button...
        $(form).find('.delete').click(function() {
          formset_action('delete', this);
        });
      }
      else {
        console.log("MAX FORMS EXCEEDED");
      }
    }

    function formset_action(action, button){

      var count = get_metadata('-TOTAL_FORMS');

      if (action === 'add'){
        var max = get_metadata('-MAX_NUM_FORMS');
        add_formset(count, max, button);
        get_metadata_field(base, '-TOTAL_FORMS').val(count + 1);
      }
      else {
        var min = get_metadata('-MIN_NUM_FORMS');
        delete_formset(count, min, button);
      }
    }

    $('#button-id-add').click(function (){
      formset_action('add', this);
    });

    $('.delete').click(function() {
      formset_action('delete', this);
    })
  })