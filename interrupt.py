#!/usr/bin/env python2.7
# version: 1.0  
# source: script by Alex Eames http://RasPi.tv  
# ref: http://RasPi.tv/how-to-use-interrupts-with-python-on-the-raspberry-pi-and-rpi-gpio-part-3  
import os.path
import RPi.GPIO as GPIO, time 
GPIO.setmode(GPIO.BCM)  
  
# GPIO 23 & 17 set up as inputs, pulled up to avoid false detection.  
# Both ports are wired to connect to GND on button press.  
# So we'll be setting up falling edge detection for both  
GPIO.setup(22, GPIO.IN)  
GPIO.setup(27, GPIO.IN)
GPIO.setup(17, GPIO.IN) 
GPIO.setup(9, GPIO.IN) 
GPIO.setup(10, GPIO.IN)
  
  
# GPIO 24 set up as an input, pulled down, connected to 3V3 on button press  
#GPIO.setup(24, GPIO.IN, pull_up_down=GPIO.PUD_DOWN)  
  
# now we'll define two threaded callback functions  
# these will run in another thread when our events are detected  
def my_callback(channel):
	time.sleep(0.1)
	if GPIO.input(27) and not os.path.isfile("/var/www/html/services/pending/pending"):
		pendingfile = open("/var/www/html/services/pending/pending","w")
		pendingfile.write(str((time.strftime('%Y-%m-%d %H:%M:%S'))))
		pendingfile.close
		print "========= START " + str((time.strftime('%Y-%m-%d %H:%M:%S'))) + "=========="
		print "Pool Light: " + str(GPIO.input(22))
		print "Drain pump: " + str(GPIO.input(27))
		print "Decoration Light: " + str(GPIO.input(17))
		print "Heat Pump: " + str(GPIO.input(9))
		print "Pool Pump: " + str(GPIO.input(10)) 
		print "========================================"
		
	if not GPIO.input(27) and os.path.isfile("/var/www/html/services/pending/pending"):
		pendingfile = open("/var/www/html/services/pending/pending","r")
		content = pendingfile.read()
		donefile = open("/var/www/html/services/done/done","w")
		donefile.write(str(content) + " => " + str((time.strftime('%Y-%m-%d %H:%M:%S'))))
		pendingfile.close
		donefile.close
		os.remove("/var/www/html/services/pending/pending")
		print "========= STOP " + str((time.strftime('%a %H:%M:%S'))) + "=========="
		print "Pool Light: " + str(GPIO.input(22))
		print "Drain pump: " + str(GPIO.input(27))
		print "Decoration Light: " + str(GPIO.input(17))
		print "Heat Pump: " + str(GPIO.input(9))
		print "Pool Pump: " + str(GPIO.input(10)) 
		print "========================================"
  
  
# when a falling edge is detected on port 17, regardless of whatever   
# else is happening in the program, the function my_callback will be run  
GPIO.add_event_detect(27, GPIO.BOTH, callback=my_callback, bouncetime=500)  
  
# when a falling edge is detected on port 23, regardless of whatever   
# else is happening in the program, the function my_callback2 will be run  
# 'bouncetime=300' includes the bounce control written into interrupts2a.py  
#GPIO.add_event_detect(22, GPIO.FALLING, callback=my_callback2, bouncetime=3000)  
  
try:
	while 1:
		time.sleep(5)
except KeyboardInterrupt:  
    GPIO.cleanup()       # clean up GPIO on CTRL+C exit  
GPIO.cleanup()           # clean up GPIO on normal exit  
