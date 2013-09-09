import os
from app import Jenkins


class ClosureCompiler:
	JAR_FILE_NAME = "closure-compiler.jar"

	CONFIG_PATH = Jenkins.BUILD_ASSETS_PATH + os.path.sep + "minify" + os.path.sep + "js.txt"

	compiler_path = None

	def __init__(self):
		self.compiler_path = os.path.dirname(os.path.abspath(__file__)) + os.path.sep + self.JAR_FILE_NAME

		print "Compiler Path: %s" % self.compiler_path
		print "Config Path: %s" % self.CONFIG_PATH

	def run(self):
		pass
