<!DOCTYPE html>
<html>
    <head>
        <title>CG遊戲數據後台</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                color: #B0BEC5;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
                vertical-align: middle;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 72px;
                margin-bottom: 40px;
            }
            .link {
                text-decoration:none;
                color: #257ba7;
                font-family: 'Lato';
                font-weight:500;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">糟糕 ! 找不到此頁面</div>
                 <p>
                <a class="link" href="{{ url('/') }}">馬上回首頁</a>
            </div>
        </div>
    </body>
</html>
