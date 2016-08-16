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

// messages is from the add_msg.js
// YOU MUST INCLUDE THE SCRIPTS FROM messages/scripts/html
// BEFORE THE ACCOUNTMANAGER.

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
          messages.add('success', json.msg);
          $('#item-' + id).remove();
          if (!$('.list-group').children().length){
            messages.add('info', 
              'There are no users to manage at this time.')
          }
        }
      },
      error: function(xhr, status, errorThrown){
        if (xhr.status === 403) {
          document.open();
          document.write(xhr.responseText);
          document.close();
        } else if (xhr.status === 404 && 
                   xhr.statusText === "Not Found") {
          messages.add('danger', 
            'This user could not be found and has been removed from the management list.')
          $("#item-" + id).remove();
          console.log("BOJECT NOT FOUND");
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