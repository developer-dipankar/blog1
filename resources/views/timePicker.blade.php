
<div class="container">
    <div class="row">
        <form id="schedulingUserform" role="form" method="post" action="">
            {{ csrf_field() }}
            
            <?php
                $daysArray = ['Sunday', 'Monday', 'Tuesday', "Wednesday", 'Thursday', 'Friday', 'Saturday'];
            ?>
            @foreach($daysArray as $days)
            <div class="form-group">
                <div class="col-md-2">{{$days}}</div>
                @if($days == 'Sunday')
                    <div class="col-md-2">{{date('Y-m-d',strtotime($days.' last week'))}}</div>
                @else
                    <div class="col-md-2">{{date('Y-m-d',strtotime($days.' this week'))}}</div>
                @endif
                <div class="col-md-2"><input type="checkbox" name="checkDay" class="checkDay" value="{{$days}}"></div>
                <div class="col-md-3">
                    <div class="input-group bootstrap-timepicker timepicker">
                        <input type="text" name="{{$days}}pickTime" id="{{$days}}pickTime" class="form-control input-small input-group-addon pickTime" disabled>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                    </div>
                    <span class="message"></span>

                </div>
                <div class="col-md-3">
                    <div class="input-group bootstrap-timepicker timepicker">
                        <input type="text" name="{{$days}}endTime" id="{{$days}}endTime" class="form-control input-small input-group-addon endTime" disabled>
                        <span class="input-group-addon"><i class="glyphicon glyphicon-time"></i></span>
                    </div>
                    <span class="message"></span>

                </div>
            </div>
            @endforeach

            <div class="field_row">
                <input type="submit" class="btn btn-primary" name="" value="Save">
            </div>
        </form>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<!-- Optional theme -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">

<!-- Latest compiled and minified JavaScript -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.16.0/additional-methods.min.js"></script>

<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,300italic,400italic,600italic' rel='stylesheet' type='text/css'>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<script src="https://apis.google.com/js/api:client.js"></script>
<script type="text/javascript">
    $(document).ready(function(){

        $(".checkDay").change(function() {
            if(this.checked) {
                $(this).parent().parent().find('.pickTime').prop('disabled', false);
                $(this).parent().parent().find('.endTime').prop('disabled', false);
            } else {
                $(this).parent().parent().find('.pickTime').prop('disabled', true);
                $(this).parent().parent().find('.endTime').prop('disabled', true);
                $(this).parent().parent().find('.endTime').val('11:00');
                $(this).parent().parent().find('.pickTime').val('10:00');

                $(this).parent().parent().find('.endTime').removeClass('validationNotMatched');
                $(this).parent().parent().find('.pickTime').removeClass('validationNotMatched');

                $(this).parent().parent().find('.message').text('');
            }
        });

        // Method 1
        var validationMatched = false;
        $('.pickTime').timepicker({
                maxHours: 24,
                showMeridian: false,
                minuteStep: 1,
                defaultTime: '10:00'
        }).on('hide.timepicker', function(e) {
            var h= e.time.hours;
            var m= e.time.minutes;
            var pickTime = (h*100)+m;

            var takeEndTime = $(this).parent().parent().parent().find('.endTime').val();
            var endTime = parseInt(takeEndTime.replace(':',''));

            if (endTime <= pickTime) {
                // alert("Pick Time should be less than "+takeEndTime);
                validationMatched = false;
                $(this).addClass('validationNotMatched');
                $(this).parent().parent().find('.message').text("Pick Time should be less than "+takeEndTime);
            } else{
                validationMatched = true;
                $(this).removeClass('validationNotMatched');
                $(this).parent().parent().find('.message').text(" ");

            }
        });

        $('.endTime').timepicker({
                maxHours: 24,
                showMeridian: false,
                minuteStep: 1,
                defaultTime: '11:00'

        }).on('hide.timepicker', function(e) {
            var h= e.time.hours;
            var m= e.time.minutes;
            var endTime = (h*100)+m;

            var takePickTime = $(this).parent().parent().parent().find('.pickTime').val();
            var pickTime = parseInt(takePickTime.replace(':',''));

            if (endTime <= pickTime) {
                // alert("End Time should be greater than "+takePickTime);
                validationMatched = false;
                $(this).addClass('validationNotMatched');
                $(this).parent().parent().find('.message').text("End Time should be greater than "+takePickTime);

            } else{
                validationMatched = true;
                $(this).removeClass('validationNotMatched');
                $(this).parent().parent().find('.message').text(" ");
            }
        });

        $("#schedulingUserform").submit(function(e){

            var validationNotMatched = $('#schedulingUserform').has('.validationNotMatched');
            if (validationNotMatched.length > 0) {
                e.preventDefault(e);
            }
        });

    });
</script>