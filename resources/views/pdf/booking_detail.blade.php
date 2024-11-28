<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #008c9c;
            color: #333;
            padding: 30px;
        }
        .container {
            width: 100%;
            max-width: 80%;
            margin: 0 auto;
            padding: 25px;
            background-color: #008c9c;
            border-radius: 15px;
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            color: white;
            border:1px solid white;
        }
        h1 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 30px;
            color: #fff;
        }
        .details {
            margin-top: 20px;
            padding-bottom: 50px;
        }
        .details label {
            display: block;
            font-weight: bold;
            font-size: 16px;
            color: white;
            margin-bottom: 5px;
        }
        .details .value {
            margin-bottom: 20px;
            padding: 12px;
            background-color: #64d9df;
            border-radius: 8px;
            font-size: 16px;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Booking Confirmation</h1>
        <div class="details">
            <label>Your Booking ID</label>
            <div class="value">{{ $reservation->order_id }}</div>

            <label>Booking Date</label>
            <div class="value">{{ $reservation->date }}</div>

            <label>Booking Time</label>
            <div class="value">{{ $reservation->time }}</div>

            <label>Shop Contact</label>
            <div class="value">{{ $branch->phone }}</div>

            <label>Shop Address</label>
            <div class="value">{{ $branch->address }}</div>
        </div>
    </div>
</body>
</html>
