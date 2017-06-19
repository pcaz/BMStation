/*(function($){
      $.fn.setinterval = function(){
         $(".slideshow ul").animate({marginLeft:-350},800,function(){
            $("this").css({marginLeft:0}).find("li:last").after($(this).find("li:first"));
         }, 3500);
      }
})(jQuery);


(function($){  
  setinterval(function(){  
    $(".slideshow ul li:first-child").animate({"margin-left": -350}, 800, function(){  
        $(this).css("margin-left",0).appendTo(".slideshow ul");  
    });  
  }, 3500);  
})(jQuery);
*/
$(function(){
	
      setInterval(function(){
         $(".slideshow ul").animate({marginLeft:-300},800,function(){
            $(this).css({marginLeft:0}).find("li:last").after($(this).find("li:first"));
         })
      }, 3500);
   });
