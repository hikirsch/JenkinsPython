import os
from app import Settings
from model.Path import Path


class ClosureCompiler:
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
		open_file = open("sample.txt")

		while 1:
			line = open_file.readline()

			if not line:
				break

			firstCharacter = line[:1]

			if firstCharacter == "+":
				pass # should just append
			else:
				pass # run compiler



