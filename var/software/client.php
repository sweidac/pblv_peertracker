<?

include 'rest/Controller.class.php';

//ADD

if ($_POST['option'] == 'add') {

    $id     = $_POST['client_id'];
    $name   = $_POST['name'];
    $phone   = $_POST['phone'];

    $image_array = explode ('.', $_FILES['image']['name']);

    $image_name         = $id.'_'.$name.'_'.$phone.'.'.$image_array[count ($image_array) - 1];
    $image_extension    = $image_array[count ($image_array) - 1];
    $image_path         = Controller::$image_path.'/'.$image_name;

    //remove old image/
	foreach (glob (Controller::$image_path.'/'.$id.'_*_*.*') as $file)
		if ($file != null)
			unlink ($file);

    if (move_uploaded_file ($_FILES['image']['tmp_name'], $image_path)) {

        if (1==2) {
        //check file format
        if (strcasecmp ($image_extension, 'jpg') == 0 || strcasecmp ($image_extension, 'jpeg') == 0)
            $image = imagecreatefromjpeg ($image_path);
        else
        if (strcasecmp ($image_extension, 'png') == 0)
            $image = imagecreatefrompng ($image_path);
        else
            $image = imagecreatefromgif ($image_path);

        //get image-dimensions
        $image_size     = getimagesize ($image_path);
        $image_width    = $image_size[0];
        $image_height   = $image_size[1];

        //generate temp image
        $temp = imagecreatetruecolor (300, 300);

        if ($image_width >= $image_height)
            imagecopyresized ($temp, $image, 0, 0, round (($image_width - $image_height) / 2), 0, 300, 300, $image_height, $image_height);
        else
            imagecopyresized ($temp, $image, 0, 0, 0, round (($image_height - $image_width) / 2), 300, 300, $image_width, $image_width);

        $image = $temp;

        //delete uploaded image
        unlink ($image_path);

        //save resized image
        imagejpeg ($image, Controller::$image_path.'/'.$id.'_'.$name.'.jpg');
        }

        //set new-state
        $new_data_array = [];

        if (file_exists (Controller::$csv_file))
            if (($handle = fopen (Controller::$csv_file, "r")) !== false) {

                while (($data = fgetcsv ($handle, 1000, "|")) !== false) {

                    $client_id  = $data[0];
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

        $handle = fopen (Controller::$csv_file, 'w');

        foreach ($new_data_array as $line)
            fputcsv ($handle, $line, "|");

        fclose ($handle);

    } else
        $error = 'Error uploading image';

    include 'templates/index.template.php';
    return;

}

//INCLUDE

include 'templates/client.template.php';

?>