# -*- coding: utf-8 -*-
################################################################################################################################
#                                                       UIoT Team															   #
################################################################################################################################
# Code by: Hiro Ferreira (hiro.ferreira@gmail.com)                                                                             #
# Date: 28 jan 2015																									  		   #
# Brasília, brazil																										  	   #
################################################################################################################################

import json

from application.config.Configuration import *
from application.helpers.Debug_Handler import *


class Event_Packet(object):
	start_byte = '\xaa'
	slavecontroller_address = ''
	Packet_type = ''
	Message_id_msb = ''
	Message_id_lsb = ''
	Content_Length = ''
	Pin_Number = ''
	Pin_Value = ''
	ack_uiot_start_byte = ''

	def __init__(self):
		
		self.debug('info','EVENT PACKET INIT','Event Packet initialized!')

	def get_loaded_content_string(self):
		text = "######################"
		text +="\n#### EVENT PACKET ####"
		text +="\n######################"
		text +="\nslavecontroller_addr: "+str(self.slavecontroller_address)
		text +="\nPacket_type: " + str(self.Packet_type)
		text +="\nMessage_id_msb: " + str(self.Message_id_msb)
		text +="\nMessage_id_lsb: " + str(self.Message_id_lsb)
		text +="\nContent_Length: " + str(self.Content_Length)
		text +="\nPin_Number: " + str(self.Pin_Number)
		text +="\nPin_Value: " + str(self.Pin_Value)
		text +="\n######################"
		return text


	def receive_data_from_bytes(self,xbee_bytes):
		self.debug('info','EVENT PACKET - DECODE XBEE', 'Starting to receive bytes...')
		if(xbee_bytes[15] != self.start_byte):
			self.debug('error','EVENT PACKET - DECODE XBEE', 'Wrong packet type for eventing...')
			return False
		else:
			self.debug('info','EVENT PACKET - DECODE XBEE', 'Write packet type! Getting info from bytes:\n'+(' '.join('{:02x}'.format(ord(c)) for c in xbee_bytes)))
			#receive slave controller addr

			self.slavecontroller_address = xbee_bytes[4:12]
			self.slavecontroller_address = "".join("{:02x}".format(ord(c)) for c in self.slavecontroller_address)
			
			#Receive  UIoT Packet identifier
			self.Packet_type = ord(xbee_bytes[15])

			#Receive  UIoT Packet Message id
			self.Message_id_msb = ord(xbee_bytes[16])
			self.Message_id_lsb = ord(xbee_bytes[17])

			#Receive  UIoT Packet pin value length
			self.Content_Length = 0
			i = 1
			while (xbee_bytes[17+i] != '\x00'):
				self.Content_Length += ord(xbee_bytes[17+i])
				i+=1

			#Receive  UIoT Packet pin number
			self.Pin_Number = ord(xbee_bytes[17+i+1])
			
			#Receive  UIoT Packet pin value
			k=0
			self.Pin_Value = 0
			while (k < self.Content_Length):
				self.Pin_Value += ord(xbee_bytes[17+i+2+k])
				k+=1

			self.debug('info','EVENT PACKET - DECODE XBEE', self.get_loaded_content_string())

			#Return response
			return True
	def get_json(self):
		obj_json = {
			"oder":((self.Message_id_msb*16)+self.Message_id_lsb),
			"slavecontroller_address":self.slavecontroller_address,
			"pin_number":self.Pin_Number,
			"data":self.Pin_Value
			}
		return json.dumps(obj_json, sort_keys=False)
	
	def get_ack(self,SUCCESS = True):
		try:
		    self.debug('INFO', 'EVENT PACKET - GENERATE UIOT ACK PACKET', 'Starting generating XBEE uiot_header...')
		    #puts UIOT initial byte of control messages
		    uiot_header = self.start_byte
		    
		    #puts unique message id to which ack is been sent for
		    uiot_header += chr(self.Message_id_msb)
		    uiot_header += chr(self.Message_id_lsb)
		    
		    #puts ACK Status (0x00 = SUCCESS, others are error).
		    status = '\xFF'
		    if SUCCESS == True:
		    	status = '\x00'
		    uiot_header += status
		      
		    #gets zigbee header
		    zigbe_header = self.get_xbee_header(self.slavecontroller_address,len(uiot_header))
		    
		    #generates checksum
		    checksum = self.get_checksum((zigbe_header+ uiot_header)) 
		    
		    #returns result as byte array
		    final_packet = zigbe_header + uiot_header + checksum
		    b = bytes(final_packet)
		    self.debug('INFO', 'EVENT PACKET - GENERATE XBEE ACK PACKET', 'Finished generating XBEE uiot_header with success!')
		    return b
		except Exception as e:
		    self.debug('ERROR', 'EVENT PACKET - GENERATE XBEE ACK PACKET', 'Error while generating uiot_header:'+str(e))
		    return False
		
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
		
	def get_checksum(self,packet):
	    packet = packet[3:]
	    checksum = 0;
	    for c in packet:
	        checksum += ord(c)
	    checksum = (checksum % 256)
	    checksum = 255 - checksum
	    return chr(checksum)
       
	def debug(self,type,title,description):
		if not CONFIG.do_debugging:
			return None
		try:
			description = description.replace("\xFF",'\\xFF')
			class_name = 'event_packet'
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