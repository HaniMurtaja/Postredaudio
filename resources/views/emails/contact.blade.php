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

        .mail-wrapper .title {
            font-size: 22px;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .mail-wrapper .title span {
            color: #ff0000;
        }

        .form-info {
            padding-bottom: 20px;
            margin-bottom: 30px;
            border-bottom: 1px solid #fff;
        }

        .info-block {
            margin-bottom: 20px !important;
        }

        .info-block.message {
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
        <div class="title">Contact Request from <span>{{ $formData['name'] }}</span></div>
        <div class="form-info">
            @isset($formData['name'])
            <div class="info-block">
                <div class="label">Name</div>
                <div class="text">{{ $formData['name'] }}</div>
            </div>
            @endisset
            @isset($formData['mail'])
            <div class="info-block">
                <div class="label">Mail</div>
                <div class="text">{{ $formData['mail'] }}</div>
            </div>
            @endisset
            @isset($formData['dynamic_field_1_value'])
            <div class="info-block">
                <div class="label">@php echo nova_get_setting('dynamic_field_1_name'); @endphp</div>
                <div class="text">{{ $formData['dynamic_field_1_value'] }}</div>
            </div>
            @endisset
            @isset($formData['dynamic_field_2_value'])
            <div class="info-block">
                <div class="label">@php echo nova_get_setting('dynamic_field_1_name'); @endphp</div>
                <div class="text">{{ $formData['dynamic_field_2_value'] }}</div>
            </div>
            @endisset
            @isset($formData['message'])
            <div class="info-block message">
                <div class="label">Message</div>
                <div class="text">{{ $formData['message'] }}</div>
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