<?

include '../Controller.class.php';

if (!empty ($_GET['client_id']))
    $client_id = $_GET['client_id'];
else
    $client_id = null;

$client_list = array ();

//open and read client-list
if (file_exists (Controller::$csv_file))
    if (($handle = fopen (Controller::$csv_file, "r")) !== false)
        while (($data = fgetcsv ($handle, 1000, "|")) !== false) {

            $id         = $data[0];
            $distance   = $data[1];
            $name       = null;
            $image      = null;

            //if client-id is given
            if ($client_id && $id != $client_id)
                continue;

            //check image
            foreach (glob ('../../'.Controller::$image_path.'/'.$id.'_*.*') as $file)
                if ($file != null) {

                    $name   = explode ('_', substr ($file, 0, -4))[1];
		    $phone   = explode ('_', substr ($file, 0, -4))[2];
                    $image  = explode ('/', $file);
                    $image  = $image[count ($image) - 1];

                }

            //add to list
            $client_list[$id] = array ('id' => $id, 'distance' => $distance, 'name' => $name, 'phone' => $phone, 'image' => $image);

        }

echo json_encode ($client_list);

?>