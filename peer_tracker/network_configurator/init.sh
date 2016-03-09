#!/bin/ash

######################################################################################################################
############## Change this values to adjust the paths ################################################################
######################################################################################################################

#This is the path to the Usb Detection script
usb_detection_path=/peertracker/UsbDetection.py

#This is the path to the master script
#master_script_path=/var/peer_tracker/MasterCore.py
master_script_path=/peertracker/dummy.py

#This is the path where the config files for the master and child module are stored
config_source_path=/peertracker

#This is the path where the config file of the current module should be placed
config_destination_path=/etc/config

#This is the path to the localconfig file which dertermines which kind of module is currently running
local_config_path=/peertracker/localconfig

#The path to the server application
server_application_path=/etc/init.d/lighttpd

#The postfix for child configurations
child_postfix=_childmodule
#The postfix for master configurations
master_postfix=_mastermodule

######################################################################################################################

#initialization for childmodule
init_child()
{
	echo "child detected"
	copy_files $child_postfix

	/etc/init.d/network restart
	echo "module switched to child"
}

#initialization for mastermodule
init_master()
{
	echo "master detected"
	copy_files $master_postfix

	/etc/init.d/network restart

	echo "module switched to master"
	if [ -e "$master_script_path" ]
	then

		echo "starting master script"
		python $master_script_path > /dev/null 2>&1 &
		
		echo "starting server application"
		$server_application_path start > /dev/null 2>&1 &

	fi

}


copy_files()
{
	copy_file network $1
	copy_file firewall $1 
	copy_file wireless $1
}

copy_file()
{

	file=$1
	postfix=$2

	file_path=$config_source_path/$file$postfix

		if [ -e "$file_path" ]
		then

			destination_file_path=$config_destination_path/$file
				
			echo "copy $file_path to $destination_file_path"

			cp $file_path $destination_file_path
			
		fi
}

#Checks if module is master or child
#	0 is returned when child
#	1 is returned when master
is_master()
{

	python $usb_detection_path

	file_content=`cat $local_config_path`


	if [ "$file_content" == "master" ]
	then
		retval=1
	else
		retval=0
	fi

	return $retval
}



is_master
master="$?"

if [ "$master" == "1" ]
then
	init_master
else

	init_child
fi
