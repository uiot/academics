#######################################################################################
#######################################################################################
##                                           ##
##              UIoT Team. 2015. Database - V 1.0.0                    ##
##                                           ##
#######################################################################################
#######################################################################################
      
This directory holds MySQL files. The folder ./db_migrate holds incremental database updates, in other words, you should run each script in time order consecutively. If you have nothing installed on your computer, install ./latest_dump.sql and save the date you did it. Once in a while, update your git and check if is there any db_migrate sql dated after your latest_dump.sql installation. If there is, just run those scripts and they will update your database, because they only have what was updated.