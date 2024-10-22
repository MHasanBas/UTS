<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HasanStore - Toko Fashion Indonesia</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('../style.css') }}">
</head>
<body>

    @include('landing.header')

    @include('landing.koleksi')
  
    @include('landing.artikel')

    @include('landing.about')
    
    @include('landing.footer')

    
</body>
</html>
