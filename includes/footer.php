

<section>
   <div class="row" >
      <div class="large-7 columns">
         <img src="includes/img/logo-white.png" class="logo-dull">
         <div class="row">
            <div class="medium-4 columns">
               <address>
                  Rank and Review LLC <br>
                  123 Main Street <br>
                  Seaford, DE 19971
               </address>
            </div>
            <div class="medium-4 columns">
               <ul class="no-bullet">
                  <li> <a href="">About Us</a> </li>
                  <li> <a href="">Privacy Policy</a> </li>
                  <li> <a href="">Terms & Condition</a> </li>
               </ul>
            </div>
            <div class="medium-4 columns">
               <ul class="no-bullet">
                  <li> <a href="">For Amazon Sellers</a> </li>
                  <li> <a href="">Blog</a> </li>
                  <li> <a href="">Contact Us</a> </li>
               </ul>
            </div>
         </div>
      </div>
      <div class="large-5 columns">
         <div class="rnr-news">
            <h3 class="text-thin text-white"> <i class="fa fa-envelope rpd-10"></i> News Letter</h3>
            <p>Latest News & Updates Directly To Your Inbox</p>
            <div class="row">
               <div class="large-12 medium-7 columns">
                  <div class="row collapse">
                     <div class="small-8 columns">
                        <input type="text" placeholder="Your Email" class="preinput">
                     </div>
                     <div class="small-4 columns">
                        <a href="#" class="button btn-yellow postfix">Subscribe</a>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</section>
<hr class="dark-hr">
<section>
   <div class="row" >
   <div class="medium-6 columns">
      <ul class="list-inline  text-center-mobile">
         <li><a href="" target="_blank"><i class="fa fa-2x fa-facebook-square"></i></a></li>
         <li><a href="" target="_blank"><i class="fa fa-2x fa-twitter-square"></i></a></li>
         <li><a href="" target="_blank"><i class="fa fa-2x fa-linkedin-square"></i></a></li>
      </ul>
   </div>
   <div class="medium-6 columns">
      <p class="text-right text-center-mobile">
         &copy; 2016 Rank and Reviews. All Rights Reserved
      </p>
   </div>
</section>



<!-- all script file -->

<script src="includes/js/foundation.min.js"></script>
<script src="includes/js/foundation-datepicker.min.js"></script>
<script src="includes/slick/slick.min.js"></script>
<script src="includes/js/slimscroll.js"></script>
<script src="includes/js/wow.min.js"></script>


<!-- other js -->
<script type="text/javascript">
   jQuery(document).ready(function ($) {
     $.noConflict();
       $(document).foundation();
   });
</script>
   

<!-- Responcive table js -->
<script type="text/javascript">
   jQuery(document).ready(function ($) {
      $.noConflict();
   var switched = false;
   var updateTables = function() {
     if (($(window).width() < 767) && !switched ){
       switched = true;
       $("table.responsive").each(function(i, element) {
         splitTable($(element));
       });
       return true;
     }
     else if (switched && ($(window).width() > 767)) {
       switched = false;
       $("table.responsive").each(function(i, element) {
         unsplitTable($(element));
       });
     }
   };
    
   $(window).load(updateTables);
   $(window).on("redraw",function(){switched=false;updateTables();}); // An event to listen for
   $(window).on("resize", updateTables);
    
   
   function splitTable(original)
   {
     original.wrap("<div class='table-wrapper' />");
     
     var copy = original.clone();
     copy.find("td:not(:first-child), th:not(:first-child)").css("display", "none");
     copy.removeClass("responsive");
     
     original.closest(".table-wrapper").append(copy);
     copy.wrap("<div class='pinned' />");
     original.wrap("<div class='scrollable' />");
   
     setCellHeights(original, copy);
   }
   
   function unsplitTable(original) {
     original.closest(".table-wrapper").find(".pinned").remove();
     original.unwrap();
     original.unwrap();
   }
   
   function setCellHeights(original, copy) {
     var tr = original.find('tr'),
         tr_copy = copy.find('tr'),
         heights = [];
   
     tr.each(function (index) {
       var self = $(this),
           tx = self.find('th, td');
   
       tx.each(function () {
         var height = $(this).outerHeight(true);
         heights[index] = heights[index] || 0;
         if (height > heights[index]) heights[index] = height;
       });
   
     });
   
     tr_copy.each(function (index) {
       $(this).height(heights[index]);
     });
   }
   
   });
   
