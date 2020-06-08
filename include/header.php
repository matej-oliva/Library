<!DOCTYPE html>
<html lang="cs">
    <head>
        <meta charset="UTF-8">
        <meta name="description" content="Library Webapp" />
        <meta name="author" content="Matěj Oliva" />
        <meta name="keywords"
            content="knihovna, aplikace, správa výpůjček knih, knihy, dokumenty, půjčení" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title><?php echo (!empty($pageTitle)?$pageTitle.' - ':'')?>Knihovna</title>
        <link rel="stylesheet" href="./assets/css/main.css">
        <link rel="icon" href="./assets/img/Graphicloads-Food-Drink-Coffee-bean.ico">
        <script src="https://kit.fontawesome.com/d4998154c5.js" crossorigin="anonymous"></script>
        <link href="https://fonts.googleapis.com/css?family=Mirza:400,500,600,700&display=swap&subset=latin-ext"
            rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Oregano:400,400i&display=swap&subset=latin-ext"
            rel="stylesheet">
            <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    </head>
    <body>
        <header class="container bg-dark">
            <h1 class="text-white py-4 px-2">Půjčování knih</h1>
        </header>
        <main class="container">
