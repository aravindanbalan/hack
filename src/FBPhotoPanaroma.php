<?php
/**
 * Copyright 2011 Facebook, Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may
 * not use this file except in compliance with the License. You may obtain
 * a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require_once '../php-sdk/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
    'appId'  => '441093369359229',
    'secret' => '6fa913f044435ba2d67387fcfd825868',
    'cookie' => true
));

// Get User ID
$user = $facebook->getUser();
$access_token = $facebook->getAccessToken();


// We may or may not have this data based on whether the user is logged in.
//
// If we have a $user id here, it means we know the user is logged into
// Facebook, but we don't know if the access token is valid. An access
// token is invalid if the user logged out of Facebook.

if ($user) {
    try {
        // Proceed knowing you have a logged in user who's authenticated.

        $user_albums = $facebook->api('/me/albums');
        $albums = array();
        if(!empty($user_albums['data'])) {
            foreach($user_albums['data'] as $album) {
                $temp = array();
                $temp['id'] = $album['id'];
                $temp['name'] = $album['name'];
                $temp['thumb'] = "https://graph.facebook.com/{$album['id']}/picture?type=album&access_token={$facebook->getAccessToken()}";
                $temp['count'] = (!empty($album['count'])) ? $album['count']:0;
                if($temp['count']>1 || $temp['count'] == 0)
                    $temp['count'] = $temp['count'] . " photos";
                else
                    $temp['count'] = $temp['count'] . " photo";
                $albums[] = $temp;
            }
        }
    } catch (FacebookApiException $e) {
        error_log($e);
        $user = null;
    }
}

// Login or logout url will be needed depending on current user state.
if ($user) {
    $logoutUrl = $facebook->getLogoutUrl();
} else {
    $statusUrl = $facebook->getLoginStatusUrl();
    $loginUrl = $facebook->getLoginUrl(array(
        'scope' => 'user_photos'
    ));
}

// This call will always work since we are fetching public data.
$aravind = $facebook->api('/aravindanB');

?>
    <!doctype html>
    <html xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <!--   <link href="../css/style.css" media="screen" type="text/css" rel="stylesheet">   -->
        <link rel="stylesheet" href="../css/plusgallery.css">
        <link href="../css/panorama_viewer.css" media="screen2" type="text/css" rel="stylesheet">
        <link href="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/themes/ui-darkness/jquery-ui.css" rel="stylesheet">

        <link href="../css/panorama_viewer.css" media="screen2" type="text/css" rel="stylesheet">
        <link href="../css/starter-template.css" rel="stylesheet">


    </head>
    <body>


        <div class="starter-template">
            <h1>Facebook Hackathon </h1>
            <h2> Album and Photo Manager</h2>

            <p class="lead">Manage your photos and albums from different Social platforms. Share across social platforms. Includes panorama rendering supports....</p>
        </div>

    <?php if ($user): ?>
        <a href="<?php echo $logoutUrl; ?>">Logout</a>
    <?php else: ?>

        <div>
            Check the login status using OAuth 2.0 handled by the PHP SDK:
            <a href="<?php echo $statusUrl; ?>">Check the login status</a>
        </div>
        <div>
            Login using OAuth 2.0 handled by the PHP SDK:
            <a href="<?php echo $loginUrl; ?>">Login with Facebook</a>
        </div>
    <?php endif ?>

    <div id="tabs">
        <ul>
            <li><a href="#tabs-1" title="">Facebook</a></li>
            <li><a href="#tabs-2" title="">Instagram</a></li>
            <li><a href="#tabs-4" title="">Google+</a></li>
            <li><a href="#tabs-3" title="">Flickr</a></li>
        </ul>

        <div id="tabs-1" class="mytab">
            <!--tab content-->

            <div id="wrapper" class="mywrapper">
                <div id="plusgallery"  class="plusgallery"
                     data-type="facebook"
                     data-userid="aravindanB" data-access-token = "<?php echo $access_token; ?>"
                    >
                </div>
            </div>

        </div>
        <div id="tabs-2" class="mytab">
            <!--tab content-->

            <div id="wrapper" class="mywrapper">
                <div id="plusgallery"    class="plusgallery"
                     data-type="instagram"
                     data-userid="212082755" data-access-token = "212082755.96f5a11.9d89d53707614c83a4887be178937e0e"
                    >
                </div>
            </div>
        </div>
        <div id="tabs-3" class="mytab">
            <!--tab content-->
            <div id="wrapper" class="mywrapper">
                <div id="plusgallery" data-api-key="e63d5c0dafdd4639986d72843fc5108d" data-userid="122383554@N07" data-type="flickr"></div>
            </div>
        </div>

        <div id="tabs-4" class="mytab">
            <!--tab content-->
            <div id="wrapper" class="mywrapper">
              <p>Work in progress</p>
            </div>
        </div>

    </div><!--End tabs-->



    <script type="text/javascript" src="../js/jquery-1.7.2.min.js "></script>
    <!-- <script type="text/javascript" src="../js/jquery.fancybox-1.3.4.pack.js"></script>   -->
    <script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.9.2/jquery-ui.min.js"></script>
    <script src="../js/plusgallery.js"></script>
    <script type="text/javascript" src="../js/jquery.panorama_viewer.js"></script>

    <div id="dialog"></div>
    <img id="tempImg" hidden>
    <input type="button" id="temp" hidden=""/>

    <script>
        $(function(){
            //DOM loaded
            $('.plusgallery').plusGallery();

        });

    </script>

    <script>

        $(function() {
            $("#dialog").dialog({
                autoOpen: false,
                modal: true,
                open: function ()
                {
                    var url = $(this).attr('sourceUrl');
                    var width = $('#tempImg').width();
                    var height = $('#tempImg').height();
                    if ($(this).is(':empty')) {
                        var repeat = false;
                        if(width > 0 && height > 0)
                        {
                            var aspectRatio = width/height;
                            if(aspectRatio > 4) repeat = true;
                        }
                        $(".panorama").panorama_viewer({repeat:repeat});

                        $(this).load('panaroma_image.php?imageurl='+url+'&width='+width+'&height='+height+'&repeat='+repeat);
                        //+'&& width='+width+'&& height='+height

                    }
                    else
                    {
                        $(this).empty();
                        $(this).load('panaroma_image.php?imageurl='+url);
                    }
                },
                close: function ()
                {
                    $(this).empty();
                },
                width: 1000,
                height: 600
                ,title:"Panaroma"

            });

            $("#temp").on("click", function() {
                $("#dialog").dialog("open");

            });
        });

    </script>


    <script>
        $(function(){
            //DOM loaded
            // $("#tabs").tabs();
            $("#tabs").tabs({
                select: function(event, ui) {
                    //alert(ui.index);
                    //remove all existing wrappers and add new for that particular tab
                    $("#tabs").find('div.mytab').each(function (li) {
                        // $(this).empty();
                    });
                    var id = $("#tabs").find('div.mytab')[ui.index];

                    var gall = $(id).find('div.plusgallery');

                    // $(gall).plusGallery();
                    return true;
                }
            });
        });

    </script>

    </body>
    </html>


<?php
/**
 * for creating new images with additional metadata - use this for panaroma
 * https://developers.facebook.com/docs/opengraph/using-objects/
 *
 * http://phpflickr.com/ - for connecting to flickr
 *
 * picasa - https://developers.google.com/picasa-web/docs/1.0/developers_guide_php
 */
?>