var meta_base, par_ref;
// fields to check for when updating forms and the respective
// elements attrs with the current form index in the formset.
var attrs = ['for', 'id', 'name'];

function fix_index(element, index){

  var regex = new RegExp('(' + meta_base.substring(3) + '-\\d+-)');
  var replacement = meta_base.substring(3) + '-' + index + '-';
  for (var i=0; i < attrs.length; ++i){
    var attr = $(element).attr(attrs[i]);
    if (typeof attr !== 'undefined'){
      $(element).attr(attrs[i], attr.replace(regex, replacement));
    }
  }
}

function update_forms(forms){

  $(forms).each(function (form_index, val){
    fix_index($(this), form_index)
    $(this).find('*').each(function(index, child){
      fix_index($(child), form_index);
    });
  });
}

function metadata(suffix, new_value, int=false){

  var object = $(meta_base + suffix);
  var partial = (typeof new_value == 'undefined' || new_value 
    === null) ? object.val() : object.val(new_value);
  return int ? parseInt(partial) : partial;
}

function delete_formset(count, btn){

  var min = metadata('-MIN_NUM_FORMS');  
  if (count > 1) {
    if (count > min) {
      $(btn).parent().prev().remove();
      $(btn).remove();
      metadata('-TOTAL_FORMS', $(par_ref).length)
    }
  }
  return false;
}

function add_formset(count, btn){

  var max = metadata('-MAX_NUM_FORMS');
  if (count < max){
    var form = $(par_ref).clone(false).get(0);
    var new_form = $(form).insertBefore("table");
    $(new_form).find('input:text').val("");
    var new_btn = $(par_ref).next('.delete-formset-parent').clone(false).get(0);
    $(new_btn).insertAfter(new_form);
    // Need to rebind the delete button...
    $(new_btn).find('.delete-formset').click(function() {
      formset_action('delete', this);
    });
    metadata('-TOTAL_FORMS', count + 1);
  }
  return false;
}

function formset_action(action, btn){

  var count = metadata('-TOTAL_FORMS', null, true);
  if (action === 'add'){
    add_formset(count, btn);
  }
  else {
    delete_formset(count, btn);
  }
  update_forms($(par_ref));  
  return false;
}