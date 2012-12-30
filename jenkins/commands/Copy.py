import shutil
import errno
from Exec import Exec
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

		if self.dest.isRemote == self.src.isRemote and self.src.isRemote:
			raise Exception("Both paths can not be remote paths!")

		Exec( "copy " + src + " " + dest )

	def doCopy(self):
		try:
			shutil.copytree( self.src, self.dest )
		except OSError as exc: # python >2.5
			if exc.errno == errno.ENOTDIR:
				shutil.copy( self.src, self.dest )
			else: raise


