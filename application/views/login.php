<?php
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
?>

<html>
    <head>
        <script
            src="https://code.jquery.com/jquery-3.2.1.js"
            integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE="
        crossorigin="anonymous"></script> 
    </head>
    <body>
        <?php echo form_open(''); ?>
        <input  id="username" name="username" type="text" value="">
        <input  id="pswd" name="pswd" type="password" value="">
        <input  id="submit" name="submit" type="button" value="submit">
        <input  id="retrieve" name="retrieve" type="button" value="retrieve">

        <?php echo form_close(); ?>   
        <div id="container"></div>

        <script>

            $(function () {


                $('#submit').click(function () {
                    var username = $('#username').val();
                    var pswd = $('#pswd').val();
                    $.ajax({
                        method: "POST",
                        url: "<?php echo base_url(); ?>auth/token",
                        data: {username: username, pswd: pswd},
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log("catacrocker");   
                            $("#container").html("no existe el token o no esta autorizado");
                        }
                    })
                        .done(function (msg) {
                            console.log(msg.token);
                            alert("Token: " + msg.token);
                            tokenSuccess(msg.token)
                                  localStorage.setItem('token',msg.token);
                        });
                });

                $('#retrieve').click(function () {
                    var token = localStorage.getItem("token");
                    $.ajax({
                        method: "POST",
                        url: "<?php echo base_url(); ?>auth/tokenretrieve",
                        //   data: {Authorization: token},
                        beforeSend: function (request) {
                            request.setRequestHeader("Authorization", token);
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log("crock");   
                            $("#container").html("no existe el token o no esta autorizado");
                        }
                    })
                    .done(function (msg) {
                        console.log(msg);
                        $("#container").html("El Sujeto del toke es: "+msg.payload.sub);
                    })
                            
                });
            });

        </script>
    </body>
</html>