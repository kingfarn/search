<?php

include_once 'header.php';
require_once 'connect.php';


//create_index();

create_pipline();

/*function create_index()
{
    global $client;
    $params = [
        'index' => 'my_index',
    ];
    $response = $client->index($params);
}*/

function create_pipline()
{
    global $client;
    $response = $client->ingest()->putPipeline([
        'id' => 'inl_index',
        'body' => [
            'description' => 'my attachment ingest processor',
            'processors' => [
                [
                    'attachment' => [
                        'field' => 'content',
                        'field' => 'filename'
                    ]
                ]
            ]
        ]
    ]);
}

// // ---------------------------------------

function reArrayFiles(&$file_post)
{
    $file_ary = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for ($i = 0; $i < $file_count; $i++) {
        foreach ($file_keys as $key) {
            $file_ary[$i][$key] = $file_post[$key][$i];
        }
    }

    return $file_ary;
}

if (isset($_FILES['file'])) {
    $file_ary = reArrayFiles($_FILES['file']);

    foreach ($file_ary as $file) {
        $filename = $file['name'];
        move_uploaded_file($file['tmp_name'], 'upload/' . $filename);
        ingest_processor_indexing($filename);
    }
}

// ---------------------------------------

function ingest_processor_indexing($filename)
{
    global $client;
    $params = [
        'index' => 'inl_index',
        'pipeline' => 'attachment',
        'body'  => [
            'filename' => $filename,
            'content' => base64_encode(file_get_contents('./upload/' . $filename))
        ]
    ];
    return $client->index($params);
}

// ---------------------------------------

$params = [
    'index' => 'inl_index',
    'body' => [
        'query' =>  [
            'match_all' => (object)[]
        ]
    ]
];

$results = $client->search($params);
$r = $results["hits"]["hits"];
$total = $results["hits"]["total"];

?>

<div class="container">
    <div class="card mt-3">
        <div class="card-header"><i class="fas fa-file-import"></i></div>
        <div class="card-body">

            <form method="post" enctype="multipart/form-data" action="index.php">
                <div class="">
                    <input type="file" name="file[]" id="file" multiple required>
                </div>
                <div class="mt-2">
                    <button type="submit" name="upload" id="upload" class="btn btn-info"><i class="fas fa-cloud-upload-alt"></i>
                        Upload</button>
                </div>
            </form>
        </div>
    </div>
    <br>
    <div class="card mt-2">
        <div class="card-header"><i class="fas fa-list"></i></div>

        <table class="table">

            <thead>
                <tr>
                    <th>Name</th>
                </tr>
            </thead>

            <?php
            foreach ($r as $key) {
            ?>
                <tbody>
                    <tr>
                        <td><a href="./upload/<?php echo $key['_source']['filename']; ?>
                    " target="_blank" rel="noopener noreferrer"><?php echo $key['_source']['filename']; ?></a></td>
                    </tr>
                </tbody>
            <?php
            }
            ?>

        </table>

    </div>
</div>
<br>

<?php include 'footer.php' ?>
