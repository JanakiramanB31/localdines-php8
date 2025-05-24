(function($) {

    // var $controller_url =  <?php echo $required_url; ?>;
   
    /**
  
     * Review stars
  
     */
  
      $(".food-review-rate a:nth-of-type(1)").hover(function() {
      console.log("hovered 1");
      $(".fa-star").css("background-color", "#dcdce6");
      $(this).css("background-color", "red");
    },
    function() {
      var $rate = $("#ratingJs").val();
      if ($rate == 1 ) {
        $(".food-review-rate a:nth-of-type(1)").css("background-color", "red");
      } else if ($rate == 2) {
        $(".food-review-rate a:nth-of-type(2)").css("background-color", "orange");
        $(".food-review-rate a:nth-of-type(2)").siblings(".star-one").css("background-color", "orange");
      } else if ($rate == 3) {
        $(".food-review-rate a:nth-of-type(3)").css("background-color", "#fbce00");
        $(".food-review-rate a:nth-of-type(3)").siblings(".star-one,.star-two").css("background-color", "#fbce00");
      } else if ($rate == 4) {
        $(".food-review-rate a:nth-of-type(4)").css("background-color", "#73cf11");
        $(".food-review-rate a:nth-of-type(4)").siblings(".star-one,.star-two,.star-three").css("background-color", "#73cf11");
      } else if ($rate == 5) {
        $(".food-review-rate a:nth-of-type(5)").css("background-color", "#42b67a");
        $(".food-review-rate a:nth-of-type(5)").siblings().css("background-color", "#42b67a");
      } else {
        $(this).css("background-color", "#dcdce6");
        $(this).siblings().css("background-color", "#dcdce6");
      }
      
    });
    $(".food-review-rate a:nth-of-type(2)").hover(function() {
      $(".fa-star").css("background-color", "#dcdce6");
      $(this).css("background-color", "orange");
      $(this).siblings(".star-one").css("background-color", "orange");
    },function() {
      var $rate = $("#ratingJs").val();
      if ($rate == 1 ) {
        $(".food-review-rate a:nth-of-type(1)").css("background-color", "red");
        $(".food-review-rate a:nth-of-type(1)").siblings().css("background-color", "#dcdce6");
      } else if ($rate == 2) {
        $(".food-review-rate a:nth-of-type(2)").css("background-color", "orange");
        $(".food-review-rate a:nth-of-type(2)").siblings(".star-one").css("background-color", "orange");
      } else if ($rate == 3) {
        $(".food-review-rate a:nth-of-type(3)").css("background-color", "#fbce00");
        $(".food-review-rate a:nth-of-type(3)").siblings(".star-one,.star-two").css("background-color", "#fbce00");
      } else if ($rate == 4) {
        $(".food-review-rate a:nth-of-type(4)").css("background-color", "#73cf11");
        $(".food-review-rate a:nth-of-type(4)").siblings(".star-one,.star-two,.star-three").css("background-color", "#73cf11");
      } else if ($rate == 5) {
        $(".food-review-rate a:nth-of-type(5)").css("background-color", "#42b67a");
        $(".food-review-rate a:nth-of-type(5)").siblings().css("background-color", "#42b67a");
      } else {
        $(this).css("background-color", "#dcdce6");
        $(this).siblings().css("background-color", "#dcdce6");
      }
    });
    $(".food-review-rate a:nth-of-type(3)").hover(function() {
      $(".fa-star").css("background-color", "#dcdce6");
      $(this).css("background-color", "#fbce00");
      $(this).siblings(".star-one,.star-two").css("background-color", "#fbce00");
    },function() {
      var $rate = $("#ratingJs").val();
      if ($rate == 1 ) {
        $(".food-review-rate a:nth-of-type(1)").css("background-color", "red");
        $(".food-review-rate a:nth-of-type(1)").siblings().css("background-color", "#dcdce6");
      } else if ($rate == 2) {
        $(".food-review-rate a:nth-of-type(2)").css("background-color", "orange");
        $(".food-review-rate a:nth-of-type(2)").siblings(".star-one").css("background-color", "orange");
        $(this).css("background-color", "#dcdce6");
      } else if ($rate == 3) {
        $(".food-review-rate a:nth-of-type(3)").css("background-color", "#fbce00");
        $(".food-review-rate a:nth-of-type(3)").siblings(".star-one,.star-two").css("background-color", "#fbce00");
      } else if ($rate == 4) {
        $(".food-review-rate a:nth-of-type(4)").css("background-color", "#73cf11");
        $(".food-review-rate a:nth-of-type(4)").siblings(".star-one,.star-two,.star-three").css("background-color", "#73cf11");
      } else if ($rate == 5) {
        $(".food-review-rate a:nth-of-type(5)").css("background-color", "#42b67a");
        $(".food-review-rate a:nth-of-type(5)").siblings().css("background-color", "#42b67a");
      } else {
        $(this).css("background-color", "#dcdce6");
        $(this).siblings().css("background-color", "#dcdce6");
      }
    })
    $(".food-review-rate a:nth-of-type(4)").hover(function() {
      $(".fa-star").css("background-color", "#dcdce6");
      $(this).css("background-color", "#73cf11");
      $(this).siblings(".star-one,.star-two,.star-three").css("background-color", "#73cf11");
    },function() {
      var $rate = $("#ratingJs").val();
      if ($rate == 1 ) {
        $(".food-review-rate a:nth-of-type(1)").css("background-color", "red");
        $(".food-review-rate a:nth-of-type(1)").siblings().css("background-color", "#dcdce6");
      } else if ($rate == 2) {
        $(".food-review-rate a:nth-of-type(2)").css("background-color", "orange");
        $(".food-review-rate a:nth-of-type(2)").siblings(".star-one").css("background-color", "orange");
        $(".food-review-rate a:nth-of-type(3)").css("background-color", "#dcdce6");
        $(this).css("background-color", "#dcdce6");
      } else if ($rate == 3) {
        $(".food-review-rate a:nth-of-type(3)").css("background-color", "#fbce00");
        $(".food-review-rate a:nth-of-type(3)").siblings(".star-one,.star-two").css("background-color", "#fbce00");
        $(this).css("background-color", "#dcdce6");
      } else if ($rate == 4) {
        $(".food-review-rate a:nth-of-type(4)").css("background-color", "#73cf11");
        $(".food-review-rate a:nth-of-type(4)").siblings(".star-one,.star-two,.star-three").css("background-color", "#73cf11");
      } else if ($rate == 5) {
        $(".food-review-rate a:nth-of-type(5)").css("background-color", "#42b67a");
        $(".food-review-rate a:nth-of-type(5)").siblings().css("background-color", "#42b67a");
      } else {
        $(this).css("background-color", "#dcdce6");
        $(this).siblings().css("background-color", "#dcdce6");
      }
    })
    $(".food-review-rate a:nth-of-type(5)").hover(function() {
      $(this).css("background-color", "#42b67a");
      $(this).siblings().css("background-color", "#42b67a");
    },function() {
      var $rate = $("#ratingJs").val();
      if ($rate == 1 ) {
        $(".food-review-rate a:nth-of-type(1)").css("background-color", "red");
        $(".food-review-rate a:nth-of-type(1)").siblings().css("background-color", "#dcdce6");
      } else if ($rate == 2) {
        $(".food-review-rate a:nth-of-type(2)").css("background-color", "orange");
        $(".food-review-rate a:nth-of-type(2)").siblings(".star-one").css("background-color", "orange");
        $(".food-review-rate a:nth-of-type(3)").css("background-color", "#dcdce6");
        $(".food-review-rate a:nth-of-type(4)").css("background-color", "#dcdce6");
        $(this).css("background-color", "#dcdce6");
      } else if ($rate == 3) {
        $(".food-review-rate a:nth-of-type(3)").css("background-color", "#fbce00");
        $(".food-review-rate a:nth-of-type(3)").siblings(".star-one,.star-two").css("background-color", "#fbce00");
        $(".food-review-rate a:nth-of-type(4)").css("background-color", "#dcdce6");
        $(this).css("background-color", "#dcdce6");
      } else if ($rate == 4) {
        $(".food-review-rate a:nth-of-type(4)").css("background-color", "#73cf11");
        $(".food-review-rate a:nth-of-type(4)").siblings(".star-one,.star-two,.star-three").css("background-color", "#73cf11");
        $(this).css("background-color", "#dcdce6");
      } else if ($rate == 5) {
        $(".food-review-rate a:nth-of-type(5)").css("background-color", "#42b67a");
        $(".food-review-rate a:nth-of-type(5)").siblings().css("background-color", "#42b67a");
      } else {
        $(this).css("background-color", "#dcdce6");
        $(this).siblings().css("background-color", "#dcdce6");
      }
    });
  
    $("#review-rate .food-review-rate a:nth-of-type(1)").click(function() {
      $(".fa-star").css("background-color", "#dcdce6");
      $(".star-one").css("background-color", "red");
      $("#ratingJs").val(1);
    });
    $("#review-rate  .food-review-rate a:nth-of-type(2)").click(function() {
      $(".fa-star").css("background-color", "#dcdce6");
      $(".star-one,.star-two").css("background-color", "orange");
      $("#ratingJs").val(2);
    });
    $("#review-rate  .food-review-rate a:nth-of-type(3)").click(function() {
      $(".fa-star").css("background-color", "#dcdce6");
      $(".star-one,.star-two,.star-three").css("background-color", "#fbce00");
      $("#ratingJs").val(3);
    });
    $("#review-rate  .food-review-rate a:nth-of-type(4)").click(function() {
      $(".fa-star").css("background-color", "#dcdce6");
      $(".star-one,.star-two,.star-three,.star-four").css("background-color", "#73cf11");
      $("#ratingJs").val(4);
    });
    $("#review-rate  .food-review-rate a:nth-of-type(5)").click(function() {
      $(".star-one,.star-two,.star-three,.star-four,.star-five").css("background-color", "#42b67a");
      $("#ratingJs").val(5);
    });
    
    /*-------------------
        REVIEW PAGE
      --------------------*/

    var $rate = $("#ratingJs").val();
    if ($rate == 1) {
      $(".star-one").css("background-color","red");
    } else if ($rate == 2) {
      $(".star-one,.star-two").css("background-color","orange");
    } else if ($rate == 3) {
      $(".star-one,.star-two,.star-three").css("background-color","#fbce00");
    } else if ($rate == 4) {
      $(".star-one,.star-two,.star-three,.star-four").css("background-color","#73cf11");
    } else if ($rate == 5){
      $(".food-review-rate a").css("background-color","#42b67a")
    }
    $("#review-msg textarea").on("keyup",function(e) {
      var $review = $(this).val();
      var $reviewTitle = $review.split(". ");
      
      var len = $review.replace(/\s+/g, '').length;
      if (len > 15) {
        
        $("[name=review_title]").val($review.substring(0, 8)+"..."); 
      } else {
        if ($reviewTitle.length > 1) {
          $("[name=review_title]").val($reviewTitle[0]);
        } else {
          $("[name=review_title]").val($review); 
        }
        
      }
      
      
      
    })
    // $("#review-rate .food-review-rate").on("click", function() {
    //   $("#hiddenDiv").css("display","block");
    // })
    $("#review_terms input").click(function() {
      console.log("clicked the checkbox...");
      if ($(this).prop("checked")) {
        $("#review-terms_error").css("display","none");
      } else {
        $("#review-terms_error").css("display","block");
      }
    })

    /*-------------------
        VALIDATION
      --------------------*/

      $("#reviewFrm").on("submit", function() {
        var valid = true,
            error = 0;
    
        $inputs = $(".form-group");
        $inputs.each(function(){
            if ($(this).hasClass("has-error")) {
                error = error + 1;
            }
        })
        if ($("#review_terms input").prop("checked")) {
             valid = true;
             $("#review-terms_error").css("display","none");
        } else {
            error = error + 1;
            $("#review-terms_error").css("display","block");
        }
    
        if (error > 0) {
            valid = false;
        }
        return valid;
    })

    /*-------------------
        SEARCH BAR
      --------------------*/

    // $(".search-btn").on("click", function(e)  {
     
    //   e.preventDefault();
    //   var $keyword = $("input[type='search']").val();
    //   $page = $("input[name=page_type]").val();
    //   $('.fdSearchResults').html('');
    //   if ($keyword != '') {
    //     $.ajax({
    //       type: 'POST',
    //       url: $controller_url+'index.php?controller=pjFrontPublic&action=pjActionGetSearchResults',
    //       data: {key: $keyword,page: $page},
    //       success: function (html) {
    //         $("#search").css('display', 'block');
    //         $(".fdSearchResults").append(html);
    //       }
    //     });
    //   }
    // })
     
    // $("input[type='search']").on('keyup', function(e) {
    //   if (e.keyCode == 13) {
    //     $keyword = $(this).val();
    //     $page = $("input[name=page_type]").val();
    //     $('.fdSearchResults').html('');
    //     if ($keyword != '') {
    //       $.ajax({
    //         type: 'POST',
    //         url: $controller_url+'index.php?controller=pjFrontPublic&action=pjActionGetSearchResults',
    //         data: {key: $keyword, page: $page},
    //         success: function (html) {
    //           $("#search").css('display', 'block');
    //           $(".fdSearchResults").append(html);
    //         }
    //       });
    //     }
    //   }
    // })

    /**
  
     * Search bar
  
     */

    //console.log($(".food-item-desc"));
    
    // if($(".food-item-desc").length > 0) {
    //   $(".search-me").css("display","block");
    // } else {
    //   $(".search-me").css("display","none");
    //   if ($("#searchInput-group").css("display") == "flex") {
    //     $("#searchInput-group").css("display", "none");
        
    //     $(".logo").css("display", "block");
    //   }
    // }

    // $(window).resize(function() {
    //   if ($(window).width() <= 767) {
    //     $(".search-me").on("click", function() {
    //       $("#searchInput-group").css("display","flex");
    //       //$(this).css("display","none");
    //       $(this).fadeOut("slow");
    //       $("#searchInput-group").css("width", "100%");
    //       //$(".logo").css("display", "none");
    //       $(".logo").fadeOut("slow");
    //     })
    //     $(".fa-close").on("click", function() {
    //       $("input[type='search']").val('');
    //       $('.fdSearchResults').html('');
    //       $("#searchInput-group").css('display', 'none');
    //       $(".search-me").css("display","block");
    //       $("#search").css('display', 'none');
    //       $(".logo").css("display", "block");
    //     })

    //   } else {
    //     $(".search-me").on("click", function() {
    //       $("#searchInput-group").css("display","flex");
    //       $(this).css("display","none");
    //     })
    //     $(".fa-close").on("click", function() {
    //       $("input[type='search']").val('');
    //       $('.fdSearchResults').html('');
    //       $("#searchInput-group").css('display', 'none');
    //       $(".search-me").css("display","block");
    //       $("#search").css('display', 'none');
    //     })
    //   }
    // })
    // $(".search-me").on("click", function() {
    //   $("#searchInput-group").css("display","flex");
    //   $("#searchInput-group").css("width", "200px");
    //   $(this).css("display","none");
    //   if ($(window).width() <= 767) {
    //     $("#searchInput-group").css("width", "100%");
    //     //transition: width 2s
    //     //$("#searchInput-group").addClass("toggle-search");
    //     //$("#searchInput-group").css("transition", "width 2s");
    //     $(".logo").css("display", "none");
    //   }
      
    // })
    // $(".fa-close").on("click", function() {
    //   $("input[type='search']").val('');
    //   $('.fdSearchResults').html('');
    //   $("#searchInput-group").css('display', 'none');
    //   $("#searchInput-group").css("width", "0px");
    //   $(".search-me").css("display","block");
    //   $("#search").css('display', 'none');
    //   if ($(window).width() <= 767) {
    //     $(".logo").css("display", "block");
    //   }
    // })
  

    $("#profile").click(function() {
      console.log("you clicked my profile");
    })
    

    /*-------------------
        REVIEW PAGE
      --------------------*/

    // $("#all_reviews").on("click", function() {
    //   var $p_id = $(this).data("product");
    //   $.ajax({
    //     type: 'POST',
    //     url: 'index.php?controller=pjFrontPublic&action=pjActionProductRatings',
    //     data: {product: $p_id},
    //     success: function (data) {
    //       $("#review_Container").html(data);
    //     }
    //   });
    // })
    
    
    
  
  })(jQuery);