</script>
<!-- Responcive table js END-->





<script type="text/javascript">
   jQuery(document).ready(function ($) {
     $.noConflict();
   
       /*home silder js*/

       $('#product-slider').slick({
         dots: true,
         arrows: false,
         autoplay: false,
         infinite: true,
         speed: 300,
         slidesToShow: 5,
         slidesToScroll: 1,
         responsive: [
           {
             breakpoint: 1024,
             settings: {
               slidesToShow: 3,
               slidesToScroll: 1,
               infinite: true,
               dots: true
             }
           },
           {
             breakpoint: 768,
             settings: {
               slidesToShow: 2,
               slidesToScroll: 1
             }
           },
           {
             breakpoint: 480,
             settings: {
               slidesToShow: 1,
               slidesToScroll: 1
             }
           }
           
         ]
       });

        $('#quote-slider').slick({
  slidesToShow: 1,
  slidesToScroll: 1,
  arrows: false,
  dots: true,
  autoplay: true,
  infinite: true
  
});
   
    /*home silder js END*/
   


   /*menu js*/  
       $(window).scroll(function() {
         if($(this).scrollTop() > 100)  
         {
           $('.menu-outer').addClass('has-bg');
         } else {
           $('.menu-outer').removeClass('has-bg');
       
         }
       });
       
       
       $(".animate-bar").click(function(){
            if(  $(".menu-outer").hasClass("has-bg")) {
            } 
            else{
             $(".menu-outer").toggleClass("has-bg");
            }
       });
   
   /*menu js END*/  
   
            
         /*uSER ACCOUNT js*/

       $("#filter-btn").click(function(){
       
         $("#filter-btn").toggleClass("open");
         $("#user-src-filter").toggle();
         
       
       });
       
       $("#user-nav .yo-collapse-icon").click(function(){
       
          $('.yo-link-wrapper').toggleClass("open");
       
       });
       
       $("#user-nav .has-child").click(function(event){
         event.stopPropagation();
         $(this).find('.child-menu').toggle();
         
       });
         
       $(document).click( function(){
            $('.child-menu').hide();
        });
      
      /*uSER ACCOUNT js END*/
   
   
   
   
   });
</script>
   
   


<!-- WOW JS -->
<script>
   var wow = new WOW(
   {
    boxClass:     'wow',      // animated element css class (default is wow)
    animateClass: 'animated', // animation css class (default is animated)
    offset:       0,          // distance to the element when triggering the animation (default is 0)
    mobile:       false       // trigger animations on mobile devices (true is default)
   }
   );
   wow.init();
</script>
   
<!-- sMLIN SCROLL JS -->

<script>
   jQuery(document).ready(function ($) {
     var element = document.querySelectorAll('.slimScroll');
   
       // Apply slim scroll plugin
       var one = new slimScroll(element[0], {
           'wrapperClass': 'scroll-wrapper unselectable mac',
           'scrollBarContainerClass': 'scrollBarContainer',
           'scrollBarContainerSpecialClass': 'animate',
           'scrollBarClass': 'scroll',
           'keepFocus': false
       });
   
       var one = new slimScroll(element[1], {
           'wrapperClass': 'scroll-wrapper unselectable mac',
           'scrollBarContainerClass': 'scrollBarContainer',
           'scrollBarContainerSpecialClass': 'animate',
           'scrollBarClass': 'scroll',
           'keepFocus': false
       });
   
   });
</script>
   
   
   
   
   
   
   
   
   
    
   
   

