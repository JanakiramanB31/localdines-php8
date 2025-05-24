(function($) {
/**
  
     * Review stars
  
     */
  
    
 $(".food-review-rate a:nth-of-type(1)").hover(function() {
      
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

  /***********************************************/

  $(".stars-group a:nth-of-type(1)").hover(function() {
      
    $(".stars-group .fa-star").css("background-color", "#dcdce6");
    $(this).css("background-color", "red");
  },
  function() {
    var $rate = $("#ratingJs").val();
    if ($rate == 1 ) {
      $(".stars-group a:nth-of-type(1)").css("background-color", "red");
    } else if ($rate == 2) {
      $(".stars-group a:nth-of-type(2)").css("background-color", "orange");
      $(".stars-group a:nth-of-type(2)").siblings(".star-one").css("background-color", "orange");
    } else if ($rate == 3) {
      $(".stars-group a:nth-of-type(3)").css("background-color", "#fbce00");
      $(".stars-group a:nth-of-type(3)").siblings(".star-one,.star-two").css("background-color", "#fbce00");
    } else if ($rate == 4) {
      $(".stars-group a:nth-of-type(4)").css("background-color", "#73cf11");
      $(".stars-group a:nth-of-type(4)").siblings(".star-one,.star-two,.star-three").css("background-color", "#73cf11");
    } else if ($rate == 5) {
      $(".stars-group a:nth-of-type(5)").css("background-color", "#42b67a");
      $(".stars-group a:nth-of-type(5)").siblings().css("background-color", "#42b67a");
    } else {
      $(this).css("background-color", "#dcdce6");
      $(this).siblings().css("background-color", "#dcdce6");
    }
    
  });
  $(".stars-group a:nth-of-type(2)").hover(function() {
    $(".stars-group .fa-star").css("background-color", "#dcdce6");
    $(this).css("background-color", "orange");
    $(this).siblings(".star-one").css("background-color", "orange");
  },function() {
    var $rate = $("#ratingJs").val();
    if ($rate == 1 ) {
      $(".stars-group a:nth-of-type(1)").css("background-color", "red");
      $(".stars-group a:nth-of-type(1)").siblings().css("background-color", "#dcdce6");
    } else if ($rate == 2) {
      $(".stars-group a:nth-of-type(2)").css("background-color", "orange");
      $(".stars-group a:nth-of-type(2)").siblings(".star-one").css("background-color", "orange");
    } else if ($rate == 3) {
      $(".stars-group a:nth-of-type(3)").css("background-color", "#fbce00");
      $(".stars-group a:nth-of-type(3)").siblings(".star-one,.star-two").css("background-color", "#fbce00");
    } else if ($rate == 4) {
      $(".stars-group a:nth-of-type(4)").css("background-color", "#73cf11");
      $(".stars-group a:nth-of-type(4)").siblings(".star-one,.star-two,.star-three").css("background-color", "#73cf11");
    } else if ($rate == 5) {
      $(".stars-group a:nth-of-type(5)").css("background-color", "#42b67a");
      $(".stars-group a:nth-of-type(5)").siblings().css("background-color", "#42b67a");
    } else {
      $(this).css("background-color", "#dcdce6");
      $(this).siblings().css("background-color", "#dcdce6");
    }
  });
  $(".stars-group a:nth-of-type(3)").hover(function() {
    $(".stars-group .fa-star").css("background-color", "#dcdce6");
    $(this).css("background-color", "#fbce00");
    $(this).siblings(".star-one,.star-two").css("background-color", "#fbce00");
  },function() {
    var $rate = $("#ratingJs").val();
    if ($rate == 1 ) {
      $(".stars-group a:nth-of-type(1)").css("background-color", "red");
      $(".stars-group a:nth-of-type(1)").siblings().css("background-color", "#dcdce6");
    } else if ($rate == 2) {
      $(".stars-group a:nth-of-type(2)").css("background-color", "orange");
      $(".stars-group a:nth-of-type(2)").siblings(".star-one").css("background-color", "orange");
      $(this).css("background-color", "#dcdce6");
    } else if ($rate == 3) {
      $(".stars-group a:nth-of-type(3)").css("background-color", "#fbce00");
      $(".stars-group a:nth-of-type(3)").siblings(".star-one,.star-two").css("background-color", "#fbce00");
    } else if ($rate == 4) {
      $(".stars-group a:nth-of-type(4)").css("background-color", "#73cf11");
      $(".stars-group a:nth-of-type(4)").siblings(".star-one,.star-two,.star-three").css("background-color", "#73cf11");
    } else if ($rate == 5) {
      $(".stars-group a:nth-of-type(5)").css("background-color", "#42b67a");
      $(".stars-group a:nth-of-type(5)").siblings().css("background-color", "#42b67a");
    } else {
      $(this).css("background-color", "#dcdce6");
      $(this).siblings().css("background-color", "#dcdce6");
    }
  })
  $(".stars-group a:nth-of-type(4)").hover(function() {
    $(".stars-group .fa-star").css("background-color", "#dcdce6");
    $(this).css("background-color", "#73cf11");
    $(this).siblings(".star-one,.star-two,.star-three").css("background-color", "#73cf11");
  },function() {
    var $rate = $("#ratingJs").val();
    if ($rate == 1 ) {
      $(".stars-group a:nth-of-type(1)").css("background-color", "red");
      $(".stars-group a:nth-of-type(1)").siblings().css("background-color", "#dcdce6");
    } else if ($rate == 2) {
      $(".stars-group a:nth-of-type(2)").css("background-color", "orange");
      $(".stars-group a:nth-of-type(2)").siblings(".star-one").css("background-color", "orange");
      $(".stars-group a:nth-of-type(3)").css("background-color", "#dcdce6");
      $(this).css("background-color", "#dcdce6");
    } else if ($rate == 3) {
      $(".stars-group a:nth-of-type(3)").css("background-color", "#fbce00");
      $(".stars-group a:nth-of-type(3)").siblings(".star-one,.star-two").css("background-color", "#fbce00");
      $(this).css("background-color", "#dcdce6");
    } else if ($rate == 4) {
      $(".stars-group a:nth-of-type(4)").css("background-color", "#73cf11");
      $(".stars-group a:nth-of-type(4)").siblings(".star-one,.star-two,.star-three").css("background-color", "#73cf11");
    } else if ($rate == 5) {
      $(".stars-group a:nth-of-type(5)").css("background-color", "#42b67a");
      $(".stars-group a:nth-of-type(5)").siblings().css("background-color", "#42b67a");
    } else {
      $(this).css("background-color", "#dcdce6");
      $(this).siblings().css("background-color", "#dcdce6");
    }
  })
  $(".stars-group a:nth-of-type(5)").hover(function() {
    $(this).css("background-color", "#42b67a");
    $(this).siblings().css("background-color", "#42b67a");
  },function() {
    var $rate = $("#ratingJs").val();
    if ($rate == 1 ) {
      $(".stars-group a:nth-of-type(1)").css("background-color", "red");
      $(".stars-group a:nth-of-type(1)").siblings().css("background-color", "#dcdce6");
    } else if ($rate == 2) {
      $(".stars-group a:nth-of-type(2)").css("background-color", "orange");
      $(".stars-group a:nth-of-type(2)").siblings(".star-one").css("background-color", "orange");
      $(".stars-group a:nth-of-type(3)").css("background-color", "#dcdce6");
      $(".stars-group a:nth-of-type(4)").css("background-color", "#dcdce6");
      $(this).css("background-color", "#dcdce6");
    } else if ($rate == 3) {
      $(".stars-group a:nth-of-type(3)").css("background-color", "#fbce00");
      $(".stars-group a:nth-of-type(3)").siblings(".star-one,.star-two").css("background-color", "#fbce00");
      $(".stars-group a:nth-of-type(4)").css("background-color", "#dcdce6");
      $(this).css("background-color", "#dcdce6");
    } else if ($rate == 4) {
      $(".stars-group a:nth-of-type(4)").css("background-color", "#73cf11");
      $(".stars-group a:nth-of-type(4)").siblings(".star-one,.star-two,.star-three").css("background-color", "#73cf11");
      $(this).css("background-color", "#dcdce6");
    } else if ($rate == 5) {
      $(".stars-group a:nth-of-type(5)").css("background-color", "#42b67a");
      $(".stars-group a:nth-of-type(5)").siblings().css("background-color", "#42b67a");
    } else {
      $(this).css("background-color", "#dcdce6");
      $(this).siblings().css("background-color", "#dcdce6");
    }
  });
})(jQuery);