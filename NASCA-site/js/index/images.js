function init_images() {
  toggleSearch('on');
  $("#imagesModal").on('shown.bs.modal', function(e){
    var pointer = e.relatedTarget.dataset.pointer
    var image = images[pointer]
    var counter = 1;
    var rel = {
      title: "Title",
      relati: "Collection",
      publis: "Publisher",
      descri: "Description",
      creato: "Creator",
      date: "Date",
      datea: "--",
      dateb: "--",
      geogra: "Location",
      extent: "Extent",
      rights: "Use rights",
      langua: "Language",
      tribe: "Tribe"
    }
    var title = image['title']
    var titleHtml = "<h4>"+title+"</h4>";
    var html = "<p><strong>Title: </strong>"+title+"</strong></p>";
    $.each(image,function(index,val){
      if (counter++ < 7) return;
      if (val !== "" ){
        html = html+"<p><strong>"+rel[index]+": </strong>"+val+"</p>"
      }
    })
    $("#imagesModal .modal-title").html(titleHtml);
    $("#imagesModal .modal-foot").html(html);
    $("#imagesImg").attr("src",SITE_ROOT+"/db/data/images/"+pointer+"_large.jpg");

      setNewState("images",pointer);
  });

  $("#imagesModal").on('hidden.bs.modal', function(e){
    setNewState("images");
    $("#imagesModal .modal-title").html("<div class=\"text-center\"><h1>Loading...</h1><i class=\"fa fa-spinner fa-spin\" style=\"font-size:76px\"></i></h1></div>")
    $("#imagesModal .modal-foot").html("<div class=\"text-center\"><h1>Loading...</h1><i class=\"fa fa-spinner fa-spin\" style=\"font-size:76px\"></i></h1></div>")
    $("#imagesImg").attr("src","");
  });    

  if (currentUrl.length == 2){//we have a sub uri
    if ($.inArray(currentUrl[1],imagePointers) !== -1){
      currentImage = currentUrl[1];      
    }
    else {
      changePage("404","tabs-home");
      return;
    }
    $('.images-div[data-pointer="'+currentImage+'"]').click()
  }

}