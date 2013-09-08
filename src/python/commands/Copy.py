import os
import subprocess
from commands.Merge import Merge
from model.Path import Path


class Copy:
	src = None
	dest = None

	RSYNC_OPTIONS = ["-a", "--progress", "--delete-during"]

	def __init__(self, src, dest):
		self.src = Path(src)
		self.dest = Path(dest)

		if self.src is None:
			raise Exception("Source path is empty.")

		if self.dest is None:
			raise Exception("Destination path is empty.")

		if self.dest.isRemote is True and self.src.isRemote is True:
			raise Exception("Both paths can not be remote paths!")

		if self.src.isRemote is False and self.dest.isRemote is False:
			self.doCopyLocal()

		if not self.src.isRemote and self.dest.isRemote:
			self.doCopyRemote()

	def doCopyLocal(self):

		if self.dest.exists():
			print "Removing original folder '" + self.dest.path + "'"
			self.dest.remove()

		if self.src.isDir():
			os.mkdir(self.dest.path)

		print "Copying '" + self.src.path + "' to '" + self.dest.path
		Merge(self.src.path, self.dest.path)

	def doCopyRemote(self):
		options = ["rsync"] + self.RSYNC_OPTIONS[:]

		options.append('-e')

		if self.dest.scheme == "ftp":
			options.append('ftp')
		else:
			options.append('"ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no"')

		src_path = self.src.path
		if os.path.isdir(src_path):
			src_path += "/"

		options += [src_path, self.dest.getSyncPath()]

		# print options
		self.execute(options)

	def execute(self, rsync_command):
		print "RUNNING: " + " ".join(rsync_command)
		cleanProcess = subprocess.Popen(rsync_command, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
		output = cleanProcess.communicate()[0].split("\n")

		for line in output:
			print line





