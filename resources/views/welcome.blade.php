<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Raleway', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
</head>
<body>


<div class="flex-center position-ref full-height">
    <div class="content">
        <div id="received">

        </div>
        <form class="formforgroup" method="post" action="">
            <div class="form-group">
                <label>Data Input</label>
                <input id="mixeddata" class="form-control" placeholder="Enter Data" name="mixeddata" maxlength="50">
            </div>

            <div>
                Add Data: <input type="checkbox" id="adddata">
                Find Data: <input type="checkbox" id="finddata">
                Find All: <input type="checkbox" id="findalldata">
            </div>
            <div class="form-group clearfix">
                <button type="submit" class="btn btn-outline btn-primary pull-right">Process</button>
            </div>
        </form>
    </div>
</div>

<script>

    var request;
    var requestWhich;

    function checking() {
        $("#adddata").prop("checked", false);
        $("#finddata").prop("checked", false);
        $("#findalldata").prop("checked", false);
        event.target.checked = true;
        requestWhich = event.target.id;
    }

    $("#adddata").bind('click', checking);
    $("#finddata").bind('click', checking);
    $("#findalldata").bind('click', checking);
    $('form.formforgroup').on('submit', function (form) {
        form.preventDefault();
        if (!requestWhich) {
            alert('Please select process!');
            return false;
        }
        if (!$("#mixeddata").val().trim() && requestWhich != "findalldata") {
            alert('Please enter data!');
            return false;
        }

        if (requestWhich == 'adddata') {
            request = {
                data: $("input[name=mixeddata]").val().replace(/\s/g, '').trim()
            }
        }
        if (requestWhich == 'finddata') {
            request = {
                finddata: $("input[name=mixeddata]").val().replace(/\s/g, '').trim()
            }
        }
        if (requestWhich == 'findalldata') {
            request = {
                findalldata: ""
            }
        }

        function getRequest() {
            return window.ActiveXObject ? new ActiveXObject("Microsoft.XMLHTTP") : window.XMLHttpRequest ? new XMLHttpRequest : false
        }

        var req = getRequest();
        req.upload.onprogress = function (e) {
            if (e.lengthComputable) {
                var percentComplete = (e.loaded / e.total) * 100;
                console.log(percentComplete + '% uploaded');
            }
        };

        req.onload = function () {
            if (this.status == 200) {
                var resp = this.response;
                console.log('Server got:', resp);
                $(".formforgroup")[0].reset()
                var convertedresp = typeof(resp) == "object" ? JSON.stringify(resp) : resp
                $("div[id=received]").html('<b>' + convertedresp + '</b>');
            };

        };
        req.open('POST', 'http://' + window.location.hostname + '/simpleApiData/' + requestWhich);
        req.setRequestHeader("Content-Type", "application/json");
        req.send(JSON.stringify(request));

    });

</script>
</body>
</html>
