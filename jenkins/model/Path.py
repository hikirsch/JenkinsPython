import re
from urlparse import urlparse

class Path:
	uri = None

	isRemote = False

	scheme = None

	userName = None
	password = None

	server = None
	portNumber = None
	path = None

	supportedProtocols = [ '', "sftp", "ftp", "cifs" ]

	def __init__(self, uri):
		uri = uri.strip()

		if len( uri ) == 0:
			raise Exception("URI is empty")

		self.uri = uri

		self.parse()

	def parse(self):
		parsedResult = urlparse( self.uri )

		if not self.supportedProtocols.index( parsedResult.scheme ) > -1:
			raise Exception("Protocol " + parsedResult.scheme + " is not supported")

		if parsedResult.netloc:
			self.isRemote = True

		self.scheme = parsedResult.scheme
		self.server = parsedResult.netloc
		self.path = parsedResult.path

