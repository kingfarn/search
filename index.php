<?php

//require header && connet the file that creats connection to elasticsearch
include_once 'header.php';
require_once 'connect.php';


//create index function for creating index
//create_index();

//create pipline function for creating pipline add ingest to the index ,
//create attachment

create_pipline();

/*function create_index()
{
    global $client;
    $params = [
        'index' => 'inl_index',
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
//creating arrayfiles for uploading multiplefile together

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

//uploading file/files to upload  folder after that run ingest_processor_indexing function to index file to your index chanig file to base64 binary 

if (isset($_FILES['file'])) {
    $file_ary = reArrayFiles($_FILES['file']);

    foreach ($file_ary as $file) {
        $filename = $file['name'];
        move_uploaded_file($file['tmp_name'], 'upload/' . $filename);
        ingest_processor_indexing($filename);
    }
}

// ---------------------------------------
//pdf files can uploded in 3 ways to index pdf
//1- by ingest attachment plugin like this one.
//2-Mapper Plugins whicha adds attachment type when mapping properties so that documents can be populated with file attachment contents (encoded as base64).
//3-by fscrawler which makes uploading files esaier but for reading and geting back files it's hard becouse you need to manualy run commmond after every upload 
// (all i tried maybe there is a way but i didn't come across it).

//indexing files to your index changing file content to binary becouse elastic doesn't read pdf directory
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
//geting back all documents inside that index
$params = [
    'index' => 'inl_index',
    'body' => [
        'query' =>  [
            'match_all' => (object)[]
        ]
    ]
];
//getting resulats form elasticsearch
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
