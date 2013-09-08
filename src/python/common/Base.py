import os
import sys

class Base:
	version = "0.0.1"


#	def __init__(self):

	def copy(self, src, dest):
		self.executeShell( "copy " + src + " " + dest )

	def executeShell(self, command ):
		print "EXEC: " + command