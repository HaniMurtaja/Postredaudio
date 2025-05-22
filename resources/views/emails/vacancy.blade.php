<html>

<head>
    <style>
        .mail-wrapper {
            background-color: #000 !important;
            padding: 30px 4% 10px;
        }

        .mail-wrapper .title,
        .mail-wrapper .form-info,
        .mail-wrapper .footer {
            color: #fff;
        }

        .title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .title span {
            color: #ff0000;
            text-transform: capitalize;
        }

        .form-info {
            padding-bottom: 20px;
            margin-bottom: 30px;
            border-bottom: 1px solid #fff;
        }

        .info-block {
            margin-bottom: 20px !important;
        }

        .info-block.phone {
            margin-bottom: 0 !important;
        }

        .info-block .label {
            margin-right: 10px;
            font-size: 18px;
            line-height: 20px;
            font-weight: bold;
            color: #ff0000;
        }

        .info-block .text {
            font-size: 16px;
        }

        .info-block .text a {
            color: inherit;
            text-decoration: none;
        }

        .footer {
            padding-bottom: 30px;
        }

        .footer .postred-logo {
            width: 150px;
            margin-bottom: 5px;
        }

        .footer .postred-logo img {
            width: 100%;
            user-select: none;
        }

        .footer .date {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="mail-wrapper">
        <div class="title">Application for <span>{{ $formData['position'] }}</span></div>
        <div class="form-info">
            @isset($formData['name'])
            <div class="info-block">
                <div class="label">Name:</div>
                <div class="text">{{ $formData['name'] }}</div>
            </div>
            @endisset
            @isset($formData['mail'])
            <div class="info-block">
                <div class="label">Email:</div>
                <div class="text">{{ $formData['mail'] }}</div>
            </div>
            @endisset
            @isset($formData['phone'])
            <div class="info-block phone">
                <div class="label">Phone:</div>
                <div class="text">{{ $formData['phone'] }}</div>
            </div>
            @endisset
        </div>
        <div class="footer">
            <div class="postred-logo">
                <img src="{{ $message->embed(storage_path('images/postred-logo.png')) }}">
            </div>
            <div class="date">© {{ now()->year }} POSTRED</div>
        </div>
    </div>
</body>

</html>