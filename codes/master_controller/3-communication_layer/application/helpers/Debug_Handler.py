# -*- coding: utf-8 -*-
################################################################################################################################
#                                                       UIoT Team															   #
################################################################################################################################
# Code by: Hiro Ferreira (hiro.ferreira@gmail.com)                                                                             #
# Date: 15 jan 2015																									  		   #
# Brasília, brazil																										  	   #
################################################################################################################################

import time, socket, threading, sys
from application.config.Configuration import *
import string

class Debug_Handler(object):
    
    also_print_data = False
    
    socket = None    
    max_connections = 10
    ip = '127.0.0.1'
    port = 12939
    client_sockets = {}
    history = []
    history_max_length = 0
    def __init__(self):
        if CONFIG.do_debugging:
            # setus up configuration for server socket to listen for control requests
            self.ip = CONFIG.debug_ip
            self.port = CONFIG.debug_port
            self.max_connections = CONFIG.debug_max_connections
            self.history_max_length = CONFIG.debug_history_max_length
            
            """This method starts a server socket to receive connection from other programs client sockets"""
            
            #creates a server INET, STREAMing socket
            self.socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            
            #bind the socket to a public host, and to config port
            self.socket.bind((self.ip, self.port))
            
            #become a server socket thread
            listen_thread = threading.Thread(target=self.__listen_for_incoming_connections, args=([]))
            listen_thread.daemon=True
            listen_thread.start()
            
    def __listen_for_incoming_connections(self):
        self.client_threads = {}
        self.client_sockets = {}
        print('DEBUGGER LISTENNING FOR NEW CONNECTIONS ON PORT '+str(self.port))
        while True:
            try:
                #starts a new connection
                self.socket.listen(self.max_connections)
                (clientsock, (ip, port)) = self.socket.accept()
                
                #Saves socket to send any new debug message
                key = self.__get_unique_key()
                self.client_sockets[key] = clientsock
                
                #Sends history messages to new listener
                self.__send_history(key)
                
            except Exception as e:
                print e
                pass
            
    def __send_history(self,socket_key):
        for l in  self.history:
            self.client_sockets[socket_key].send(l)
    
    def __get_unique_key(self):
        number_count = 0
        char_count = 0
        char_to_try = ''
        chars_list = list(map(chr, range(56, 123)))
        acumulated_word = ''
        key = ''
        while True:
            key = char_to_try + str(number_count) + acumulated_word
            if not self.client_sockets.has_key(key):
                break
            number_count+=1
            if number_count == sys.maxint - 2:
                number_count=0
                
                if(char_count == len(chars_list)):
                    char_count = 0
                    char_to_try = ''
                    acumulated_word = key
                else:
                    char_to_try = chars_list[char_count]
                    char_count+=1
                
        return key
        
    def input_handler(self, connection_id):
        if CONFIG.do_debugging:
            print('DEBUG[' + connection_id + ']  started with success:')
        
    
    def log(self, sender, message):
        if CONFIG.do_debugging:
            send_message = False
            
            if sender == 'socket_handler' and CONFIG.debug_socket_handler_class == True:
                send_message = True
            if sender == 'usb_handler' and CONFIG.debug_usb_handler_class == True:
                send_message = True
            if sender == 'event_packet' and CONFIG.debug_event_packet_class == True:
                send_message = True
            if sender == 'communication_layer' and CONFIG.debug_communication_layer_class == True:
                 send_message = True
            if sender == 'control_packet' and CONFIG.debug_control_packet_class == True:
                 send_message = True
                
            if(send_message):
                for key in self.client_sockets.keys():
                    sock = self.client_sockets[key]
                    try:
                        sock.send(message +'\n')
                    except Exception as e:
                        print 'DEU TRETA:'
                        print e
                        print 'DEBUG FAIL: ' +message
                        sock.close()
                        del self.client_sockets[key]
                        print self.client_sockets
                        
                if self.also_print_data:
                    print message
                self.history.append(str(message) +'\n')
                if len(self.history) >= self.history_max_length :
                    del self.history[0]

DEBUG = Debug_Handler()