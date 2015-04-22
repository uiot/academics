# -*- coding: utf-8 -*-
##################################################################################################################################
#                                                       UIoT Team															     #
##################################################################################################################################
# This class requires pySerial installed to properly work. Check  http://pyserial.sourceforge.net/ for installation instructions #
# Code by: Hiro Ferreira (hiro.ferreira@gmail.com)                                                                               #
# Date: 15 jan 2015																									  		     #
# Brasília, brazil																										  	     #
##################################################################################################################################

import serial, time, json
from application.helpers.Debug_Handler import DEBUG
from application.config.Configuration import CONFIG

class USB_Handler(object):
	
	#Holds OS's address of port to communicate with
	port_addr = "COM1"

	#Holds baudrate to communicate with port
	baudrate = 9600

	#holds an open connection to a port
	usb_device = None

	#used for debbuging purpose
	i = 0

	#Holds how many retries should be done when trying to write to port. Put -1 for infinite retries
	max_write_retry=5

	#Counter of writing retries
	write_retry=0

	#Holds how many retries should be done when trying to read from port. Put -1 for infinite retries
	max_read_retry=-1

	#Counter of reading retries
	read_retry=0
	
	#This method initializes the USB_Handler by starting the connection
	def __init__(self, port_addr = None, baudrate = None, open_port_now = True):
		self.debug('NOTICE', 'USB INIT','initializing USB device '+port_addr+'...')

		#Saves port and baudrate to handle communication during reads and writes calls
		if(port_addr != None):
			self.port_addr = port_addr
		if(baudrate != None):
			self.baudrate = baudrate

		#Starts communication with USB port
		if open_port_now == True:
			self.open_communication_port(port_addr,baudrate)

		self.debug('NOTICE', 'USB INIT','USB Device '+port_addr+' started with success!')

	#This method converts string chars to string with bits of char
	def tobits(s):
	    result = []
	    for c in s:
	        bits = bin(ord(c))[2:]
	        bits = '00000000'[len(bits):] + bits
	        result.extend([int(b) for b in bits])
	    return result

	#This method writes data to port. returns True case everything was done correctly or False in case of erros
	def write(self,message):
		#Try to write bytes to port
		try:
			#Write bytes to port
			self.debug('INFO', 'USB WRITE', 'Sending data to USB device. ')
			written_bytes = self.usb_device.write(message)
			self.debug('INFO', 'USB WRITE', 'Data ('+str(written_bytes)+' bytes) sent to USB device with Success. ')
			return True
		except Exception as e:
			self.debug('ERROR', 'USB WRITE', 'Error while writing. '+str(e))
			#in case of error, try to reconnect to port and write again
			if((self.max_write_retry == -1) | (self.write_retry < self.max_write_retry)):
				
				self.write_retry+=1
				self.debug('ERROR', 'USB WRITE','Retrying to write '+str(self.write_retry)+'/'+str(self.max_write_retry))
				
				if self.usb_device.isOpen(): 
					self.usb_device.close()
				
				self.open_communication_port(self.port_addr,self.baudrate,1)
				self.write(message)
				
				pass
			else:
			#in case of error and exceeded retries returns false
				self.write_retry = 0
				return False

	#This method reads bytes from port. returns bytes read, or "None" in case channel has nothing, or "False" in case of erros
	def read(self,chars_to_read):
		#Try to read bytes from port
		try:
			#Read bytes from port
			data = self.usb_device.read(chars_to_read)

			#Case there is data, returns data
			if data:
				return data

			#Case there is no data, returns "None"
			else:
				return None
		except Exception as e:
			self.debug('ERROR', 'USB READ', 'Error while reading. '+str(e))
			self.i+=1
			if self.i > 10 :
				self.i = 0

			#in case of error, try to reconnect to port and read again
			if((self.max_read_retry == -1) | (self.read_retry < self.max_read_retry)):
				
				self.read_retry+=1
				
				self.debug('ERROR', 'USB READ','Retrying to read '+str(self.read_retry)+'/'+str(self.max_read_retry))

				if self.usb_device.isOpen(): 
					self.usb_device.close()
				
				self.open_communication_port(self.port_addr,self.baudrate,1)
				self.read(chars_to_read)
				
				pass

			else:
				#in case of error and exceeded retries returns false
				self.read_retry = 0
				return False


	#This method opens communication to desired port with desired baudrate, and keeps retrying until it does!
	def open_communication_port(self,port_addr,baudrate,retries=-1):
		#Try to open port
		while True:
			try:
				#Opens port
				self.usb_device = serial.Serial(port_addr, baudrate, timeout=.1)
				if self.usb_device.isOpen() == False: 
					self.usb_device.open()
				
				#Wait for communication to be stabilished correctly
				time.sleep(1)
				break
			
			except Exception as e:
				self.debug('ERROR', 'USB INIT COMMUNICATION PORT',str(e))
				
				#In case of error, wait a second and retry
				time.sleep(1)
				if ((retries == -1) | (retries>0)):
					if(retries > 0):
						retries -= 1
				else:
					break
				pass
		
	def debug(self,type,title,description):
		if not CONFIG.do_debugging:
			return None
		try:
			description = description.replace("\xFF",'\\xFF')
			class_name = 'usb_handler'
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
		pass