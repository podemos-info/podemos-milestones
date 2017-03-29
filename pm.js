jQuery(document).ready(function() {
    jQuery(".accordion-heading").on("click",function() {
      if(jQuery(this).children("i").hasClass("fa-angle-down")){
        jQuery(this).children("i").removeClass("fa-angle-down");
	jQuery(this).children("i").addClass("fa-angle-up");
      }
      else{
	jQuery(this).children("i").removeClass("fa-angle-up");
	jQuery(this).children("i").addClass("fa-angle-down");
      }
    });
});