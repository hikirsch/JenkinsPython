import os
import sys
from app import Settings
from helpers.Execute import Execute
from model.Path import Path


class Plovr:
	JAR_FILE_NAME = "plovr-81ed862.jar"

	CONFIG_PATH = Path(os.path.join(Settings.BUILD_ASSETS_PATH, Settings.MINIFY_PATH, "plovr.json"))
	CONFIG_PATH_ALT = Path(os.path.join(Settings.BUILD_ASSETS_PATH, Settings.PLOVR_PATH))

	JAR_PATH = Path(os.path.dirname(os.path.abspath(__file__)) + os.path.sep + JAR_FILE_NAME)

	def __init__(self):
		if self.CONFIG_PATH.exists():
			self.run(self.CONFIG_PATH.path)

		elif self.CONFIG_PATH_ALT.exists():
			for plovr_config in os.listdir(self.CONFIG_PATH_ALT.path):
				full_path = os.path.join(self.CONFIG_PATH_ALT.path, plovr_config)

				if os.path.isfile(full_path):
					self.run(full_path)

		else:
			print "Compass config file not found."


	def run(self, path):
		print "Running plovr build with config %s..." % path
		params = ["java", "-jar", self.JAR_PATH.path, "build", path]
		Execute().run(params)

		print "Done"
