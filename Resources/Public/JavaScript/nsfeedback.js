if (typeof jQuery === 'undefined') {
    alert('jQuery is not included, Please include first!');
}
$(document).ready(function () {
    $('.ns-alert-close').on('click', function(){
        $('.ns-alert-wrap').hide();
        location.reload();
    });
    if($('.quick-scoreboard').length > 0){
        var yescount = $('.bar-yes').data('count')
        var nocount = $('.bar-no').data('count')
        var yesbutcount = $('.bar-yesbut').data('count')
        var nobutcount = $('.bar-nobut').data('count')
        var total = yescount + nocount + yesbutcount + nobutcount;
        var yesper = noper = yesbutper = nobutper = 0;
        if(total > 0){
            yesper = yescount * 100 / total;
            noper = nocount * 100 / total;
            yesbutper = yesbutcount * 100 / total;
            nobutper = nobutcount * 100 / total;
        }
        $('.bar-yes').css({"width": yesper+"%","background-color": bgColor(yesper)});
        $('.bar-no').css({"width": noper+"%","background-color": bgColor(noper)});
        $('.bar-yesbut').css({"width": yesbutper+"%","background-color": bgColor(yesbutper)});
        $('.bar-nobut').css({"width": nobutper+"%","background-color": bgColor(nobutper)});
    }

    if($('footer').length > 0 ){
        $(".global-ns-feedback").insertBefore( "footer" );
    }
    var effect = $('.animationEffect').val();
        var boxid;
        $('.commentboxbtn').click(function() {
            idClass = $(this).attr('id');
            boxid = $(this).data('boxid');
            $('.comment-submit-btn').attr({
                buttonfor: $(this).data('buttonfor'),
                qkbtn: $(this).data('qkbtn'),
                cid: $(this).data('cid'),
                newsid: $(this).data('newsid')
            });
            $('.commentbox').addClass(boxid);
            if (effect == 'toggle') {
                $('.commenttxt').toggle();
            }
            if (effect == 'slide') {
                $('.commenttxt').slideToggle();
            }
            if (effect == 'fade') {
                $('.commenttxt').fadeToggle();
            }
        });

    $('.quick-submit').click(function (e) {
        var newsId = 0;
        var feedbackType = $('.feedbacktype').val();
        var cid = $(this).attr('cid');
        var buttonfor = $(this).attr('buttonfor');
        var qkbutton = $(this).attr('qkbtn');
        var commentText = $('.'+boxid).val();
        newsId = $(this).attr('newsid');
        if ($('.'+boxid+":visible").length > 0) {
            console.log($('.'+boxid).length);
            if (commentText == '') {
                $(".validation").show();
                return false;
            }
        }
        var href = $(this).attr('href');
        $.ajax({
            type: "POST",
            url: href,
            data: {
                "tx_nsfeedback_feedback[result][feedbackType]": feedbackType,
                "tx_nsfeedback_feedback[result][buttonfor]": buttonfor,
                "tx_nsfeedback_feedback[result][qkbutton]": qkbutton,
                "tx_nsfeedback_feedback[result][cid]": cid,
                "tx_nsfeedback_feedback[result][commentText]": commentText,
                "tx_nsfeedback_feedback[result][newsId]": newsId,
            },
            success: function (data) {
                if (data == 'OK') {
                    $('#ns-c'+cid+' .qckform').html('');
                    $('.ns-alert-wrap').show();
                } else {
                    $(".validation").remove();
                    var somethingWrong = $('#resubmit').val();
                    $("#feedback-btns").after("<div class='validation' style='color:red;margin-bottom: 20px;'>"+somethingWrong+"</div>");
                }
            },
        });
        e.preventDefault();
    });
});

function bgColor(per){
    if(per > 0 && per <= 20){
        return '#f44336';
    }
    if(per > 20 && per <= 40){
        return '#ff9800';
    }
    if(per > 40 && per <= 60) {
        return '#00bcd4';
    }
    if(per > 60 && per <= 80) {
        return '#2196F3';
    }
    if(per > 80 && per <= 100) {
        return '#4CAF50';
    }
}

