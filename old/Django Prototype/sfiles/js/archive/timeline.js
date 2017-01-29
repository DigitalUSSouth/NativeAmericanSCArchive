function reset_hierarchy(type, partition, limit){
  partition.each(function(index){
    var phase = $(this).find("[class^=timeline-phase-]"); 
    console.log("Phase", phase);
    if (index < limit){
      var nindex = (index + 1).toString();
      phase.attr('class', 'timeline-phase-' + type + "-" + nindex);
    } else {
      console.log(index, "hidden")
      phase.attr('class', 'timeline-hidden');
    }
  });
}

$('[class^=timeline-phase-]').click(function(){
  var container = $(this).parent().parent()
  var lower = container.nextAll();
  var higher = container.prevAll();
  reset_hierarchy('bottom', lower, 2);
  reset_hierarchy('top', higher, 2);
  $(this).attr('class', 'timeline-current');
});

