var AccountManager = function(action){
  this.action = action
  $.ajaxSetup({
      beforeSend: function(xhr, settings) {
          if (!csrfSafeMethod(settings.type) && !this.crossDomain) {
              xhr.setRequestHeader("X-CSRFToken", csrftoken);
          }
      }
  });
};

$.extend(AccountManager.prototype, {
  callback: function(element) {
    var id = $(element).attr("id").split("-")[1];
    var self = this;
    var url = "/accounts/"
    if (self.action === 'approve'){
      url = url + "approve";
    } else {
      url = url + "deny";
    }
    url= url + '/' + id.toString() + "/";
    $.ajax({
      url: url,
      type: 'POST',
      data: {},
      dataType: 'json',
      success: function(json){
        if (json.success) {
          msgs = $("#messages-container")
          msgs.append(
            "<div class='alert alert-success' role='alert'>" +
              json.msg + "</div>");
          $('#item-' + id).remove();
          if (!$('.list-group').children().length){
            msgs.append("<div class='alert alert-info' role='alert'>" +
              "There are no users to manage at this time.</div>");
          }
        }
      },
      error: function(xhr, status, errorThrown){
        if (xhr.status === 403) {
              document.open();
              document.write(xhr.responseText);
              document.close();
        } else {
          console.log("XHR", xhr, "STATUS", status, "ERROR THROWN", errorThrown);
        }
      },
    });
    return false;
  },
});

var ApproveAccount = new AccountManager("approve");
var DenyAccount = new AccountManager("deny"); 