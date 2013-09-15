import os
from commands.Merge import Merge
from helpers.Execute import Execute
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

		if self.dest.is_remote is True and self.src.is_remote is True:
			raise Exception("Both paths can not be remote paths!")

		if self.src.is_remote is False and self.dest.is_remote is False:
			self.doCopyLocal()

		if not self.src.is_remote and self.dest.is_remote:
			self.doCopyRemote()

	def doCopyLocal(self):

		if self.dest.exists():
			print "Removing original folder '" + self.dest.path + "'"
			self.dest.remove()

		if self.src.is_dir():
			os.mkdir(self.dest.path)

		print "Copying '" + self.src.path + "' to '" + self.dest.path
		Merge(self.src.path, self.dest.path)

	def doCopyRemote(self):
		options = ["rsync"] + self.RSYNC_OPTIONS[:]

		options.append('-e')

		if self.dest.scheme == "ftp":
			options.append('ftp')
		else:
			if self.dest.portNumber is not None:
				options.append('"ssh -P %s -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no"' % self.dest.portNumber)
			else:
				options.append('"ssh -o UserKnownHostsFile=/dev/null -o StrictHostKeyChecking=no"')

		src_path = self.src.path
		if os.path.isdir(src_path):
			src_path += "/"

		options += [src_path, self.dest.get_sync_path()]

		Execute().run(options)





