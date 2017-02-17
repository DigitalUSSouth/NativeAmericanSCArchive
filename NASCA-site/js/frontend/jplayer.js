//<!-- //for old browsers

  $(document).ready(function(){
    $("#jquery_jplayer_1").jPlayer({
      ready: function() {
        $(this).jPlayer("setMedia", {
          title: "Lula Samuel Henrietta Beck. August 11, 1982. Catawba Indian Nation.",
          mp3: "../db/data/oralhistory/LB-Aug-1982_96kbs.mp3"
        });
      },
      cssSelectorAncestor: "#jp_container_1",
      swfPath: "../js",
      supplied: "mp3",
      useStateClassSkin: true,
      autoBlur: false,
      smoothPlayBar: true,
      keyEnabled: true,
      remainingDuration: true,
      toggleDuration: true
    });
  });

//for old browsers -->