# -*- coding: utf-8 -*-
################################################################################################################################
#                                                       UIoT Team                                                              #
################################################################################################################################
# Code by: Hiro Ferreira (hiro.ferreira@gmail.com)                                                                             #
# Date: 09 fev 2015                                                                                                            #
# Brasília, Brazil.                                                                                                            #
################################################################################################################################

class Layer_Configuration(object):
    """This class holds configuration data of all other classes
    
    Attributes
        xbee_address: usb file to write and read from xbee module.
        xbee_baudrate: baudaret to talk to xbee module
        control_layer_ip: ip of control layer process to send data through socket
        control_layer_eventing_server_port: port of control layer proccess to send data about new states received from slave controllers
        communication_layer_ip: local ip to respond for control requests
        communication_layer_control_server_port: local port to receive control requests
    """
    #Xbee USB radio config
    xbee_address = '/dev/cu.usbserial-A601D7XR'
    xbee_baudrate = 19200
    xbee_control_packet_retransmission_interval = 1#0.1 #in seconds
    xbee_control_packet_ack_re_read_interval = 0.1#0.001 # in seconds and it must be smaller then xbee_control_packet_retransmission_interval
    zigbee_header_length = 14 # 14 = 17 - 3 => start_delimiter + packte_length_MSB + packte_length_LSB + 14_header_bytes
    zigbee_maximum_payload_length = (256*256-14) # =  MSB*LSB - zigbee_header_length. 
    
    #Communication layer configuration
    control_layer_ip = "127.0.0.1"
    control_layer_eventing_server_port = 12937
    
    #Control layer configuration
    communication_layer_ip = "127.0.0.1"
    communication_layer_control_server_port = 12938
    communication_layer_control_server_max_connections = 10
    
    #Event packet treatment
    event_packet_buffer_max_length = 1000
    
    #debug port configuration
    do_debugging = True
    debug_ip = "127.0.0.1"
    debug_port = 12939
    debug_history_max_length = 100
    debug_max_connections = 10
    debug_event_packet_class = False
    debug_control_packet_class = False
    debug_communication_layer_class = True
    debug_socket_handler_class = False
    debug_usb_handler_class = False
    
    def __init__(self):
        """Inits Layer_Configuration class"""
        self.load_configurations()
    
    def load_configurations(self):
        """ loads configs from database and holds them within local object"""
        pass
        
globals()["CONFIG"] = Layer_Configuration()