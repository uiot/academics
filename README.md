#UIoT Middleware
This repository holds all codes related to UIoT Middleware. 

###DOCS

To learn about how it works, please start visiting our website at [uiot.org](http://www.uiot.org) and, after that, read contents in this repositry's [docs folder](https://github.com/UIoT/middleware/tree/master/docs). They are divided as follows:

* [Website with hints](http://uiot.org/)
* [Complete Architectural and Communication Model reference in Portuguese](https://github.com/UIoT/middleware/tree/master/docs/UIoT_Hiro_Dissertation.pdf)
* [Docs for quick references](https://github.com/UIoT/middleware/tree/master/docs/presentations)
* [Scientific Papers ](https://github.com/UIoT/middleware/tree/master/docs/papers)

###INSTALL
Inside [code subfolders](https://github.com/UIoT/middleware/tree/master/codes), there are README.txt files. They contain information on how to install/setup each layer separately. If you click below you will be redirected to such files.

* [MySQL Database used by all layers](https://github.com/UIoT/middleware/tree/master/database/latest_dump.sql)
* [Master Controller - Service Layer - CMS](https://github.com/UIoT/middleware/tree/master/codes/master_controller/1-service_layer/CMS/README.txt)
* [Master Controller - Service Layer - REST API](https://github.com/UIoT/middleware/tree/master/codes/master_controller/1-service_layer/REST_API/README.txt)
* [Master Controller - Control Layer + UPnP API](https://github.com/UIoT/middleware/tree/master/codes/master_controller/2-control_layer/README.txt)
* [Master Controller - Communication Layer](https://github.com/UIoT/middleware/tree/master/codes/master_controller/3-communication_layer/README.txt)
* [Slave Controller - Communication and Execution Layer - Arduino UNO or MEGA](https://github.com/UIoT/middleware/tree/master/codes/slave_controller/arduino/communication_and_execution_layer/README.txt)

In a close future we intend to build an installer to ease installation and updates! In the mean time, please refer to README.txt files or send us email, contact@uiot.or, so that we can help you with it.

###PORTS

Here we present an abstract of TCP Servers Ports used by UIoT layers to communicate one with the other.

##### #Service Layer

Service Layer has ine TCP Server port. It is used to receive Eventing messages from Control Layer. 

* Default eventing port: 12940

##### #Control Layer

Control Layer has two TCP Server ports. The first is used to receive Eventing messages from Communication layer and the other to receive Control messages from Service Layer. 

* Default controlling port: 12936
* Default eventing port: 12937

##### #Communication Layer

Control Layer has two TCP Server ports. The first is used to receive DEBBUGING listenners and the other to receive Control messages from Control Layer. 

* Default controlling port: 12938
* Default debugging port: 12939

###Defalut Database and CMS Users and Passwords
 
##### #Database:
 * Default DB Name: UIoT_Middleware
 * Default DB User: uiot_admin 
 * Default DB Password: admin321
 * Default DB SQL: [latest_dump.sql](https://github.com/UIoT/middleware/tree/master/database/latest_dump.sql)

##### #CMS:
 * Default CMS User: uiot_admin
 * Default CMS Password: admin321


###Final considerations

It sounds complicated yet, be in a short while we intend to put some decent documentation here! If you have any doubts it will be our pleasure to help you! Please Email us at contact@uiot.org

Happy coding! o/
