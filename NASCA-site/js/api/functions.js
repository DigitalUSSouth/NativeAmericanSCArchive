function jsUcfirst(string) {
  return string.charAt(0).toUpperCase() + string.slice(1);
}

function intervalFade(fadeIns,interval) {
  var i = 0;
  var intervalId = setInterval(function(){
    $(fadeIns[i]).fadeIn('slow');
    i++;
    dynamic_css();
    //if last one in list
    if(i === fadeIns.length) {
      clearInterval(intervalId);
      dynamic_css();
    }
  },interval);
  dynamic_css();
}

function init_shadows() {
  var caster = $('.shadow').siblings('div.shadow-caster');
  if(caster.length === 0) {
    return 1;
  }
  var width = caster.width();
  var parentWidth = caster.parent().width();
  var percentWidth = 100*width/parentWidth;
  var height = caster.height();
  var parentHeight = caster.parent().height();
  var percentHeight = 100*height/parentHeight;
  var left = caster.css('left');
  left = pxAsInt(left);
  var percentLeft = 100*left/parentWidth;
  var shadows = $('.shadow');
  var transformVal = caster.css('transform');
  if(transformVal === 'translateX(0)' || transformVal === null) {
    shadows.css({'width': asPercent(percentWidth), 'top': asPercent(percentHeight), 'left': asPercent(percentLeft)});
  } else {
    shadows.css({'width': asPercent(percentWidth), 'top': asPercent(percentHeight), 'left': asPercent(percentLeft), 'transform': transformVal});
  }
}

function asPercent(i) {
  return i.toString()+'%';
}

function pxAsInt(px) {
  return parseInt(px.substring(0,px.length-2));
}