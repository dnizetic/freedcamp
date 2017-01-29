<?php
$template_path = $this->template_path;
?>
<!DOCTYPE html>
<html>

    <head>
        <meta charset="utf-8">
        <title>Freedcamp test task: Denis Nizetic </title>
        <meta name="description" content="">
        <meta name="author" content="">

        <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <!-- Basic Styles -->
        <link rel="stylesheet" type="text/css" media="screen" href="<?php echo $template_path; ?>css/bootstrap.min.css">

        <!-- Table styles. -->
        <style type="text/css">
            .tg  {border-collapse:collapse;border-spacing:0;}
            .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
            .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;}
            .tg .tg-yw4l{vertical-align:top}
        </style>

        <!-- Link to Google CDN's jQuery + jQueryUI; fall back to local -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
        <script>
            if (!window.jQuery) {
                document.write('<script src="<?php echo $this->template_path; ?>js/libs/jquery-2.1.1.min.js"><\/script>');
            }

            var csrf_value = '<?php echo $this->security->get_csrf_hash(); ?>';
        </script>
    </head>

    <body>

        <?php echo $content; ?>

        <!-- Extra scripts loaded by templating engine. -->
        <?php echo $_scripts; ?>
    </body>

</html>