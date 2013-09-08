import os
from commands.Merge import Merge
from model.Path import Path


class Copy:
	src = None
	dest = None

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

	def doCopyLocal(self):

		if self.dest.exists():
			print "Removing original folder '" + self.dest.path + "'"
			self.dest.remove()

		if self.src.isDir():
			os.mkdir(self.dest.path)

		print "Copying '" + self.src.path + "' to '" + self.dest.path
		Merge(self.src.path, self.dest.path)



