#######################################################################################
#######################################################################################
##																					 ##
##		UIoT Team. 2015. Service Layer REST API - V 1.0.0		 ##
##																					 ##
#######################################################################################
#######################################################################################
			
This code requires previously a webserver installed that supports PHP. I recommend using XAMPP, which is simple, transparent to operating system and easy to add or remove.

#######################################################################################
#######################################################################################
##											xampp     								 ##
#######################################################################################
#######################################################################################

Download and install from here:
 	+ https://www.apachefriends.org/pt_br/download.html
 After that, add a virtual host on xampp. Follow this link for instructions:
 	+ http://sawmac.com/xampp/virtualhosts/
 	+ add lines below from <VirtualHost *:80> until </VirtualHost>

#######################################################################################
#######################################################################################
										VHOST Config
#######################################################################################
#######################################################################################

	
<VirtualHost *:80>
    ServerAdmin your.email@yourdomain.com
    DocumentRoot "/complete/path/to/php/code/"
    ServerName rest_api.uiot.localhost
    ServerAlias localhost.uiot.rest_api
    ErrorLog "/complete/path/to/php/code/logs/uiot_rest_api-error_log"
    CustomLog "/complete/path/to/php/code/logs/uiot_rest_api-access_log" common
    <Directory "/complete/path/to/php/code/">
            Options Indexes FollowSymLinks Includes execCGI
            AllowOverride All
            Order Allow,Deny
            Allow From All
        </Directory>
</VirtualHost>



#######################################################################################
#######################################################################################
                            CONFIG FILE
#######################################################################################
#######################################################################################

    + configure DB credentials on Master_Controller/1-Service_Layer/REST_API/database/db_config.php

#######################################################################################
#######################################################################################
										POST Install test
#######################################################################################
#######################################################################################

	+ access http://rest_api.uiot.localhost/ and see what happens =)


