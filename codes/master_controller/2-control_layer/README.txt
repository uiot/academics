#######################################################################################
#######################################################################################
##																					 ##
##		UIoT Team. 2015. Control Layer Python implementation version 1.0		 ##
##																					 ##
#######################################################################################
#######################################################################################

This is the hardest part of installation... HERQQ can be very tricky depending on your environemt. Ubuntu tests has shown very good for this. The code is also messed up. To have a starting point, refer to codes under master_controller/2-control_layer/apps/upnp-handler/. Other locations are HERQQ UPnP files, so you can mostly 'ignore' them.

#######################################################################################
#######################################################################################
##					Installing in Mac OS X Mavericks								 ##
#######################################################################################
#######################################################################################
			
1 - Download HERQQ1.0.0 from  http://www.herqq.org
2 - Install QT4: brew install qt
3 - Download and install QtCreator from https://qt-project.org/downloads
4 - Install HUPnP through tutorail in http://www.herqq.org/html/hupnp_v1/index.html
5 - QTCreator wont open project before adding qmake to it. PRess 'CMD'+',' select Build&Run, select tab 'QtVersion', click ADD and put following path: /usr/local/Cellar/qt/4.8.5/bin/qmake. Select option BUILD ALL and follow to tab OPTIONS. There choose QT 4.8.6 and click APPLY, them OK.
6 - Build it.
7 - If you experience build/make erros, please refer to
		+ http://sourceforge.net/p/hupnp/bugs/2/
		+ http://svnweb.freebsd.org/ports/head/net/hupnp/files/patch-hmulticast_socket.cpp?view=markup
8 - Errors on build mostly happen because it can not find a library which is badly included(something with soap for UPnP), so, you can either find where it is included and fix it, or create the requested directory with requested files, which also are inside project.


#######################################################################################
#######################################################################################
                            CONFIG FILE
#######################################################################################
#######################################################################################

    + configure DB credentials on Master_Controller/2-Control_Layer/apps/upnp-handler/Database/database.h
    + for other configs, please refer to .h files contained in subfolders of direcotry codes/master_controller/2-control_layer/apps/upnp-handler/