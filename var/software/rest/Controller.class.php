<?

class Controller {

    //variables

    //variables
    public static $csv_file     = '/opt/peer_tracker/db';
    public static $image_path   = 'images';

    /**
     * INDEX
     *
     * @url GET /
     */
    public function index () {

        return json_encode (
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

    }

    /**
     * CLIENTS
     *
     * @url GET /clients
     * @url GET /clients/$client_id
     */
    public function clients ($client_id = null) {

        $client_list = array ();

        //open and read client-list
        if (file_exists (Controller::$csv_file))
            if (($handle = fopen (Controller::$csv_file, "r")) !== false)
                while (($data = fgetcsv ($handle, 1000, ",")) !== false) {

                    $id     = $data[0];
                    $state  = $data[1];
                    $name   = null;
                    $image  = null;

                    //if client-id is given
                    if ($client_id && $id != $client_id)
                        continue;

                    //check image
                    foreach (glob (Controller::$image_path.'/'.$id.'_*.*') as $file)
                        if ($file != null) {

                            $name   = explode ('_', substr ($file, 0, -4))[1];
                            $image  = explode ('/', $file);
                            $image  = $image[count ($image) - 1];

                        }

                    //add to list
                    $client_list[$id] = array ('id' => $id, 'state' => $state, 'name' => $name, 'image' => $image);

                }

        return json_encode ($client_list);

    }

}

?>