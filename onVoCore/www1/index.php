<?

include 'rest/Controller.class.php';

if (empty ($_POST['distance'])) {

    if (file_exists (Controller::$distance_file_linux))
        $distance_file = Controller::$distance_file_linux;
    else
        $distance_file = Controller::$distance_file_windows_2;

    if (file_exists ($distance_file))
        $_POST['distance'] = file_get_contents ($distance_file);

}

//DELETE

if ($_GET['option'] == 'delete') {

    unlink (Controller::$image_path.'/'.$_GET['image']);

    //set new-state
    $new_data_array = [];

    if (file_exists (Controller::$csv_file_linux))
        $db_file = Controller::$csv_file_linux;
    else
        $db_file = Controller::$csv_file_windows_2;

    if (file_exists ($db_file))
        if (($handle = fopen ($db_file, "r")) !== false) {

            while (($data = fgetcsv ($handle, 1000, "|")) !== false) {

                $client_id  = substr (str_replace (':', '', $data[0]), -4);
                $distance   = $data[1];
                $new        = $data[2];

                if ($client_id == $id)
                    if ($new == '0')
                        $new = '1';
                    else
                        if ($new == 'false')
                            $new = 'true';

                $new_data_array[] = array ($client_id, $distance, $new);

            }

            fclose ($handle);

        }

    if (file_exists ($db_file))
        if (($handle = fopen ($db_file, "w")) !== false) {

            foreach ($new_data_array as $line)
                fputcsv ($handle, $line, "|");

            fclose ($handle);

        }

}

if ($_POST['option'] == 'save_distance') {

    if (file_exists (Controller::$distance_file_linux))
        $distance_file = Controller::$distance_file_linux;
    else
        $distance_file = Controller::$distance_file_windows_2;


    file_put_contents ($distance_file, $_POST['distance']);

}

//INCLUDE

include 'templates/index.template.php';

?>