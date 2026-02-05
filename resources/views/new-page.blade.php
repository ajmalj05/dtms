<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Patient Details</title>
</head>
<body>

    <h1>Patient Details</h1>
    <p><strong>Name:</strong> {{ $name }}</p>
    <p><strong>Age:</strong> {{ $age }}</p>
    <p><strong>UHID:</strong> {{ $uhid }}</p>

</body>
</html>
<!---Athira--->