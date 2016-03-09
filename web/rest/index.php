<?

//namespace Jacwright\RestServer;

//include 'server/RestServer.php';

//include 'Controller.class.php';

//server mode: [debug] [production]
//$mode = 'debug';

//init server
//$server = new RestServer ($mode);

//add route-controller
//$server -> addClass ('Controller');

//start handling
//$server -> handle ();

echo json_encode (
    array (
        'name'      => 'REST API CHILD-TRACKER',
        'routes'    => array (
            array (
                'route'         => '/clients',
                'description'   => 'get list of clients'
            ),
            array (
                'route'         => '/clients/[client-id]',
                'description'   => 'get client by id'
            )
        )
    )
);

?>