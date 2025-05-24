<style>
    .panel_review,
    /* .product-img, */
    .product-info {
        border: 1px solid #ddd;
        border-radius: 5px;
    }
    .panel_Guest {
        margin: 10px 0px;
        text-align: center;
    }
    .panel_Guest img {
        width: 90px;
        height: 90px;
        margin: auto;    
        display: block;
    }
    .panel_review .food-review-rate {
        float: none;
    }
    #page_navigation {
        cursor: pointer;
    }
    .numericButton {
        background-color: #FFFFFF;
        color: #000 !important;
        border: 1px solid #C9C9C9;
        display: inline-block;
        height: 25px;
        line-height: 14px;
        min-width: 14px !important;
        padding: 4px;
        text-align: center;
        text-decoration: none;
    }
    .currentPageButton {
        background-color: #000 !important;
        color: #fff !important;
        border: 1px solid #C9C9C9;
        display: inline-block;
        height: 25px;
        line-height: 14px;
        min-width: 14px !important;
        padding: 4px;
        text-align: center;
        text-decoration: none;
    }
    .nextbutton {
        background-color: #ffb03b !important;
        border: 1px solid #ffb03b;
        border-radius: 5px;
        color: #fff !important;
        display: inline-block;
        height: 25px;
        line-height: 14px;
        padding: 4px;
        text-align: center;
        text-decoration: none;
        width: 85px;
    }
    .previousDisabled,
    .nextDisabled {
        background-color: #eebd76 !important;
        border: 1px solid #eebd76;
        color: #fff !important;
        display: inline-block;
        height: 25px;
        line-height: 14px;
        padding: 4px;
        text-align: center;
        text-decoration: none;
        width: 85px;
        cursor: not-allowed;
    }
    #page_navigation {
        padding-top: 8px;
        margin-bottom: 3rem;
    }
