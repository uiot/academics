# -*- coding: utf-8 -*-
################################################################################################################################
#                                                       UIoT Team                                                               #
################################################################################################################################
# Code by: Hiro Ferreira (hiro.ferreira@gmail.com)                                                                             #
# Date: 03 mar 2015                                                                                                                 #
# Brasília, brazil                                                                                                                 #
################################################################################################################################

import json, sys, time, yaml

from application.config.Configuration import *
from application.helpers.Debug_Handler import *


class Control_Packet(object):
    __message_id = None
    start_byte = '\xFF'
    slavecontroller_address = None
    pin_number = None
    pin_type = None
    data_type = None
    baud_rate = None
    data = None
    ack_status = False
    
    __slave_controllers_and_ids = {}

    def __init__(self):
        self.debug('INFO', 'CONTROL PACKET INIT', 'Control Packet initialized!')
        #creates unique id
        self.__message_id = Control_Packet.__get_new_message_id(self.slavecontroller_address)

    def __del__(self):
        self.free_id()

    def get_id(self):
        return str(self.slavecontroller_address)+'-'+str(self.__message_id)
            
    def get_loaded_content_string(self):
        text = "###########################"
        text += "\n##### CONTROL PACKET ####"
        text += "\n########################"
        text += "\n slavecontroller_address: " + str(self.slavecontroller_address)
        text += "\n start_byte: " + str(self.start_byte)
        text += "\n message_id: " + str(self.__message_id)
        text += "\n data_size: " + str(len(self.data))
        text += "\n pin_number: " + str(self.pin_number)
        text += "\n pin_type: " + str(self.pin_type)
        text += "\n data_type: " + str(self.data_type)
        text += "\n baud_rate: " + str(self.baud_rate)
        text += "\n data: " + str(self.data)
        text += "\n#######################"
        return text


    def get_data_from_json_string(self, string_json):
        self.debug('INFO', 'CONTROL PACKET - DECODE JSON', 'Received following JSON String:' + string_json)
        try:
            #converts json string into local object attributes
            json_object = yaml.load(string_json)
            if len(json_object['slavecontroller_address']) != 16:
                raise Exception('Invalid Slave Controller address. it should be an 8 byte address(64 bits).')
            self.slavecontroller_address = json_object['slavecontroller_address']  # 0013A20040A14151
            self.pin_number = json_object['pin_number']     # 13
            self.pin_type = json_object['pin_type']         # DIGITAL
            self.data_type = json_object['data_type']       # BOOLEAN
            self.baud_rate = int(json_object['baud_rate'])  # 0
            self.data = json_object['data']                 # HIGH
            response = True
            self.debug('INFO', 'CONTROL PACKET - DECODE JSON', 'JSON decoded with success. Data collected:'+self.get_loaded_content_string())
        except Exception as e:
            self.debug('ERROR', 'CONTROL PACKET - DECODE JSON', 'JSON not decoded with success. error:' + str(e))
            response = False
        
        # Return response
        return response
    
    def get_json_string(self):
        self.debug('INFO', 'CONTROL PACKET - GET JSON', 'Generating JSON string of control packet...')
        #converts local object attributes into a json string and return it
        obj_json = {
                    "slavecontroller_address": self.slavecontroller_address,
                    "message_id": self.__message_id,
                    "data_size": len(self.data),
                    "pin_number": self.pin_number,
                    "pin_type": self.pin_type,
                    "data_type": self.data_type,
                    "baud_rate": self.baud_rate,
                    "data": self.data,
                    }
        response =  json.encoded_str(obj_json)
        self.debug('INFO', 'CONTROL PACKET - GET JSON', 'JSON string done.')
        return response
    
    def get_xbee_header(self,destination_xbee_addr,data_length):
        zigbee_start_byte = '\x7E'
        
        #Frame type set to "ZigBee Transmit Request"
        transmit_request_frame_type = '\x10'
        
        #Start delimiter
        header  = zigbee_start_byte                         
        
        #Adds zigbee header length, without 3 first bytes(start_delimiter MSB and LSB), to packet length
        data_length += CONFIG.zigbee_header_length
        # It is important to notice that data_lengths can't be larger then 16 bits (65536-14 bytes/chars of ZigBee payload).
        # (8 bits of MSB and 8 bits of LSB, total integer 256*256). 
        # lengths higher then 256*256 bits will be truncated and ignored, as well as data. 
        # Future implementation should split data and send in multiple packets.
        
        #Truncate data_length to 16 bits <=> 2 bytes
        data_length = data_length%(256*256)
        
        #Calculates MSB and LSB
        LSB = data_length % 256 # gets last  8 bits => 0000 0000 1111 1111
        MSB = data_length >> 8  # gets first 8 bits => 1111 1111 0000 0000 
        
        #Adds MSB of packet length
        header += chr(MSB)                 
        
        #Adds LSB of packet length
        header += chr(LSB)                         
        
        #Adds Frame type
        header += transmit_request_frame_type               
        
        #Frame ID - No response needed  because not in a ZigBee UART like transmission, so set to 0 to request no response.
        header += '\x00'                                    
    
        #Integrating the 64-bit destination address in the packet
        chars = ''
        for c in destination_xbee_addr:
            chars += c
            if len(chars) == 2:
                header += chr(int(chars,16))
                chars = ''
    
        #Set the 16-bit destination address. Since it is unknown, we put it to zero to force a network search. Causes latency!!! Must be improved in future versions.
        header += '\xFF'   
        header += '\xFE'   
    
        #Broadcast radius - Max hops before achive final hop. 0 means infinite.
        header += '\x00'                            
        # other option - 00 means none.
        header += '\x00'       
        
        return header
    
    def get_xbee_checksum(self,packet):
        packet = packet[3:]
        checksum = 0;
        for c in packet:
            checksum += ord(c)
        checksum = (checksum % 256)
        checksum = 255 - checksum
        return chr(checksum)
            
    def received_ack(self,ack_status):
        self.ack_status = ack_status
        
    def sucessfuly_sent(self):
        if self.ack_status != False:
            return True
        else:
            return False
            
    def get_xbee_packet(self):
        """Generates a XBEE uiot_header compliant with ZigBee."""
        try:
            self.debug('INFO', 'CONTROL PACKET - GENERATE XBEE PACKET', 'Starting generating XBEE uiot_header...')
            #puts UIOT initial byte of control messages
            uiot_header = self.start_byte
            
            #puts unique id
            if self.__message_id < 256:
                uiot_header += '\x00'
            uiot_header += Control_Packet.get_ascii_string_of_int(int(self.__message_id))
             
            #puts data length
            type = self.data_type.upper()
            if type in ['INTEGER','INT','LONG','BIGINT']:
                if type in ['INTEGER','INT']:
                    data = int(self.data)
                else:
                    data = long(self.data)
                length_value = data/256
                if (data+1)%256 > 0:
                    length_value +=1
                uiot_header+= Control_Packet.get_ascii_string_of_int(length_value)
            elif type in ['BOOLEAN','BOOL']:
                uiot_header+= '\x01'
            else:
                uiot_header += Control_Packet.get_ascii_string_of_int(len(str(self.data)))
            uiot_header += '\x00'

            #puts pin number
            uiot_header += Control_Packet.get_ascii_string_of_int(int(self.pin_number))
            
            #puts pin's operation mode, data type and baud rate
            op_mode = self.get_code_for_pin_operation_mode(self.pin_type) << 6
            data_t = self.get_code_for_data_type(self.data_type) << 4
            baud_r = self.get_code_for_baud_rate(self.baud_rate)%256
            custom_byte =  op_mode +  data_t + baud_r
            custom_byte = Control_Packet.get_ascii_string_of_int(custom_byte)
            uiot_header += custom_byte
            
            #truncates data to maximum allowed by zigbee packet
            data = self.data[:CONFIG.zigbee_maximum_payload_length]
            
            #puts data
            type = self.data_type.upper()
            uiot_data = '\x00'
            if type in ['INTEGER','INT']:
                uiot_data+= Control_Packet.get_ascii_string_of_int(int(data))
            elif type in ['BOOLEAN','BOOL']:
                if data.upper() == 'HIGH' or data.upper() == 'ON' or data.upper() == '1':
                    uiot_data = '\x01'
                elif data.upper() == 'LOW' or data.upper() == 'OFF' or data.upper() == '0':
                    uiot_data = '\x00'
            elif type == 'LONG':
                uiot_data = Control_Packet.get_ascii_string_of_int(long(data))
            else:
                uiot_data = str(data)
            
            #gets zigbee header
            zigbe_header = self.get_xbee_header(self.slavecontroller_address,len(uiot_header + uiot_data))
            
            #generates checksum
            checksum = self.get_xbee_checksum((zigbe_header+ uiot_header + uiot_data)) 
            
            #returns result as byte array
            final_packet = zigbe_header+ uiot_header + uiot_data + checksum
            b = bytes(final_packet)
            self.debug('INFO', 'CONTROL PACKET - GENERATE XBEE PACKET', 'Finished generating XBEE uiot_header with success!')
            return b
        except Exception as e:
            self.debug('ERROR', 'CONTROL PACKET - GENERATE XBEE PACKET', 'Error while generating uiot_header:'+str(e))
            return False
    
    
    @staticmethod
    def get_code_for_pin_operation_mode(pin_type_string):
        type = pin_type_string.upper()
        if type == 'DIGITAL':
            code = 0 #00
        elif type == 'ANALOG':
            code = 1 #01
        elif type == 'PWM':
            code = 2 #10
        elif type == 'UART':
            code = 3 #11
        else:
            code = 0
        return code
    
    @staticmethod
    def get_code_for_data_type(data_type_string):
        type = data_type_string.upper()
        if type in ['INTEGER','INT']:
            code = 0 #00
        elif type in ['BOOLEAN','BOOL']:
            code = 1 #01
        elif type in ['ASCII','CHAR','STRING','CHR']:
            code = 2 #10
        elif type == 'LONG':
            code = 3 #11
        else:
            code = 0
        return code 
    
    @staticmethod
    def get_code_for_baud_rate(baud_rate_string):
        baud_rate = int(baud_rate_string)
        if baud_rate == 0:
            code =  0  #0000
        elif baud_rate == 75:
            code =  1  #0001
        elif baud_rate == 150:
            code =  2  #0010
        elif baud_rate == 300:
            code =  3  #0011
        elif baud_rate == 600:
            code =  4  #0100
        elif baud_rate == 1200:
            code =  5  #0101
        elif baud_rate == 2400:
            code =  6  #0110
        elif baud_rate == 4800:
            code =  7  #0111
        elif baud_rate == 9600:
            code =  8  #1000
        elif baud_rate == 14400:
            code =  9  #1001
        elif baud_rate == 16457:
            code =  10 #1010
        elif baud_rate == 19200:
            code =  11 #1011
        elif baud_rate == 28800:
            code =  12 #1100
        elif baud_rate == 38400:
            code =  13 #1101
        elif baud_rate == 57600:
            code =  14 #1110
        elif baud_rate == 11520:
            code =  15 #1111
        else:
            code = 0
        return code
    
    @staticmethod
    def get_ascii_string_of_int(int_to_be_converted):
        if int_to_be_converted == 0:
            return '\x00'
        rounds = int_to_be_converted/256
        response = ''
        if(int_to_be_converted%256>0):
            response += chr(int_to_be_converted%256)
        while rounds >0 :
            response += '\xFF'
            rounds-=1
        return response
    
    @staticmethod
    def __get_new_message_id(slave_controller_address):
        #gets a new message id for each slave controller varying from 0 to 256*256 (MSB and LSB allowed by UIoT Message protocol)  
        slave_controller_address = str(slave_controller_address)
        if slave_controller_address not in Control_Packet.__slave_controllers_and_ids:
            Control_Packet.__slave_controllers_and_ids[slave_controller_address] = []
        message_id = 0
        while True:
            if message_id not in Control_Packet.__slave_controllers_and_ids[slave_controller_address]:
                break
            else:
                message_id+=1
                if message_id >= (256*256-1):
                    sleep(1)
                    message_id=0
                
        Control_Packet.__slave_controllers_and_ids[slave_controller_address].append(message_id)
        return message_id
        
    def free_id(self):
        return Control_Packet.__release_id(self.slavecontroller_address,self.__message_id)
        
    @staticmethod 
    def __release_id(slave_controller_address,message_id):
        if  (slave_controller_address in Control_Packet.__slave_controllers_and_ids) and (message_id in Control_Packet.__slave_controllers_and_ids[slave_controller_address]):
            Control_Packet.__slave_controllers_and_ids[slave_controller_address].remove(message_id)
            return True
        return False
    @staticmethod
    def DEBUG(type, title, description):
        o = Control_Packet()
        o.debug(type, title, description)
        
    def debug(self, type, title, description):
        if not CONFIG.do_debugging:
            return None
        try:
            description = description.replace("\xFF",'\\xFF')
            class_name = 'control_packet'
            debug_object = {'type' : str(type).upper(), 'title' : title, 'message':description}
            json_string = json.dumps(debug_object, sort_keys=False)
            DEBUG.log(class_name, json_string)
        except Exception as eq:
            try:
                from application.helpers.Debug_Handler import *
                DEBUG.log(class_name, json_string)
            except Exception as eq2:
                print debug_object    
                print eq
                print eq2
