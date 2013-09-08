import os
from os import path
import hashlib
import shutil
from model.Path import Path


class Merge:
	def __init__(self, src, dest):
		self.src = Path(src)
		self.dest = Path(dest)

		self.merge(self.src.path, self.dest.path)

	def hash_file(self, filename):
		'''
		hash = hash_file(filename)

		Computes hash for contents of `filename`
		'''
		#print 'hashing(%s)...' % filename
		hash = hashlib.md5()

		with open(filename) as input:
			s = input.read(4096)
			while s:
				hash.update(s)
				s = input.read(4096)

		return hash.hexdigest()


	def hash_recursive(self, directory):
		'''
		hash = hash_recursive(directory)

		Computes a hash recursively
		'''
		from os import listdir, path

		files = listdir(directory)
		files.sort()

		hash = hashlib.md5()

		for f in files:
			p = path.join(directory, f)

			if path.isdir(p):
				hash.update(self.hash_recursive(p))
			elif path.isfile(p):
				hash.update(self.hash_file(p))
			else:
				raise OSError("Cannot handle files such as `%s`" % p)

		return hash.hexdigest()


	def props_for(self, filename):
		'''
		props = props_for(filename)

		Properties for `filename`
		'''
		st = os.stat(filename)

		return st.st_mode, st.st_uid, st.st_gid, st.st_mtime


	def merge(self, origin, dest):
		'''
		for op,args in merge(origin, dest);
			op(*args)

		Attempt to merge directories `origin` and `dest`

		Parameters
		----------
		origin : str
			path to origin
		dest : str
			path to destination
		options : options object
		'''
		filequeue = os.listdir(origin)

		while filequeue:
			fname = filequeue.pop()

			origin_filename = path.join(origin, fname)
			dest_filename = path.join(dest, fname)

			try:
				if path.isdir(origin_filename):
					filequeue.extend(path.join(fname, ch) for ch in os.listdir(origin_filename))
				elif not path.isfile(origin_filename):
					print 'Ignore %s' % origin_filename
				elif path.isdir(dest_filename):
					print 'File `%s` matches directory `%s`' % (origin_filename, dest_filename)
				else:
					parent_path = os.path.dirname(dest_filename)

					if not os.path.exists( parent_path):
						os.makedirs(parent_path)

					print "Copying '%s' --> '%s'" % ( origin_filename, dest_filename )
					shutil.copy(origin_filename, dest_filename)
			except IOError, e:
				import sys

				print >> sys.stderr, 'Error accessing `%s`/`%s`: %s' % (origin_filename, dest_filename, e)