</style>
<?php //echo "<pre>";print_r($tpl['reviews']);exit; ?>
<?php
if(!empty($tpl['product_info'][0]['image']))
{
    $image_path = PJ_INSTALL_URL . $tpl['product_info'][0]['image'];
} 
?>
<div class="container mt-5">
        <div class="row">
            <div class="col-sm-4 col-xs-4 product-img">
                <img src="<?php echo $image_path; ?>" class="img-fluid" alt="Product">
                <!-- <img src="https://b.zmtcdn.com/data/pictures/2/19584192/aa6fd89435d3155df1cd319437d42210.jpg" class="img-fluid" alt="Product"> -->
            </div>
            <div class="col-sm-8 col-xs-8 product-info">
                <div>
                    <h3><?php echo strtoupper($tpl['product_info'][0]['name']); ?></h3>
                    <p><?php echo ucfirst($tpl['product_info'][0]['description']); ?></p>
                </div>
                <div id="tot_rate" class="food-review-rate">
                    <a  class="fa fa-star star-one" aria-hidden="true"></a>
                    <a  class="fa fa-star star-two" aria-hidden="true"></a>
                    <a  class="fa fa-star star-three" aria-hidden="true"></a>
                    <a  class="fa fa-star star-four" aria-hidden="true"></a>
                    <a  class="fa fa-star star-five" aria-hidden="true"></a>
                    <input type='hidden' name="total_review_rate" value=<?php echo $tpl['product_info'][0]['tot_rating']; ?>>
                </div>
                <div>(<?php echo $tpl['product_info'][0]['cnt_reviews']; ?> reviews)</div>
                <div style="margin-bottom: 20px;">
                    <a class="btn btn-review" id="write_review">Write a review...   <i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                </div>
            </div>
        </div>
    </div>
    <input type='hidden' id='current_page' />
    <input type='hidden' id='show_per_page' />
    <input type='hidden' id='nop' />
    <div id="paginate" class="container">

    <?php foreach($tpl['reviews'] as $review) { ?>
       <div class="row my-5 panel_review">
            <div class="col-sm-2 col-xs-4 panel_Guest">
            <img src="https://www.mouthshut.com/Images/COMMON/male-80X80.gif" style="border: 1px solid #c7c6c6;border-radius: 50%;" alt="client">
                <?php if($review['user_type'] == 'guest') { ?>
                    
                
                    <p><?php echo $review['guest_un']; ?></p>
                    <!-- <p>2 reviews</p> -->
                <?php } ?>
            </div>
            <div class="col-sm-10 col-xs-8">
            
                <div><h3><?php if ($review['review_title'] != '') {
                    echo $review['review_title'];
                } else {
                    $comments = ['Bad','Poor','Average','Great','Excellent'];
                    echo $comments[$review['rating']-1];

                } ?></h3></div>
                <div class="food-review-rate">
                    <a  class="fa fa-star star-one" aria-hidden="true" data-rate ="<?php echo $review['rating']; ?>" ></a>
                    <a  class="fa fa-star star-two" aria-hidden="true" data-rate ="<?php echo $review['rating']; ?>"></a>
                    <a  class="fa fa-star star-three" aria-hidden="true" data-rate ="<?php echo $review['rating']; ?>"></a>
                    <a  class="fa fa-star star-four" aria-hidden="true" data-rate ="<?php echo $review['rating']; ?>"></a>
                    <a  class="fa fa-star star-five" aria-hidden="true" data-rate ="<?php echo $review['rating']; ?>"></a>
                    <input type='hidden' name="single_review_rate" value=<?php echo $review['rating']; ?>>
                </div>
                <div><p><?php echo $review['review']; ?></p></div>
            </div>
        </div>
    <?php } ?>
    <div id="page_navigation"></div>
        <!-- <div class="row my-5">
            <div class="col-sm-2">
                <img src="https://www.mouthshut.com/Images/COMMON/female-80X80.gif" style="border: 1px solid #c7c6c6;border-radius: 50%;" alt="client">
                <p>Lavanya</p>
                <p>5 reviews</p>
            </div>
            <div class="col-sm-10">
                <div><h3>Review Title</h3></div>
                <div>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                    <i class="fa fa-star"></i>
                </div>
                <div><p>Review</p></div>
            </div>
        </div> -->
    </div>
    <script>

        // var $single_review_rate = $("input[name='single_review_rate']").val();
        // // console.log($single_review_rate);
        // if ($single_review_rate == 1) {
        //     $("input[name='single_review_rate']").siblings(".star-one").css("background-color","red");
        // } else if ($single_review_rate == 2) {
        //     $("input[name='single_review_rate']").siblings(".star-one,.star-two").css("background-color","orange");
        // } else if ($single_review_rate == 3) {
        //     $("input[name='single_review_rate']").siblings(".star-one,.star-two,.star-three").css("background-color","#fbce00");
        // } else if ($single_review_rate == 4) {
        //     $("input[name='single_review_rate']").siblings(".star-one,.star-two,.star-three,.star-four").css("background-color","#73cf11");
        // } else if ($single_review_rate == 5){
        //     $("input[name='single_review_rate']").siblings(".food-review-rate a").css("background-color","#42b67a")
        // }
        // function backgroundColor() {
        var ratings =  $("input[name='single_review_rate']");
        var tot_rating_element =  $("input[name='total_review_rate']");
        var tot_rating =  parseFloat($("input[name='total_review_rate']").val()).toFixed(1);
        
        if (isFloat(tot_rating)) {
            var floatPart = parseFloat(tot_rating % 1).toFixed(1);
            console.log(floatPart);
            var intPart = Math.ceil(tot_rating);
            if (intPart == 1) {
                var bg = "red";
                tot_rating_element.siblings(".star-one").css("background-color","red");
                floatStar(intPart,floatPart,bg);
            } else if (intPart == 2) {
                var bg = "orange";
                tot_rating_element.siblings(".star-one,.star-two").css("background-color","orange");
                floatStar(intPart,floatPart,bg);
            } else if (intPart == 3) {
                var bg = "#fbce00";
                tot_rating_element.siblings(".star-one,.star-two,.star-three").css("background-color","#fbce00");
                floatStar(intPart,floatPart,bg);
            } else if (intPart == 4) {
                var bg = "#73cf11";
                tot_rating_element.siblings(".star-one,.star-two,.star-three,.star-four").css("background-color","#73cf11");
                floatStar(intPart,floatPart,bg);
            } else if (intPart == 5){
                var bg = "#42b67a";
                tot_rating_element.siblings(".food-review-rate a").css("background-color","#42b67a");
                floatStar(intPart,floatPart,bg);
            }
            
        } else {
            if (tot_rating == 1) {
                tot_rating_element.siblings(".star-one").css("background-color","red");
            } else if (tot_rating == 2) {
                tot_rating_element.siblings(".star-one,.star-two").css("background-color","orange");
            } else if (tot_rating == 3) {
                tot_rating_element.siblings(".star-one,.star-two,.star-three").css("background-color","#fbce00");
            } else if (tot_rating == 4) {
                tot_rating_element.siblings(".star-one,.star-two,.star-three,.star-four").css("background-color","#73cf11");
            } else if (tot_rating == 5){
                tot_rating_element.siblings(".food-review-rate a").css("background-color","#42b67a")
            }
        }
        ratings.each(function() {
            var $rate = $(this).val();
            if ($rate == 1) {
                $(this).siblings(".star-one").css("background-color","red");
            } else if ($rate == 2) {
                $(this).siblings(".star-one,.star-two").css("background-color","orange");
            } else if ($rate == 3) {
                $(this).siblings(".star-one,.star-two,.star-three").css("background-color","#fbce00");
            } else if ($rate == 4) {
                $(this).siblings(".star-one,.star-two,.star-three,.star-four").css("background-color","#73cf11");
            } else if ($rate == 5){
                $(this).siblings(".food-review-rate a").css("background-color","#42b67a")
            }
        })

        function isFloat(value) {
            if(value % 1 === 0){
                return false;
            } else{
                return true;
            }
        } 

        function floatStar(int,float,bg) {
            var elem = $("#tot_rate").children("a").eq(int-1);
            float = float.toString();
            float = float.split(".")[1];
            var floatPercent = float+"0"+"%";
            var intPercent = (10-float) +"0"+"%";
            var value = "linear-gradient(to right,"+bg+" "+floatPercent+" , #dcdce6"+" "+intPercent+")";
            //console.log(intPercent+"-"+floatPercent+"-"+bg);
            //elem.css({background-image: value});
            elem.css({
                background: value
            });
            
                //elem.css({background: "-webkit-gradient(linear, left top, left bottom, from(#ccc), to(#000))" });
        }

        /*-----------------------------
    PAGINATION IN RATINGS PAGE
    -----------------------------*/
    $(document).ready(function() {
    if ($("#paginate")) {
        showPage(1);
    }
    $(document).on("click", '.previous', function(event) { 
        previous();
    }).on("click", '.next', function(event) {
        next();
    }).on("click", '.showpage', function(event) {
        showPage($(this).text());
    });
    // $(".showPage").on("click", function(e){
    
    // });
    // $(".previous123").on("click", function(e){
    // console.log("clicked previous 1")
   
    // });
    // $(".first").on("click", function(e){
    // first();
    // });
    // $(".next").on("click", function(e){
    
    // });
    // $(".last").on("click", function(e){
    // var $nop = $("#nop").val()
    // last($nop);
    // });
});
    function makePager(page){
        
      var show_per_page = 10;
      var number_of_items = $('#paginate .row').length;
      var number_of_pages = Math.ceil(number_of_items / show_per_page);
      var number_of_pages_todisplay = 5;
      var navigation_html = '';
      var current_page = page;
      var current_link = (number_of_pages_todisplay >= current_page ? 1 : number_of_pages_todisplay + 1);
      $("#nop").val(number_of_pages);
      if (current_page > 1)
          current_link = current_page;
      if (current_link != 1) {
        navigation_html += "<a class='nextbutton previous' >« Prev&nbsp;</a>&nbsp;";
      } else {
        navigation_html += "<a class='nextbutton previousDisabled' >« Prev&nbsp;</a>&nbsp;";
      }
      
      if (current_link == number_of_pages - 1) current_link = current_link - 3;
      else if (current_link == number_of_pages) current_link = current_link - 4;
      else if (current_link > 2) current_link = current_link - 2;
      else current_link = 1;
      var pages = number_of_pages_todisplay;
      while (pages != 0) {
          if (number_of_pages < current_link) { break; }
          if (current_link >= 1)
              navigation_html += "<a class='" + ((current_link == current_page) ? "currentPageButton" : "numericButton") + " showpage'>" + (current_link) + "</a>&nbsp;";
          //console.log(current_link);
          current_link++;
          pages--;
      }
      if (number_of_pages > current_page){
          navigation_html += "<a class='nextbutton next' >Next »</a>&nbsp;";
      } else {
          navigation_html += "<a class='nextbutton nextDisabled' >Next »</a>&nbsp;";
      }
      if (number_of_pages == 1) {
        $('#page_navigation').html("");
      } else {
        $('#page_navigation').html(navigation_html);
      }
                    
    }
    function showPage(page) {
      var pageSize = 10;
      $("#paginate .row").hide();
      $('#current_page').val(page);
      $("#paginate .row").each(function (n) {
        if (n >= pageSize * (page - 1) && n < pageSize * page)
          $(this).show();
      });
      makePager(page);
    }
      //showPage(1);
    function next() {
      var new_page = parseInt($('#current_page').val()) + 1;
      //console.log(new_page);
      showPage(new_page);
    }
    function last(number_of_pages) {
      var new_page = number_of_pages;
      $('#current_page').val(new_page);
      showPage(new_page);
    }
    function first() {
      var new_page = "1";
      $('#current_page').val(new_page);
      showPage(new_page);    
    }
    function previous() {
      var new_page = parseInt($('#current_page').val()) - 1;
      $('#current_page').val(new_page);
      //console.log(new_page);
      showPage(new_page);
    }
    
	$(function() {
		if($(".food-item-desc").length > 0) {
			$(".search-me").css("display","block");
		} else {
			$(".search-me").css("display","none");
            if ($("#searchInput-group").css("display") == "flex") {
				$("#searchInput-group").css("display", "none");
                $(".logo").css("display", "block");
			}
		}
	})


        
    </script>