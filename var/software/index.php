<?

include 'rest/Controller.class.php';

//DELETE

if ($_GET['option'] == 'delete') {

    unlink (Controller::$image_path.'/'.$_GET['image']);

    //set new-state
    $new_data_array = [];

    if (file_exists (Controller::$csv_file))
        if (($handle = fopen (Controller::$csv_file, "r")) !== false) {

            while (($data = fgetcsv ($handle, 1000, "|")) !== false) {

                $client_id  = $data[0];
                $distance   = $data[1];
                $new        = $data[2];

                if ($client_id == $id)
                    if ($new == '0')
                        $new = 1;
                    else
                        if ($new == 'false')
                            $new = 'true';

                $new_data_array[] = array ($client_id, $distance, $new);

            }

            fclose ($handle);

        }

    $handle = fopen (Controller::$csv_file, 'w');

    foreach ($new_data_array as $line)
        fputcsv ($handle, $line, "|");

    fclose ($handle);

}

//INCLUDE

include 'templates/index.template.php';

?>