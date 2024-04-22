if (typeof jQuery === 'undefined') {
    alert('jQuery is not included, Please include first!');
}
$(document).ready(function () {
    $('.ns-alert-close').on('click', function(){
        $('.ns-alert-wrap').hide();
        location.reload();
    });
    if($('.quick-scoreboard').length > 0){
        var yescount = $('.bar-yes').data('count') ?? 0
        var nocount = $('.bar-no').data('count') ?? 0
        var yesbutcount = $('.bar-yesbut').data('count') ?? 0
        var nobutcount = $('.bar-nobut').data('count') ?? 0
        var total = yescount + nocount + yesbutcount + nobutcount;
        var yesper = noper = yesbutper = nobutper = 0;
        if(total > 0){
            yesper = yescount * 100 / total;
            noper = nocount * 100 / total;
            yesbutper = yesbutcount * 100 / total;
            nobutper = nobutcount * 100 / total;
        }
        if ($('.bar-yes').length>0){
            $('.bar-yes').css({"width": yesper+"%","background-color": bgColor(yesper)});
        }
        if ($('.bar-no').length>0) {
            $('.bar-no').css({"width": noper+"%","background-color": bgColor(noper)});
        }
        if ($('.bar-yesbut').length>0){
            $('.bar-yesbut').css({"width": yesbutper+"%","background-color": bgColor(yesbutper)});
        }
        if ($('.bar-nobut').length>0){
            $('.bar-nobut').css({"width": nobutper+"%","background-color": bgColor(nobutper)});
        }
    }

    if($('footer').length > 0 ){
        $(".tx-ns-feedback").insertBefore( "footer" );
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
            if($(".quick-submit[qkbtn='yes']").length>0){
                $(".quick-submit[qkbtn='yes']").toggleClass('disabled-quickBtn');
            }
            if($(".quick-submit[qkbtn='no']").length>0){
                $(".quick-submit[qkbtn='no']").toggleClass('disabled-quickBtn');
            }

            if($(".commentboxbtn[data-qkbtn='yesbut']").length>0){
                if($(".commentboxbtn[data-qkbtn='yesbut']").data('boxid')!=boxid){

                    $(".commentboxbtn[data-qkbtn='yesbut']").toggleClass('disabled-quickBtn');
                }
            }
            if($(".commentboxbtn[data-qkbtn='nobut']").length>0){
               
                if($(".commentboxbtn[data-qkbtn='nobut']").data('boxid')!=boxid){

                    $(".commentboxbtn[data-qkbtn='nobut']").toggleClass('disabled-quickBtn');
                }            
            }

        });

    $('.quick-submit').click(function (e) {
        var cid = $(this).attr('cid');
        var buttonfor = $(this).attr('buttonfor');
        var qkbutton = $(this).attr('qkbtn');
        var commentText = $('.'+boxid).val();
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
                "tx_nsfeedback_feedback[result][buttonfor]": buttonfor,
                "tx_nsfeedback_feedback[result][qkbutton]": qkbutton,
                "tx_nsfeedback_feedback[result][cid]": cid,
                "tx_nsfeedback_feedback[result][commentText]": commentText,
            },
            success: function (data) {
                console.log(data)
                if (data['Status'] == 'Success') {
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

