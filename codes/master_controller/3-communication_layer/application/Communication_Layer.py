# -*- coding: utf-8 -*-
################################################################################################################################
#                                                       UIoT Team															   #
################################################################################################################################
# Code by: Hiro Ferreira (hiro.ferreira@gmail.com)                                                                             #
# Date: 15 jan 2015																									  		   #
# Brasília, brazil																										  	   #
################################################################################################################################

import ctypes
import json
import sys
import threading
import time

from setproctitle import setproctitle

from application.config.Configuration import *
from application.helpers.Debug_Handler import *
from application.helpers.Socket_Handler import * 
from application.helpers.USB_Handler import * 
from application.packets.Control_Packet import *
from application.packets.Event_Packet import *


class Communication_Layer(object):

	xbee_module = None
	
	socket_to_control_layer = None
	socket_from_control_layer = None
	
	reading_thread = None
	writing_thread = None
	eventing_thread = None
	
	data_buffer = ''
	stop = False
	event_packet_buffer = []
	control_packet_buffer = {}

	def __init__(self):
		try:
			#Changes process name
			setproctitle('[UIoT] Communication Layer - Master Controller')
			
			#Starts communication port to exchange data with xbee USB module
			self.xbee_module = USB_Handler(CONFIG.xbee_address,CONFIG.xbee_baudrate,True)
			
			#starts thread to monitor state changes and send and receive acks to/from slave controllers
			self.reading_thread = threading.Thread(target=self.monitor_state_changes_and_acks, args=())
			self.reading_thread.daemon=True
			self.reading_thread.start()

			#starts thread to receive control requests and send acks to control layer
			self.writing_thread = threading.Thread(target=self.monitor_control_requests, args=())
			self.writing_thread.daemon=True
			self.writing_thread.start()		
			
			#starts thread to send buffered event packets
			self.eventing_thread = threading.Thread(target=self.resend_buffered_event_packets, args=())
			self.eventing_thread.daemon=True
			self.eventing_thread.start()	
			
			#deals with ctrl+c interruption
			while True: time.sleep(100)
		except (KeyboardInterrupt, SystemExit, Excpetion) as e:
			text = '\n\n\n'
			text += '\n#################################################################'
			text += '\n#### Received Keyboard interrupt. Closing ports and Threads. ####'
			text += '\n#################################################################'
			self.debug('ERROR','KEYBOARD INTERRUPT | COMM LAYER INIT',text+str(e))
			exit()

	def monitor_state_changes_and_acks(self):
		self.socket_to_control_layer = Socket_Handler(CONFIG.control_layer_ip,CONFIG.control_layer_eventing_server_port,"client")
		client = Socket_Handler("127.0.0.1",44332,"client",None,False)
		while not self.stop:
			try:
				data = self.xbee_module.read(270)
				if((data != False) & (data != None)):
					self.handle_received_data(data)
				time.sleep(1/1000)
			except Exception as e:
				self.debug('ERROR','XBEE MONITOR THREAD'," Error accessing XBEE for reading. " + str(e))
				time.sleep(1)
				pass

	def handle_received_data(self,data):
		
		# Concatenates received data with old data
		self.data_buffer = self.data_buffer + data
		
		self.debug('INFO','XBEE MONITOR THREAD'," received data. Data Buffer length is " + str(len(self.data_buffer)) +" bytes")
		
		# While there are bytes on buffer, try to find a ZigBee packet
		while (len(self.data_buffer)>0):
			
			#check if it is a ZigBee delimiter byte
			if(self.data_buffer[0] == '\x7e'):
				self.debug('INFO','XBEE MONITOR THREAD','Found a ZigBee packet in buffer!')
				
				#check if received packet length have been received
				if(len(self.data_buffer)>3):
					
					#receives length of packet(shift byte 1 in 16 bits(msb) and sums with byte 2(lsb))
					packet_length = (ord(self.data_buffer[1]) << 16) + ord(self.data_buffer[2])
					
					#checks if packet have already been entirely received
					if(len(self.data_buffer)>=(packet_length+3)):
						
						#Calculates checksum
						calculated_checksum = 0
						i = 0
						while(i<packet_length):
							calculated_checksum += ord(self.data_buffer[3+i])
							i+=1
						calculated_checksum = calculated_checksum%256
						calculated_checksum = ord('\xff') - calculated_checksum%255

						#checks if packet passes integrity check through checksum
						received_checksum = ord(self.data_buffer[3+packet_length])
						if(calculated_checksum == received_checksum):
							self.debug('INFO','XBEE MONITOR THREAD','Found a valid ZigBee packet in buffer!')
							
							#checks if it is a UIoT Packet: if has uiot header bytes and if it is api packet
							if((self.data_buffer[3] == '\x90') & ((self.data_buffer[15] == '\xaa') | (self.data_buffer[15] == '\xff'))):
								
								try:
									self.debug('INFO','XBEE MONITOR THREAD','Found a UIoT packet in Buffer! Retrieving data...')
									packet_type = self.data_buffer[15]
									
									#treats case it is a event packet
									if packet_type == '\xaa':
										self.__receive_event_packet(packet_length)
									
									#treats case it is a control packet ACK
									elif packet_type == '\xff':
										self.__receive_control_akc_packet(packet_length)
										
								except Exception as e:
									self.debug('ERROR','XBEE MONITOR THREAD', 'Packet has a unknown problem. Discarded first byte. Checksum -> '+ str(received_checksum)+ ' != '+ str(calculated_checksum))
									self.data_buffer = self.data_buffer[1:]

							else:
								self.debug('INFO','XBEE MONITOR THREAD','Received a packet, but not UIoT packet...')

							#remove packet bytes from local buffer because they have been processed
							self.data_buffer = self.data_buffer[packet_length+4:]
						else:
							self.debug('ERROR','XBEE MONITOR THREAD',  'Packet did not pass checksum. Discarded. '+ str(received_checksum)+ ' != '+ str(calculated_checksum))
							self.data_buffer = self.data_buffer[1:]
							
					#if packet have not been entirely received, wait for it.
					else:
						break;

				#if length of packet is not received, wait for it.
				else:
					break;
			
			#if it is not zigbee delimiter, try starting with next byte
			else:
				self.debug('INFO','XBEE MONITOR THREAD','Received data, but it is not a ZigBee packet...')
				self.data_buffer = self.data_buffer[1:]
		
		
	def __receive_event_packet(self,packet_length):
		#treats receved packet
		packet = Event_Packet()
		packet.receive_data_from_bytes(self.data_buffer[:packet_length+4])
		
		#Sends Eventing value to control layer or save it in local buffer
		try:
			self.debug('INFO','XBEE MONITOR THREAD','Sending UIoT Event packet to Communication layer...')
			is_socket_started = self.socket_to_control_layer.start()
			if is_socket_started:
				old_value = self.socket_to_control_layer.max_read_retry
				self.socket_to_control_layer.write(packet.get_json())
				self.debug('INFO','XBEE MONITOR THREAD','Waiting for response...')
				self.socket_to_control_layer.max_read_retry=10
				message = self.socket_to_control_layer.read_line()
				self.socket_to_control_layer.close()
				self.socket_to_control_layer.max_read_retry = old_value
			
			else:
				message = False
			
			#Send packet to buffer if not possible send it straight ahead to control layer
			if message == False or message == None:
				self.debug('ERROR','XBEE MONITOR THREAD','No response from Control layer... Saving message to send later')
				self.send_event_packet_to_local_buffer(packet.get_json());
			
			else:
				self.debug('INFO','XBEE MONITOR THREAD','Confirmation received. Finished sending Event packet to control layer.')
				self.data_buffer = self.data_buffer[packet_length+4:]
			
			response = self.xbee_module.write(packet.get_ack())
			
			self.debug('INFO','XBEE MONITOR THREAD','Sent ACK to Slave controller '+str(packet.slavecontroller_address))
		except Exception as e:
			self.debug('ERROR','XBEE MONITOR THREAD','Error while sending data to control layer: '+str(e))
			self.data_buffer = self.data_buffer[1:]
			
			
	def send_event_packet_to_local_buffer(self,packet):
		self.event_packet_buffer.append(packet)
		self.debug('INFO','EVENT PACKET BUFFER','Adding packet to buffer. There are '+str(len(self.event_packet_buffer))+' packets to be sent.')
		if len(self.event_packet_buffer) >= CONFIG.event_packet_buffer_max_length:
			self.event_packet_buffer.remove(self.event_packet_buffer[0])
	
	def resend_buffered_event_packets(self):
		"""Every second checks if there are packets in buffer. Case there are, try to send them to control layer. Case not, wait a second and try again."""
		
		#if there are packets in buffer, tries to send them one by one in FIFO fashion.
		while not self.stop:
			if len(self.event_packet_buffer):
				self.debug('INFO','EVENT PACKET BUFFER','Sending packets in buffer to control layer... There are '+str(len(self.event_packet_buffer))+' packets to be sent.')
				
				#Tries to open socket to control layer server
				try:
					is_socket_started = self.socket_to_control_layer.start()
					if is_socket_started:
						
						#Makes retries for reading from control layer finite
						old_value = self.socket_to_control_layer.max_read_retry
						self.socket_to_control_layer.max_read_retry=10
						
						#Sends all packets stored in buffer in FIFO fashion.
						while len(self.event_packet_buffer) > 0:
							packet = self.event_packet_buffer[0]
							self.socket_to_control_layer.write(packet)
							message = self.socket_to_control_layer.read_line()
							if message != None and message != False:
								self.event_packet_buffer.remove(self.event_packet_buffer[0])
							else:
								time.sleep(1)
						
						#Finishes communication with control layer and ends procedure
						self.socket_to_control_layer.close()
						
						#Restores original reading retries
						self.socket_to_control_layer.max_read_retry = old_value
						self.debug('INFO','EVENT PACKET BUFFER','Finished sending packets in buffer to control layer.')
					
					#Case Control layer is unavailable, notify through debugger to listener
					else:
						self.debug('ERROR','EVENT PACKET BUFFER','Control layer inaccessible... Impossible to send packets from Event buffer.')
				
				#Case anything unknown goes wrong, notify through debugger to listener
				except  Exception as e:
					self.debug('ERROR','EVENT PACKET BUFFER','Error while trying to send event packets to control layer. Impossible to send packets from Event buffer. '+str(e))
				
				#waits a second before retry doing it all again
				time.sleep(1)
			
			#notifies about no packets in buffer and wait a second before retry
			else:
				self.debug('INFO','EVENT PACKET BUFFER','No packets to send.')
				time.sleep(1)
			
		
	def monitor_control_requests(self):
		#sets up configuration for server socket to listen for control requests
		ip = CONFIG.communication_layer_ip
		port = CONFIG.communication_layer_control_server_port
		type = "server"
		callback_function = self.receive_control_requests
		max_connections = CONFIG.communication_layer_control_server_max_connections
		
		#creates server which will wait for connections and parse them to 'receive_control_requests' when receives them.
		self.socket_from_control_layer = Socket_Handler(ip,port,type,callback_function,None,None, max_connections)
		
	def receive_control_requests(self,socket_handler,connection_id):
		self.debug('INFO','CONTROL SOCKET','Received new control request. id: '+connection_id+'.')
		
		#reads control JSON from control layer
		request_json_string = socket_handler.read_line()
		
		#creates a new control packet through JSON
		control_packet = Control_Packet()
		json_is_ok = control_packet.get_data_from_json_string(request_json_string)
		
		#case JSON is well formed, parses request to communication layer as UIoT ZigBee Packet.
		if json_is_ok:
			self.control_packet_buffer[control_packet.get_id()] = control_packet
			response = self.run_control_request(connection_id,control_packet.get_xbee_packet(),control_packet.get_id())
			socket_handler.write(response)
		
		#case JSON is malformed, notifies control layer about it.
		else:
			socket_handler.write('{"status":"error","message":"Unable to decode control layer JSON."}')
			
		#Finishes connection with control layer.
		socket_handler.close(connection_id)
	
	def run_control_request(self,connection_id,request_packet = None,packet_id=None):
		if request_packet == False:
			response = '{"status":"error","message":"Error while assembling XBEE packet to be sent to Slave Controller."}'
		elif not request_packet:
			response = '{"status":"error","message":"Null packet received, impossible to send it to Slave Controller."}'
		else:
			try:
				response = self.xbee_module.write(request_packet)
			except Exception as e:
				response = 'FAIL'
			pakcet_string =  ('0x'+" 0x".join("{:02x}".format(ord(c)).upper() for c in str(request_packet)))
			self.debug('INFO','CONTROL SOCKET','Connection  '+connection_id+': sending following packet to XBEE module: '+pakcet_string)
			if response == True:
				response_code = self.wait_for_control_ack(packet_id)
				
				#means success
				if response_code == '\x00':
					response = '{"status":"success","message":"Control message."}'
				
				#means error
				else:
					response_code = ('0x'+" 0x".join("{:02x}".format(ord(c)).upper() for c in str(response_code)))
					response = '{"status":"error","message":"Salve controller responded with error id '+response_code+'."}'
					
			elif response == 'FAIL':
				response = '{"status":"error","message":"Error while sending message through USB Socket to XBEE Module."}'
			else:
				response = '{"status":"error","message":"Error while sending message through USB Socket to XBEE Module."}'
			self.debug('INFO','CONTROL SOCKET','Connection  '+connection_id+': received response from XBEE module.')
		return response
	
	def wait_for_control_ack(self,control_packet_id):
		elapsed_waiting_time = 0
		buffer_packet = None
		while True:
			try:
				buffer_packet = self.control_packet_buffer[control_packet_id]
				if elapsed_waiting_time < CONFIG.xbee_control_packet_retransmission_interval:
					try:
						if buffer_packet.sucessfuly_sent():
							del self.control_packet_buffer[control_packet_id]
							return buffer_packet.ack_status
						else:
							elapsed_waiting_time += CONFIG.xbee_control_packet_ack_re_read_interval
							time.sleep(CONFIG.xbee_control_packet_ack_re_read_interval)
					except Exception as e:
						print 'Deu merda:'+str(e)
						time.sleep(10)
				else:
					print 'Reenviando o pacote '+str(control_packet_id)+' ... Não foram achados acks. Fila de pacotes:'
					for i in self.control_packet_buffer:
						print str(self.control_packet_buffer[i].get_id())+": "+str(self.control_packet_buffer[i].sucessfuly_sent())
					self.xbee_module.write(buffer_packet.get_xbee_packet())
					elapsed_waiting_time = 0
					time.sleep(CONFIG.xbee_control_packet_ack_re_read_interval)
			except Exception as e:
				print 'Deu merda2:'+str(e)
				time.sleep(10)
	
	def __receive_control_akc_packet(self,packet_length):
		"""Receives a control ack and set buffer packet as 'Successfully received'."""
		try:
			self.debug('INFO','XBEE MONITOR THREAD - CONTROL ACK','Found Control ACK packet!')
			print 'Analisando control ack '+str(self.data_buffer[0:packet_length])
			#UIoT message id are 17th(MSB) and 18th(MSB) bytes of Packet
			packet_id = ord(self.data_buffer[16]) << 8 + ord(self.data_buffer[17])
			
			#ZigBee Sender ADDR are bytes 6 until 13(8 bytes) of ZigBee Packet
			slavecontroller_address = "".join("{:02x}".format(ord(c)) for c in self.data_buffer[4:12])
			
			#packet Status is byte 19
			packet_status = self.data_buffer[18]
			
			self.debug('INFO','XBEE MONITOR THREAD - CONTROL ACK','Received ACK for packet '+str(slavecontroller_address)+'-'+str(packet_id)+' valued '+('0x'+" 0x".join("{:02x}".format(ord(c)).upper() for c in str(packet_status))))
			
			#packet id in local buffer is formed by slavecontroler_addr+'-'+ message_id
			self.control_packet_buffer[str(slavecontroller_address)+'-'+str(packet_id)].received_ack(packet_status)
			print '----- Recebeu ack!!!:'
			print str(self.control_packet_buffer[str(slavecontroller_address)+'-'+str(packet_id)].get_id())+": "+str(self.control_packet_buffer[str(slavecontroller_address)+'-'+str(packet_id)].sucessfuly_sent())
			self.data_buffer = self.data_buffer[packet_length+4:]

		except Exception as e:
			self.debug('ERROR','XBEE MONITOR THREAD - CONTROL ACK','Error while treating Control ACK.-> '+str(e))
			self.data_buffer = self.data_buffer[1:]
	
	def debug(self,type,title,description):
		if not CONFIG.do_debugging:
			return None
		try:
			description = description.replace('\xFF','\\xFF')
			class_name = 'communication_layer'
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