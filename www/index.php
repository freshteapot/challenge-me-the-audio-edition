<?php

require __DIR__ . '/../src/Slim/Slim/Slim.php';
\Slim\Slim::registerAutoloader();

$app = new \Slim\Slim();

/*
GET challenge/:uuid
    return data including responses

Example:
curl "http://freshteapot.dev/challengeme/index.php/challenge/12345"
 */
$app->get('/challenge/:uuid', function ($uuid) {
    $item = array(
            'uuid' => $uuid,
            'title' => 'Hello',
            'data' => array(
                    'type' => 'audio',
                    'url' => 'URL_TO_AUDIO'
            ),
            'responses' => array()
    );
    echo '<pre>' . var_export($item, true) . '</pre>';
});

/**
 POST challenge/create
     type
         - required
         audio,
         text
     title
         - optional
     content
         - optional
     data
         - optional
         base64encoded
         only allow allowed formats.

Example
curl -X POST "http://freshteapot.dev/challengeme/index.php/challenge/create" -H "Content-Type: application/json"  -d '
{
    "content": "I am content", 
    "data": "", 
    "title": "Hello I am a title", 
    "type": "audio"
}
'

*/
$app->post('/challenge/create', function () {
    $input = file_get_contents('php://input');
    $input = json_decode($input, true);
/*
    $item = array(
            'type' => 'audio',
            'title' => 'Hello I am a title',
            'content' => 'I am content',
            'data' => '',
    );
    echo json_encode($item);
    exit;
*/
    echo '<pre>' . var_export($input, true) . '</pre>';
});


/**
 PATCH challenge/:uuid
 PUT - until Slim is patched
     title
     data (look at post)

Example:
curl -X PUT "http://freshteapot.dev/challengeme/index.php/challenge/123" -H "Content-Type: application/json"  -d '
{
    "content": "I am CONTENT",
    "title" : "I am a better title"
}
'
 */
$app->put('/challenge/:uuid', function ($uuid) use ($app) {

    //@todo - Allow patching of title
    //@todo - Allow patching type data

    /*
     * Only allow changing data for:
     *     title
     *     content
     *     data
     *         data updated based on type.
     *
     * Look up data based on uuid.
     *     What type is it?
     *         Add based on type.
     */
    $input = $app->request()->getBody();
    $input = json_decode($input, true);

    echo '<pre>' . var_export($input, true) . '</pre>';
});

/**
 * DELETE challenge/:uuid

Example:
curl -X DELETE "http://freshteapot.dev/challengeme/index.php/challenge/123"
 */
$app->delete('/challenge/:uuid', function ($uuid) {
    echo "Delete Challenge uuid:{$uuid}\n";
});

/****************************************************************************/

/**
 *    GET response/:uuid
 *        return response
 */
$app->get('/response/:uuid', function ($uuid) {
    $item = array(
            'uuid' => $uuid,
            'parent_uuid' => '123456',
            'data' => array(
                    'type' => 'text',
                    'content' => 'I am the walrus.'
            ),
    );
    echo '<pre>' . var_export($item, true) . '</pre>';
});


/**
      POST response/create
         parent
             - required
             uuid of challenge
         type
             - required
             ['text']
         data
             - required
     Example
curl -X POST "http://freshteapot.dev/challengeme/index.php/response/create" -H "Content-Type: application/json"  -d '
{
    "parent": "1234",
    "type": "text",
    "data": "I am a response"
}
'
    
 */
$app->post('/response/create', function () use ($app){
    $input = $app->request()->getBody();
    $input = json_decode($input, true);
    echo '<pre>' . var_export($input, true) . '</pre>';
});

/**
//@todo should be patch
Example:
curl -X PUT "http://freshteapot.dev/challengeme/index.php/response/123" -H "Content-Type: application/json"  -d '{
    "data": "I an updated response"
}'
 */
$app->put('/response/:uuid', function ($uuid) use ($app) {
    //@todo - Allow patching type data
    /*
     * Only allow changing data for:
     *     data
     *         data updated based on type.
     *
     * Look up data based on uuid.
     *     What type is it?
     *         Add based on type.
     */
    $input = $app->request()->getBody();
    $input = json_decode($input, true);

    echo '<pre>' . var_export($input, true) . '</pre>';
});

/**
 * DELETE Response/:uuid

Example:
curl -X DELETE "http://freshteapot.dev/challengeme/index.php/response/123"
 */
$app->delete('/response/:uuid', function ($uuid) {
    echo "Delete Response uuid:{$uuid}\n";
});


$app->run();
