<!DOCTYPE html>
<html lang="en" dir="ltr">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
        <meta charset="utf-8">
        <title></title>

        <link rel="manifest" href="manifest.json">

        <meta name="mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="application-name" content="SnapTime">
        <meta name="apple-mobile-web-app-title" content="SnapTime">
        <meta name="msapplication-starturl" content="/index.php">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, maximum-scale=1, user-scalable=0">

        <link rel="icon" type="image/png" sizes="405x88" href="/_img/snaptime_logo.png">
        <link rel="apple-touch-icon" type="image/png" sizes="405x88" href="/_img/snaptime_logo.png">

        <!-- CSS -->
        <style>

        * {
            margin: 0;
            font-family: 'NeoSansRegular', Helvetica, Tahoma;
        }

        html, body {
            width: 100%;
            height: 100vh;
            overflow-x: hidden;
        }

        body {
            max-width: 100%;
            overflow-x: hidden;
            background-image: url("/_img/snaptime_bg.jpg");
            background-repeat: no-repeat;
            background-size: 100% 100%;
            position: relative;
        }

        /* ---------- Fonts ------------- */

        @font-face {
        	font-family: 'NeoSansRegular';
            src: url('/_fonts/neosans-webfont.eot');
            src: url('/_fonts/neosans-webfont.eot?#iefix') format('embedded-opentype'),
                 url('/_fonts/neosans-webfont.svg#neosansregular') format('svg'),
        		 url('/_fonts/neosans-webfont.woff') format('woff'),
                 url('/_fonts/neosans-webfont.ttf') format('truetype');
        	font-weight: normal;
            font-style: normal;
        }

        @font-face {
        	font-family: 'NeoSansBold';
            src: url('/_fonts/neosans_bold-webfont.eot');
            src: url('/_fonts/neosans_bold-webfont.eot?#iefix') format('embedded-opentype'),
                 url('/_fonts/neosans_bold-webfont.svg#neosansbold') format('svg'),
        		 url('/_fonts/neosans_bold-webfont.woff') format('woff'),
                 url('/_fonts/neosans_bold-webfont.ttf') format('truetype');
        	font-weight: normal;
            font-style: normal;
        }

        img {
            display: block;
            width: 50%;
            height: auto;
            margin-left: 8%;
            margin-top: 8%;
            max-width: 405px;
        }

        a.logo {
            margin: 0;
            text-align: center;
            text-decoration: none;
            position: absolute;
            bottom: 0;
            width: 100%;
        }

        .logo {font-size:25px; line-height:30px; letter-spacing:-1px; margin-top:24px; font-weight: normal; color:#fff; display:block; transition: all .3s ease-in-out; }
        .logo>.regular, .logo>.super {font-family: 'NeoSansRegular', sans-serif; font-weight: normal;  }
        .logo>.bold {font-family: 'NeoSansBold', sans-serif; font-weight: normal;  }
        .logo>.super {font-size:20px; letter-spacing:-3px; vertical-align: super; font-weight: normal;  }

        .offline-container {
            width: 100%;
        }

        .offline-container h2 {
            padding-top: 100px;
            text-align: center;
            color: #fff;
        }

        .offline-container p {
            text-align: center;
            color: #fff;
            padding-top: 15px;
        }

        @media only screen and (min-width: 768px) {
            img {
                margin-left: 2%;
                margin-top: 2%;
            }
        }
        </style>
    </head>
    <body>

        <div class="offline-container">
            <img id="snaptime-logo" src="/_img/snaptime_logo.png" />
            <h2>UPS det ser ud til du ikke har forbindelse til nettet</h2>
            <p>Vores app fungere ikke uden adgang til nettet.</p>
        </div>
        <a href="/" class="logo"><span class="bold">Internet</span><span class="regular">Factory</span><span class="super">®</span></a>
    </body>
</html>
