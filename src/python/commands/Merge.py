import os
from os import path
import shutil
from model.Path import Path


class Merge:
	EXCLUDE = [".svn", ".git"]

	def __init__(self, src, dest):
		self.src = Path(src)
		self.dest = Path(dest)

		self.merge(self.src.path, self.dest.path)

	def merge(self, origin, dest):
		filequeue = os.listdir(origin)

		while filequeue:
			file_name = filequeue.pop()

			base_name = path.basename(file_name)

			origin_filename = path.join(origin, file_name)
			dest_filename = path.join(dest, file_name)

			try:
				if path.isdir(origin_filename) and not base_name in self.EXCLUDE:
					filequeue.extend(path.join(file_name, ch) for ch in os.listdir(origin_filename))
				elif not path.isfile(origin_filename):
					print "Ignored '%s'" % origin_filename
				elif path.isdir(dest_filename):
					print "File '%s' matches directory '%s'" % (origin_filename, dest_filename)
				else:
					parent_path = os.path.dirname(dest_filename)

					if not os.path.exists(parent_path):
						os.makedirs(parent_path)

					if not base_name in self.EXCLUDE:
						print "Copying '%s' --> '%s'" % ( origin_filename, dest_filename )
						shutil.copy(origin_filename, dest_filename)
			except IOError, e:
				import sys

				print >> sys.stderr, 'Error accessing `%s`/`%s`: %s' % (origin_filename, dest_filename, e)

