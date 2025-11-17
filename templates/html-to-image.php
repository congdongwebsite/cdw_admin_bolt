<?php
/*
 Template Name: HTML TO IMAGE
 */
?>

<html>

<head>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="<?php echo get_stylesheet_directory_uri() ?>/assets/js/html2canvas/html2canvas.js"></script>
</head>

<body>
    <div id="html-content-holder" style="background-color: #F0F0F1; color: #00cc65; width: 500px;padding-left: 25px; padding-top: 10px;">
        <strong>Hello html2canvas</strong>
        <hr />
        <h3 style="color: #3e4b51;">
            Html to canvas, and canvas to proper image
        </h3>
        <p style="color: #3e4b51;">Hello html2canvas</p>
    </div>
    <input id="btn-Preview-Image" type="button" value="Preview" />
    <a id="btn-Convert-Html2Image" href="#">Download</a>
    <br />
    <h3>Preview :</h3>
    <div id="previewImage"></div>



    <script>
        $(document).ready(function() {
            var element = $("#html-content-holder"); // global variable
            var getCanvas; // global variable

            $("#btn-Preview-Image").on('click', function() {
                html2canvas(element[0]).then(canvas => {
                    $("#previewImage").append(canvas);
                    getCanvas = canvas;
                });
            });

            $("#btn-Convert-Html2Image").on('click', function() {
                html2canvas(element[0]).then(canvas => {
                    $("#previewImage").append(canvas);
                    var imagebase64 = canvas.toDataURL('image/jpg');
                    var name = 'abcd';
                    var link = document.createElement("a");
                    link.href = imagebase64;

                    let data = {
                        action: 'save_image_html',
                        image: imagebase64,
                        name: name,
                    };

                    $.ajax({
                        url: '<?php echo admin_url("admin-ajax.php"); ?>',
                        type: 'post',
                        dataType: 'json',
                        data: {
                            ...data
                        },
                        beforeSend() {},
                        success(res) {
                            console.log(res)
                            if (res.success) {

                            }
                        }
                    });
                    //link.download = 'screenshot.jpg';
                    //link.click();
                });
            });

        });
    </script>
</body>

</html>