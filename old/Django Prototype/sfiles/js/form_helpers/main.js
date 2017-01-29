function getCookie(name) {

    var cookieValue = null;
    if (document.cookie && document.cookie != '') {
        var cookies = document.cookie.split(';');
        for (var i = 0; i < cookies.length; i++) {
            var cookie = jQuery.trim(cookies[i]);
            if (cookie.substring(0, name.length + 1) == (name + '=')) {
                cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                break;
            }
        }
    }
    return cookieValue;
}
var csrftoken = getCookie('csrftoken');

function csrfSafeMethod(method) {
    return (/^(GET|HEAD|OPTIONS|TRACE)$/.test(method));
}

function removeErrors(form){

    $(form).find('.form-group').each(function(){
        if($(this).hasClass('has-danger')){
            $(this).removeClass('has-danger');
        }
        if($(this).hasClass("form-control-danger")){
            $(this).removeClass("form-control-danger");
        }
        // This assumes manual rendering of an ul
        // etc to render form errors after form fields
        // update as necessary.
        $(this).next().empty();
    });
}

function addErrors(form, errors){

    for (var field in errors) {
        $("#div_id_" + field, form).addClass('has-danger');
        $("#id_" + field, form).addClass("form-control-danger");
        $.each(errors[field], function(k,v){
            $('#errors_'+field, form).append("<li>"+v+"</li>");
        }); 
    }
}

function updateForm(data, form, modal_form){

    if(data.saved){
        $(form).get(0).reset();
        removeErrors(form);
        if(modal_form){
            $(form).closest('.modal').modal('hide');        
        }
    }
    else{
        removeErrors(form);
        addErrors(form, data.errors);
    }
}

function showModalTimer(form, parent){

    parent.empty();
    var span = "<span id='timer'></span>";
    if($(form)[0].hasAttribute('name')){
        parent.append('<p>Added a ' + 
            $(form).attr('name') + '! '+ span + "</p>")                
    }
    else{
        parent.append('Success! '+span);
    }
    parent.fadeIn();
    var i = 10;
    window.setInterval(function(){
        $("#timer").text('Disappearing in '+ i + '.');
        if (i == 0){
            clearInterval($(this));
            parent.fadeOut();
        }
        i--;
    }, 1000);
}
