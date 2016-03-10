<?

ini_set('display_errors',1);
error_reporting(-1);

include 'rest/Controller.class.php';

//ADD Client

if ($_POST['option'] == 'add') {

    $id         = str_replace (':', '',$_POST['client_id']);
    $name       = $_POST['name'];
    $phone      = $_POST['phone'];
    $image_data = $_POST['image_data'];

    $image_name         = $id.'_'.$name.'_'.$phone.'.jpeg';
    $image_path         = Controller::$image_path.'/'.$image_name;

    //remove old image/
	foreach (glob (Controller::$image_path.'/'.$id.'_*_*.*') as $file)
		if ($file != null)
			unlink ($file);

    $image_data = str_replace ('data:image/jpeg;base64,', '', $image_data);
    $image_data = str_replace (' ', '+', $image_data);
    $image_data = base64_decode ($image_data);

    if (!file_put_contents ($image_path, $image_data))
        $error = 'Error uploading image';
    else {

        //set new-state
        $new_data_array = [];

        if (file_exists (Controller::$csv_file_linux))
            $db_file = Controller::$csv_file_linux;
        else
            $db_file = Controller::$csv_file_windows_2;

        if (file_exists ($db_file))
            if (($handle = fopen ($db_file, "r")) !== false) {

                while (($data = fgetcsv ($handle, 1000, "|")) !== false) {

                    $client_id  = substr (str_replace (':', '', $data[0]), -4);;
                    $distance   = $data[1];
                    $new        = $data[2];

                    if ($client_id == $id)
                        if ($new == '1')
                            $new = '0';
                        else
                        if ($new == 'true')
                            $new = 'false';

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

    include 'templates/index.template.php';
    return;

}

//INCLUDE

include 'templates/client.template.php';

?>