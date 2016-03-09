<?

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