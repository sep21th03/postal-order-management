<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Thông báo trạng thái đơn hàng</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .header {
            background-color: #4CAF50;
            color: white;
            padding: 10px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            text-align: center;
        }
        .content {
            margin: 20px 0;
        }
        .footer {
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #666666;
        }
        .btn {
            display: inline-block;
            padding: 10px 20px;
            color: white;
            background-color: #4CAF50;
            text-decoration: none;
            border-radius: 5px;
        }
        .order-info {
            background-color: #f1f1f1;
            padding: 10px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .order-info p {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Bưu điện</h1>
        </div>
        <div class="content">
            <p>Chào {{ $customerName }},</p>
            <p>Chúng tôi xin thông báo về trạng thái đơn hàng của bạn:</p>
            <div class="order-info">
                <p><strong>{{$content}}</p>
            </div>
            <p>Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua email.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} Postal. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
