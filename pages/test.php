<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


<div>
    <input type="text" id="valuesProgress">
</div>
<div class="progress-wrap progress" data-progress-percent="10"  >
  <div class="progress-bar progress"></div>
</div>

<!-- <button class="btn btn-primary" onclick="addHtmlTableRow();">
      Add Row Data
</button> -->

<script>
    // on page load...
    $('#valuesProgress').on('keyup',function(){
        let values = $('#valuesProgress').val();

        if (!values ){
            return false;
        }
        moveProgressBar();
    });

    // SIGNATURE PROGRESS
    function moveProgressBar() {
    //    console.log("moveProgressBar");
        var progressPercent = $('#valuesProgress').val();
        var limit = 20;

        if (progressPercent > limit){
            progressPercent = limit;
            $('#valuesProgress').val(limit);
            // moveProgressBar();
            // return false;
        }
        var getPercent = ( progressPercent / limit);
        
        console.log(getPercent);
        var getProgressWrapWidth = $('.progress-wrap').width();
        var progressTotal = getPercent * getProgressWrapWidth;
        var animationLength = 1200;
        
        // on page load, animate percentage bar to data percentage length
        // .stop() used to prevent animation queueing
        $('.progress-bar').stop().animate({
            left: progressTotal
        }, animationLength);
    }
</script>

<style>
    @import "compass/css3";

    .progress {
    width: 100%;
    height: 50px;
    }
    .progress-wrap {
        background: #f80;
        margin: 20px 0;
        overflow: hidden;
        position: relative;
    }

    .progress-bar {
        background: #ddd;
        left: 0;
        position: absolute;
        top: 0;
    }

</style>