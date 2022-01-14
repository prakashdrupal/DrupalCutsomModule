jQuery(function ($) {
$( document ).ready(function() {
  var owl = $('#module-slider');
  owl.owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    dots:true,
    items:1,
    //autoplay:true,
    //video: true,
    //autoplayTimeout:2000,
    autoplayHoverPause:true
  }); 

  owl.on('translate.owl.carousel',function(e){
    $('.owl-item video').each(function(){
    $(this).get(0).pause();
    $('.owl-item.active #pause').hide();
    $('.owl-item.active #play').show();
    });
    /*youtybe stop */
    var currentSlide, player, command;
    currentSlide = $('.owl-item.active');
    player = currentSlide.find(".item iframe").get(0);
    command = {
        "event": "command",
        "func": "pauseVideo"
    };
    if (player != undefined) {
      player.contentWindow.postMessage(JSON.stringify(command), "*");
    }

  });
  owl.on('translated.owl.carousel',function(e){
    if($('.owl-item.active video').length){
      if($('.owl-item.active > .item').attr('data-videoplay')==1){
        $('.owl-item.active video').get(0).play();
        $('.owl-item.active #pause').show();
        $('.owl-item.active #play').hide();
      }else{
        $('.owl-item.active #pause').hide();
        $('.owl-item.active #play').show();
      }
    }
      /*youtybe stop */
      if($('.owl-item.active > .item').attr('data-videoplay')==1){
        var currentSlide, player, command;
        currentSlide = $('.owl-item.active');
        player = currentSlide.find(".item iframe").get(0);
        command = {
            "event": "command",
            "func": "playVideo"
        };
        if (player != undefined) {
          player.contentWindow.postMessage(JSON.stringify(command), "*");
        }
      }

  });
  if(!isMobile()){
    $('.owl-item .item').each(function(){
      var attr = $(this).attr('data-videosrc');
      
      if (typeof attr !== typeof undefined && attr !== false) {
        //console.log('hit');
        var videosrc = $(this).attr('data-videosrc');
        $(this).prepend('<video controls="" id="bslidervideo" ><source src="'+videosrc+'" type="video/mp4"></video>');
      }
    });
    
    $('.owl-item.active video').attr('autoplay',true).attr('loop',true);
  }
});
});

function isMobile(width) {
  if(width == undefined){
    width = 719;
  }
  if(window.innerWidth <= width) {
    return true;
  } else {
    return false;
  }
}

function play(self) {
  if(jQuery('.owl-item.active video').length){
    jQuery('.owl-item.active video').get(0).play();
     //jQuery(self).css("display", "none");
     jQuery('.owl-item.active #play').addClass("hidden");
     jQuery('.owl-item.active #pause').removeClass('hidden');
    jQuery('.owl-item.active #play').hide();
    jQuery('.owl-item.active #pause').show();
  }  
}

function pause(self) {
  jQuery('.owl-item video').each(function(){
   jQuery(this).get(0).pause();
   //jQuery(self).css("display", "none");
   jQuery('.owl-item.active #play').removeClass('hidden');
    jQuery('.owl-item.active #pause').addClass("hidden");
   jQuery('.owl-item.active #pause').hide();
   jQuery('.owl-item.active #play').show();
  });
}

// Show and hide play and pause button and SHADOW
    var vidClip = document.getElementById("myVideo");
    function playVid() {
      myVideo.play();
      jQuery('.item').addClass('active');
      jQuery('#play').hide();
      jQuery('#pause').show();
    }
    function pauseVid() {
      myVideo.pause();
      jQuery('.item').removeClass('active');
      jQuery('#pause').hide();
      jQuery('#play').show();
    }
   /* $('button').on('click', function () {
      $('.first, .second').toggle();
    });*/
    /*vidClip.onended = function (e) {
      jQuery('.first, .second').toggle();
    };*/

    /*jQuery(document).ready(function() {
        playVid();
    });*/
