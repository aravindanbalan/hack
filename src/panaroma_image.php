<?php
/**
 * Created by IntelliJ IDEA.
 * User: Aravindan
 * Date: 4/4/14
 * Time: 8:11 PM
 * To change this template use File | Settings | File Templates.
 */
?>
<?php
 $imageUrl = $_GET["imageurl"];
$imageWidth = $_GET["width"];
$imageHeight = $_GET["height"];
$repeat = $_GET["repeat"];

?>
<html>
<head>
    <link href="../css/panorama_viewer.css" media="screen2" type="text/css" rel="stylesheet">
</head>
<body>
<script type="text/javascript" src="../js/jquery.panorama_viewer.js"></script>
<script>
    $(function(){
        var repeat = false;
        var img = $('#myimage');
        if (img.width() / img.height() > 4) {
            repeat = true;
        }
        $(".panorama").panorama_viewer({
            repeat: repeat,               // The image will repeat when the user scroll reach the bounding box. The default value is false.
            direction: "horizontal",    // Let you define the direction of the scroll. Acceptable values are "horizontal" and "vertical". The default value is horizontal
            animationTime: 500,         // This allows you to set the easing time when the image is being dragged. Set this to 0 to make it instant. The default value is 700.
            easing: "ease-out",         // You can define the easing options here. This option accepts CSS easing options. Available options are "ease", "linear", "ease-in", "ease-out", "ease-in-out", and "cubic-bezier(...))". The default value is "ease-out".
            overlay: true               // Toggle this to false to hide the initial instruction overlay
        });
    });

</script>
    <div id="content" class="panorama">
            <img id="myimage" class="myimage" src= "<?php echo $imageUrl; ?>">
        </div>
</body>
</html>