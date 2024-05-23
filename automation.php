<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
</head>
<body>
    <div>
        <form action="" method="POST">
            <div class="container">
                <div class="row">
                    <h1 style="text-align:center" class="mb-5">Automation 101</h1>
                    <div class="col-4">

                    </div>
                    <div class="col-5">
                        <div class="input-group">
                            <span class="input-group-text">Select Port</span>
                            <select id="portNumber" class="form-select form-control-sm" aria-label="Default select
                            example">
                                <?php
                                    for($x = 1; $x <= 20; $x++) {
                                        echo '<option value="'.'COM'.$x.'">'.'COM'.$x.'</option>';
                                    }
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <label></label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4"></div>
                        <div class="col-5">
                            <input type="button" value="ON" class="btn btn-success" id="btnON">
                            <input type="button" value="OFF" class="btn btn-danger" id="btnOFF">
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


    <script>
        $(document).ready(function() {
            var portid="";

            $("#portNumber").change(function() {
                portid = $("#portNumber").val();
                alert(portid);
            });

            $('#btnON').click(function(){
                // alert("ON");
                $.ajax({
                    type: "POST",
                    url: "data.php",
                    data: ({port:portid, Selector:'1'}),
                    success: function(data){
                        alert(data);
                    }
                });
            });

            $('#btnOFF').click(function(){
                // alert("ON");
                $.ajax({
                    type: "POST",
                    url: "data.php",
                    data: ({port:portid, Selector:'2'}),
                    success: function(data){
                        alert(data);
                    }
                });
            });
        });
    </script>
</body>
</html>
