<?php

//require header && connet the file that creats connection to elasticsearch

include_once 'header.php';
require_once 'connect.php';
//make empty array of query with null hits and totals so we don't get erorr .
$query = [];
$query['hits']['total'] = null;

//chicking if searhing button has been pressed if so difine searching as ewqulas get request of button
//build a query that searching inde your index form body(_source)with query that looking for searching as string 

if (isset($_GET['searching'])) {
    $searching = $_GET['searching'];
    $query = $client->search(
        [
            'index' => 'inl_index',
            'body' => [

                'query' => [
                    'query_string' => [

                        'query' => $searching

                    ]

                ]
            ]
        ]
    );
}

//getting back hits 
if ($query['hits']['total'] >= 1) {
    $results = $query['hits']['hits'];
}


?>

<body>
    <div class="container">
        <div class="card mt-3">
            <div class="card-header"><i class="fas fa-list"></i></div>
            <div class="card-body">
                <form action="search.php" method="get" autocomplete="off">
                    <input type="text" dir="rtl" name="searching" value="<?php echo (isset($searching) ? ($searching) : '') ?>" class="form-control">
                    <input type="submit" name="submit" class="btn btn-outline-primary mt-2" value="search">
                </form>
            </div>
        </div>
        <br>

        <div>
            <h6>Search Result Found In : <?php echo (isset($searching) ? ($searching) : '') ?></h6>
        </div>
        <?php



        if (isset($results)) {

            foreach ($results as $r) {

        ?>
                <div class="alert-border">
                    File Name: <a href="./upload/<?php echo $r['_source']['filename']; ?>
                    " target="_blank" rel="noopener noreferrer"><?php echo $r['_source']['filename']; ?></a>
                </div>
        <?php
            }
        }
        ?>
    </div>
    <script>
        $("submit").click(function() {
            $("p").append("<b>Appended text</b>");
        })
    </script>

    <?php include 'footer.php' ?>
