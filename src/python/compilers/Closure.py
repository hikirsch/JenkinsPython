import os
from app import Settings
from model.Path import Path


class Closure:
	JAR_FILE_NAME = "closure-compiler.jar"

	CONFIG_PATH = Path(Settings.BUILD_ASSETS_PATH + os.path.sep + "minify" + os.path.sep + "js.txt")

	compiler_path = None

	def __init__(self):
		self.compiler_path = Path(os.path.dirname(os.path.abspath(__file__)) + os.path.sep + self.JAR_FILE_NAME)

		print "Compiler Path: %s" % self.compiler_path.path
		print "Config Path: %s" % self.CONFIG_PATH.path

		if self.CONFIG_PATH.exists():
			self.run()

	def run(self):
		open_file = open(self.CONFIG_PATH.path)

		dest_filename = None
		first = True

		while 1:
			line = open_file.readline()

			if not line:
				break

			if first:
				dest_filename = Path(line)
				print "Destination is %s" % dest_filename.path
				first = False
			else:
				firstCharacter = line[:1]

				if firstCharacter == "+":
					path = Path(Settings.SOURCE_PATH + os.path.sep + line[1:])
					if path.exists():
						print "should append %s" % path.path
					else:
						print "can not append file '%s'" % path.path

				else:
					path = Path(Settings.SOURCE_PATH + os.path.sep + line)

					if path.exists():
						print "should run compiler on %s" % path.path
					else:
						print "can not run compiler on file '%s'" % path.path




