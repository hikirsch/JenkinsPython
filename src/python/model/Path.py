import os
import shutil
from urlparse import urlparse


class Path:
	uri = None

	is_remote = False
	is_absolute = False

	scheme = None

	userName = None
	password = None

	server = None
	portNumber = None
	path = None
	parsedPath = None

	supportedProtocols = ['', "sftp", "ftp", "cifs", "ssh"]

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
		if not self.is_remote and not self.path.isRemote:
			return os.path.samefile(self.path.path, path.path.path)

		raise Exception("No support for equals on remote server.")

	def parse(self):
		parsedResult = urlparse(self.uri)

		if not self.supportedProtocols.index(parsedResult.scheme) > -1:
			raise Exception("Protocol " + parsedResult.scheme + " is not supported")

		if parsedResult.netloc:
			self.is_remote = True

		self.scheme = parsedResult.scheme
		self.server = parsedResult.hostname
		self.userName = parsedResult.username
		self.password = parsedResult.password
		self.path = os.path.abspath(parsedResult.path)
		self.parsedPath = parsedResult.path

		if self.is_remote and self.path[:1] == "/":
			self.path = self.path[1:]

		if self.path[:1] == "/":
			self.is_absolute = True

	def is_dir(self):
		return os.path.isdir(self.path)

	def get_sync_path(self):
		path = self.get_ssh_path()
		path += ":%s/" % self.path

		return path

	def get_ssh_path(self):
		path = ""

		if self.userName is not None:
			path += "%s@" % self.userName

		path += self.server

		return path

	def dirname(self):
		return os.path.dirname(self.path)


