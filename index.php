<!DOCTYPE html>
<html>
    <head>
        <title>timetrack.me express edition - Wann habe ich Feierabend?</title>
        <link href='//fonts.googleapis.com/css?family=Titillium+Web:700' rel='stylesheet' type='text/css'>
        <style>
            @import "css/bootstrap.min.css";
            @import "css/bootstrap-theme.css";
            h3 {
                font-size:1.6em;
                color:#555555;
            }
            .time-important {
                font-size:2em;
                font-weight:bold;
            }

            body {
                background: #d2dde0 no-repeat;

                /* Safari 4-5, Chrome 1-9 */
                /* Can't specify a percentage size? Laaaaaame. */
                background: -webkit-gradient(radial, center center, 0, center center, 460, from(#ffffff), to(#cad5d8));

                /* Safari 5.1+, Chrome 10+ */
                background: -webkit-radial-gradient(circle, #ffffff, #cad5d8);

                /* Firefox 3.6+ */
                background: -moz-radial-gradient(circle, #ffffff, #cad5d8);

                /* IE 10 */
                background: -ms-radial-gradient(circle, #ffffff, #cad5d8);

                background-size: 100%;
                font-size:14px;
            }

            header.jumbotron {
                background-color:rgba(0,0,0,0.4);/*#67727A;*/
                position:relative;
                padding: 20px;
                border-bottom:1px solid #555555;
            }
            header .logo {
                font-size:40px;
            }

            .logo .glyphicon {
                font-size:0.3em;
                color: #eeeeee;
            }
            .logo {
                font-family: 'Titillium Web', sans-serif;
                font-weight: bold;
                color: #eeeeee;
            }

            .logo:hover {
                color: #ff9b00;
                text-decoration:none;
                text-shadow: 1px 1px 1px #333333;
            }

            .img-logo {
                vertical-align:top;
                margin:4px 6px 0 -4px;
            }
        </style>
        <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.3.5/bootstrap-select.min.css"/>
    </head>
    <body>
    <header class="jumbotron subhead">
        <div class="container">
            <div class="pull-left"><a class="logo" href="/" title="Homepage"><img src="https://timetrack.me/static/img/logo.png" class="img-logo hidden-xs" alt="timetrack.me logo">timetrack<span class="glyphicon glyphicon-time"></span>me express</a></div>
        </div>
    </header>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading"><h1 class="panel-title">Meine Arbeitszeit</h1></div>
                    <div class="panel-body">
                        <div class="col-md-3">
                            <div class="col-md-12">
                                <h3>Ankunft um</h3>
                                <form action="" class="form" role="form">
                                    <select name="hour_in" id="hour_in" data-width="auto">
                                        <?php for ($a=0;$a<25;$a++): ?>
                                            <option value="<?php echo $a?>"><?php echo $a?></option>
                                        <?php endfor;?>
                                    </select>
                                    :
                                    <select name="minute_in" id="minute_in" data-width="auto">
                                        <?php for ($a=0;$a<60;$a++): ?>
                                            <option value="<?php echo $a?>"><?php echo $a?></option>
                                        <?php endfor;?>
                                    </select>
                                    Uhr
                                </form>
                            </div>
                            <div class="col-md-12">
                                <h3>Arbeitszeit ist</h3>
                                <form action="" class="form" role="form">
                                    <select name="hour_required" id="hour_required" data-width="auto">
                                        <?php for ($a=0;$a<25;$a++): ?>
                                            <option value="<?php echo $a?>" <?php if($a==8) echo 'selected';?>><?php echo $a?></option>
                                        <?php endfor;?>
                                    </select>
                                    :
                                    <select name="minute_required" id="minute_required" data-width="auto">

                                        <?php for ($a=0;$a<60;$a+=5): ?>
                                            <option value="<?php echo $a?>"><?php echo $a?></option>
                                        <?php endfor;?>
                                    </select>
                                    Stunden
                                </form>
                            </div>
                            <div class="col-md-12">
                                <h3>Im B&uuml;ro</h3>
                                <span id="in_time"></span>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="row">

                                <div class="col-md-12">
                                    <h3>Feierabend in</h3>
                                    <span id="finishing_in" class="time-important"></span>

                                </div>
                                <div class="col-md-12">
                                    <h3>Feierabend um</h3>
                                    <span id="finishing_at" class="time-important"></span>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <script src="//code.jquery.com/jquery-1.11.0.min.js"></script>

    <script src="js/bootstrap.min.js"></script>
    <script src="js/piecon.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.3.5/bootstrap-select.min.js"></script>
    <script>
        function pad(num) {
            if ((num+"").length <2) {
                return "0"+num;
            }
            return num;
        }
        $(document).ready(function() {
            var tock = true;
            var now = new Date();
            $("#hour_in").val(now.getHours());
            $("#minute_in").val(parseInt(now.getMinutes()));
            $('select').selectpicker();
            Piecon.setOptions({
                color: '#848788', // Pie chart color
                background: '#ffffff', // Empty pie chart color
                shadow: '#479263', // Outer ring color
                fallback: false // Toggles displaying percentage in the title bar (possible values - true, false, 'force')
            });
            var update = function() {
                var now = new Date();
                var in_time = new Date();
                in_time.setHours($("#hour_in").val());
                in_time.setMinutes($("#minute_in").val());
                in_time.setSeconds(0);

                var required_time = new Date((parseInt($("#hour_required").val())*60+parseInt($("#minute_required").val()))*60000);
                var elapsed = (now - in_time);
                var diff = new Date(elapsed);
                var end_time = new Date(in_time.getTime() + required_time.getTime());
                var remaining_time = new Date(end_time-now);

                var percent_done=(required_time-remaining_time)/required_time*100;
                $("#in_time").html(pad(diff.getUTCHours())+" Stunden<br>"+pad(diff.getUTCMinutes())+" Minuten<br>"+pad(diff.getUTCSeconds())+ " Sekunden");
                $("#finishing_at").text(pad(end_time.getHours())+":"+pad(end_time.getMinutes())+" Uhr ");
                $("#finishing_in").html(pad(remaining_time.getUTCHours())+"&nbsp;Stunden "+pad(remaining_time.getUTCMinutes())+"&nbsp;Minuten ");
                $("title").text(pad(remaining_time.getUTCHours())+":"+pad(remaining_time.getUTCMinutes())+" bis Feierabend");

                Piecon.setOptions({
                    color: '#848788', // Pie chart color
                    background: (tock ? '#ffffff' : "#eeeeee"), // Empty pie chart color
                    shadow: '#479263', // Outer ring color
                    fallback: false
                });
                tock=!tock;
                Piecon.setProgress(percent_done);
            };
            update();
            setInterval(update, 1000);
        })
    </script>
    </body>
</html>