import os
import shutil
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
	parsedPath = None

	supportedProtocols = ['', "sftp", "ftp", "cifs"]

	def __init__(self, uri):
		if uri is None:
			raise Exception("URI is none")

		uri = uri.strip()

		if len(uri) == 0:
			raise Exception("URI is empty")

		self.uri = uri

		self.parse()

	def exists(self):
		return os.path.exists(self.path)

	def remove(self):
		shutil.rmtree(self.path)

	def equals(self, path):
		if not self.isRemote and not self.path.isRemote:
			return os.path.samefile(self.path.path, path.path.path)

		raise Exception("No support for equals on remote server.")

	def parse(self):
		parsedResult = urlparse(self.uri)

		if not self.supportedProtocols.index(parsedResult.scheme) > -1:
			raise Exception("Protocol " + parsedResult.scheme + " is not supported")

		if parsedResult.netloc:
			self.isRemote = True

		self.scheme = parsedResult.scheme
		self.server = parsedResult.netloc
		self.path = os.path.abspath(parsedResult.path)
		self.parsedPath = parsedResult.path

	def isDir(self):
		return os.path.isdir(self.path)

