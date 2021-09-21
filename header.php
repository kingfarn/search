<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Search Elasticsearch</title>
    <style>
        .alert-border {
            border: solid;
            border-radius: 0.25px;
            border-color: blue;
            border-width: 0.5px;
            margin: 10px;
            padding: 5px;
        }
    </style>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" />
    <link href="src/css/all.css" rel="stylesheet">
</head>

<nav class=" navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
    <a class="navbar-brand" href="index.php">Tech</a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
            <li class="nav-item active">
                <a class="nav-link" href="index.php"><i class="fas fa-home"></i><span class="sr-only">(upload)</span></a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="search.php"><i class="fas fa-search"></i></a>
            </li>
        </ul>
    </div>
</nav>

<body>