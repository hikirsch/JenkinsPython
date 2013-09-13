import os
import sys
from app import Settings
from helpers.Execute import Execute
from model.Path import Path


class Plovr:
	JAR_FILE_NAME = "plovr-81ed862.jar"

	CONFIG_PATH = Path(Settings.BUILD_ASSETS_PATH + os.path.sep + Settings.MINIFY_PATH + os.path.sep + "plovr.json")

	jar_path = None

	def __init__(self):

		self.jar_path = Path(os.path.dirname(os.path.abspath(__file__)) + os.path.sep + self.JAR_FILE_NAME)

		self.EXECUTE_PARAMS = ["java", "-jar", self.jar_path.path, "build", self.CONFIG_PATH.path]

		print " ".join(self.EXECUTE_PARAMS)

		print "Config Path: %s" % self.CONFIG_PATH.path

		if self.CONFIG_PATH.exists():
			self.run()
		else:
			print "Compass config file not found."


	def run(self):
		print "Running plovr build..."

		Execute().run(self.EXECUTE_PARAMS)

		print "Done"
