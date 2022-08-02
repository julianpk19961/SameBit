$(document).ready(function() {

    $("#fingerprint").click(function() {
      $(this).addClass("exito");
    });
  
    $(".formulario .item:nth-child(1) form").submit(function( event ) {
      $(".formulario .slide").css("left","-100%");
      $(".item .deni").text($("#dni").val());
      event.preventDefault();
    });
  
    $(".item:nth-child(2) > .clear_r a").click(function() {
      $(".formulario .slide").css("left","-200%");
      event.preventDefault();
    });
  
    $(".formulario #back").click(function() {
      $(".formulario .slide").css("left","0%");
    });
  
    $(".actividades .item_act header h3").click(function() {
      $(this).closest(".item_act").toggleClass("expand");
    });
  
    $(".zumba .horario tr:not(:first-child)").click(function() {
      $(".zumba .clear_r a").removeClass("inactive");
      $(this).addClass("selected");
      $(".zumba .horario").addClass("blocked");
    });
  
    $(".pilates .horario tr:not(:first-child)").click(function() {
      $(".pilates .clear_r a").removeClass("inactive");
      $(this).addClass("selected");
      $(".pilates .horario").addClass("blocked");
    });
  
    $(".actividades .item_act .clear_r a").click(function() {
      $(this).addClass("inactive");
      var item = $(this).closest(".item_act");
      if (item.not(".selecc")) {
        var x = parseInt($("#import").text());
        var y = parseInt($(this).attr("alt"));
        $("#import").text(x+y);
        item.addClass("selecc").removeClass("expand");
      };
      if ($("#import").text() != '0') {
        $(".item > .clear_r a").removeClass("inactive");
      };
    });
  
    $(".actividades .item_act header a").click(function() {
      $(".horario").removeClass("blocked");
      $(".horario tr").removeClass("selected");
      $(this).closest(".item_act").removeClass("selecc");
      $(this).closest(".item_act:not(.zumba,.pilates)").find(".clear_r a").removeClass("inactive");
      var x = parseInt($("#import").text());
      var y = parseInt($(this).attr("alt"));
      $("#import").text(x-y);
      if ($("#import").text() == '0') {
        $(".item > .clear_r a").addClass("inactive");
      };
    });
  
  });
  