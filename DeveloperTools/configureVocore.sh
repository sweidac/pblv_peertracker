#!/bin/bash

source ./vocore.sh

vocoreSsh "/etc/init.d/lighttpd restart"
vocoreSsh "/etc/init.d/lighttpd disable"


vocoreSsh "/etc/init.d/uhttpd restart"
vocoreSsh "/etc/init.d/uhttpd disable"




