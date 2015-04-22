# -*- coding: utf-8 -*-
##################################################################################################################################
#                                                       UIoT Team                                                                #
##################################################################################################################################
# This class requires pySerial installed to properly work. Check  http://pyserial.sourceforge.net/ for installation instructions.#
# Code by: Hiro Ferreira (hiro.ferreira@gmail.com)                                                                               #
# Date: 19 feb 2015                                                                                                              #
# Brasília, brazil                                                                                                               #
##################################################################################################################################

import  socket, time, threading, json
from application.helpers.Debug_Handler import *
from application.config.Configuration import *

#print dir()

class Socket_Handler(object):
    """This class handles a socket instace in iNET and Streamming modes.
    
    Attributes
        ip: ip to open socket for/to
        port: port to open socket for/to
        type: string that holds if socket is client or server 
        socket: holds created socket
        debug: sets class to print what it is doing to help debbuging
        max_connections: maximum of connections that a server socket can open simultaneosly
        max_write_retry: integer Holds how many retries should be done when trying to write to port. Put -1 for infinite retries
        write_retry: integer Counter of writing retries
        max_read_retry: integer Holds how many retries should be done when trying to read from port. Put -1 for infinite retries
        read_retry: integer Counter of reading retries
        client_threads: array of all threads created by clients requests on server.
        client_socket: holds latest client socket
    """
    ip = "localhost"
    port = 80
    type = "client"
    socket = None
    max_connections = 10
    debug = True
    i = 0
    max_write_retry=5
    write_retry=0
    max_read_retry=-1
    read_retry=0
    client_threads = {}
    client_sockets = {}
    client_socket = None
   
    def __init__(self, ip = None, port = None, type = None, new_connection_handler = None, auto_start = None,skip_startup_messages= None, max_connections = None):
        """#his method initializes the socket in client or server mode, accordingly to socket type"""

        #Saves data related to this socket
        if ip != None:
            self.ip = ip
        
        if type != None:
            self.type = type
        
        if port != None:
            self.port = port
        
        if auto_start == None:
            auto_start = True
            
        if skip_startup_messages == True:
            skip_startup_messages = True
        else:
            skip_startup_messages = False
            
        if max_connections != None:
            self.max_connections = max_connections
        
        #Starts socket
        if auto_start == True:
            self.start(new_connection_handler,skip_startup_messages)

    def start(self,new_connection_handler= None,skip_startup_messages = False):
        """This method starts socket"""
        if self.type == "server":
            if new_connection_handler == None:
                raise NameError('Socket_Handler: To create a SERVER socket, it is necessary to parse a function or static method to be executed when a new connection arraives')
            else:
                return self.__server_start_socket(new_connection_handler,skip_startup_messages)
        else:
            return self.__client_start_socket(1,skip_startup_messages)
    
    def write(self,message):
        """This method writes data to port. returns True case everything was done correctly or False in case of errors"""
        #Try to write bytes to port
        try:
            if self.type == "server":
                socket = self.client_socket
            else:
                socket = self.socket
            #Wrtie bytes to port
            self.debug("NOTICE", 'SOCKET WRITE', 'Socket ' + self.type + ' writing data...')
            socket.send(message)
            self.debug("NOTICE", 'SOCKET WRITE', 'Socket '+self.type+' finished writing data.')
            
            return True
        
        #case anything goes wrong...
        except Exception as e:
            
            #debug
            self.debug("NOTICE", 'SOCKET WRITE', str(e))
            
            #in case of error, try to reconnect to port and write again
            if((self.max_write_retry == -1) | (self.write_retry < self.max_write_retry)):
                self.write_retry+=1
                self.debug('ERROR', 'SOCKET WRITE', 'Retrying to write '+str(self.write_retry)+'/'+str(self.max_write_retry))
                self.write(message)
                pass
            
            #in case of error and exceeded retries returns false
            else:
                self.write_retry = 0
                return False
    def broadcast(self,message):
        """This method writes data to all client connected. returns True case everything was done correctly or False in case of erros"""
        #Try to write bytes to port
        self.debug('INFO', 'SOCKET BROADCAST', 'Socket '+self.type+' for '+self.ip+':'+str(self.port)+ ' started sending BROADCAST message...')
        if self.type == "server":
                for socket in self.client_sockets:
                    self.client_socket = socket
                    if not self.write(message):
                        self.debug('ERROR', 'SOCKET BROADCAST', 'Socket '+self.type+' finished sending BROADCAST message with ERRORS.')
                        return False
                self.debug('INFO', 'SOCKET BROADCAST', 'Socket '+self.type+' finished sending BROADCAST message with SUCCESS.')
                return True
        else:
            response = self.write(message) 
            success = "SUCCESS"
            if not response:
                success = "ERROR"
            self.debug('INFO', 'SOCKET BROADCAST', 'Socket '+self.type+' finished sending BROADCAST message with '+success+'.')
            return response

    def read(self,amount_of_chars_to_read=None):
        """This method reads bytes from port. returns bytes read, or "None" in case channel has nothing, or "False" in case of erros"""
        
        if self.type == "server":
            socket = self.client_socket
        else:
            socket = self.socket
                    
        #Try to read bytes from port
        self.read_retry = 0
        while True:
            try:
                #debug
                self.debug('INFO','SOCKET READ', 'Socket '+self.type+'  reading bytes...')
                    
                if(amount_of_chars_to_read == None):
                    data = '';
                    received_bytes = None
                    while True:
                        #Read bytes from port
                        received_bytes = socket.recv(1024)
                        if(not received_bytes):
                            break
                        data += received_bytes
                else:
                    data = socket.recv(amount_of_chars_to_read)
                    
                #debug
                self.debug('INFO','SOCKET READ', 'Socket '+self.type+'  finished reading bytes.')
                    
                #Case there is data, returns data
                if data:
                    return data
                #Case there is no data, returns "None"
                else:
                    return None
                
            #case anything goes wrong...
            except Exception as e:
                
                #debug
                self.debug('ERROR','SOCKET READ', str(e))
    
                #in case of error, try to reconnect to port and read again
                if((self.max_read_retry == -1) | (self.read_retry < self.max_read_retry)):
                    self.read_retry+=1
                    self.debug('ERROR','SOCKET READ', 'Socket '+self.type+'  retrying to read '+str(self.read_retry)+'/'+str(self.max_read_retry))
                    time.sleep(1/100)
                    pass
                
                #in case of error and exceeded retries returns false
                else:
                    self.read_retry = 0
                    return False
    
    def read_line(self):
        """This method reads a line from port. returns bytes read, or "None" in case channel has nothing, or "False" in case of erros"""
        if self.type == "server":
            socket = self.client_socket
        else:
            socket = self.socket
        
        #Try to read bytes from port
        self.read_retry = 0
        while True:
            try:
                #debug
                self.debug('INFO','SOCKET READ LINE' , 'Socket '+self.type+ ' reading line...')
                data = ''
                received_a_line = False
                f = socket.makefile()
                while not received_a_line:
                    #converts socket to file and reads the first line
                    data = f.readline()
                    if not data:
                        time.sleep(1)
                    else: 
                        break
                #debug
                self.debug('INFO','SOCKET READ LINE' ,'Socket '+self.type+ ' finished reading line.')
                    
                #Case there is data, returns data
                if data:
                    return data
                #Case there is no data, returns "None"
                else:
                    return None
                
            #case anything goes wrong...
            except Exception as e:
                
                #debug
                self.debug('ERROR','SOCKET READ LINE' , str(e))
    
                #in case of error, try to reconnect to port and read again
                if((self.max_read_retry == -1) | (self.read_retry < self.max_read_retry)):
                    self.read_retry+=1
                    self.debug('ERROR','SOCKET READ LINE' ,'Socket '+self.type+' retrying to read line'+str(self.read_retry)+'/'+str(self.max_read_retry))
                    time.sleep(1/100)
                    pass
                
                #in case of error and exceeded retries returns false
                else:
                    self.read_retry = 0
                    return False
        
    def close(self,connection_id = None):        
        try:
            if self.type == 'server':
                if connection_id != None:
                    client_sockets[connection_id].close()
                    del self.client_threads[connection_id]
                    del client_sockets[connection_id]
                else:
                    for t in self.client_threads:
                        t = None
                    try:
                        self.socket_client.close()
                    except Exception as e:
                        pass
            self.socket.close()
        except Exception as e:
            pass
        
    def __client_start_socket(self,retries=-1,skip_startup_messages = False):
        """This method opens communication to desired port with desired baudrate, and keeps retrying until it does!"""
        
        #Try to open port
        try:
            #creates a client INET, STREAMing socket
            self.socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
            
            #debug
            if not skip_startup_messages:
                self.debug('INFO','SOCKET START' ,'Socket '+self.type+' to reach '+str(self.ip)+':'+str(self.port)+' has been started.')
                
            #Opens port
            self.socket.connect((self.ip,self.port))
            
            #debug
            if not skip_startup_messages:
                self.debug('INFO','SOCKET START' ,'Socket '+self.type+' to reach '+str(self.ip)+':'+str(self.port)+' has been connected.')
        
            return True
        except Exception as e:
            self.debug('ERROR','SOCKET START' ,str(e))
            
            #In case of error, closes socket and wait a second to retry
            self.close()
            time.sleep(1)
            if ((retries == -1) | (retries>0)):
                if(retries > 0):
                    retries -= 1
                pass
            else:
                return False
            
    
    def __server_start_socket(self,new_connection_handler,skip_startup_messages=False):
        """This method starts a server socket to receive connection from other programs client sockets"""
        
        #creates a server INET, STREAMing socket
        self.socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        
        #bind the socket to a public host, and to config port
        self.socket.bind((self.ip, self.port))
        
        #become a server socket
        listen_thread = threading.Thread(target=self.__server_listen_for_incoming_connections_thread, args=([new_connection_handler]))
        listen_thread.daemon=True
        listen_thread.start()
        
        #debug
        if not skip_startup_messages:
            self.debug('INFO','SOCKET START', 'Socket '+self.type+' has been started on port '+str(self.port)+'.')
            
    def __server_listen_for_incoming_connections_thread(self,new_connection_handler):
        self.client_threads = {}
        self.client_sockets = {}
        while True:
            self.socket.listen(self.max_connections)
            self.debug('INFO','SOCKET START','Socket Server listening for new incoming connections on port '+str(self.port)+'...')
            (clientsock, (ip, port)) = self.socket.accept()
            #starts a new thread for each request
            key = self.__get_free_client_unique_key()
            self.client_socket = clientsock 
            self.client_sockets[key] = clientsock
            new_thread = threading.Thread(target=new_connection_handler, args=([self,key]))
            new_thread.daemon=True
            new_thread.start()
            self.client_threads[key] = new_thread
            
    def __get_free_client_unique_key(self):
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
            
    def debug(self,type,title,description):
        if not CONFIG.do_debugging:
            return None
        try:
            description = description.replace("\xFF",'\\xFF')
            class_name = 'socket_handler'
            debug_object = {'type' : str(type).upper(), 'title' : title, 'message':description}
            json_string = json.dumps(debug_object,sort_keys=False)
            DEBUG.log(class_name,json_string)
        except Exception as eq:
            try:
                from application.helpers.Debug_Handler import *
                DEBUG.log(class_name,json_string)
            except Exception as eq2:
                print debug_object    
                print eq
                print eq2
        
