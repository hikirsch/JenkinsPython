import os
from helpers.Execute import Execute
from model.Path import Path


class Compass:
	CONFIG_PATH = Path("config.rb")

	def __init__(self):

		print "Config Path: %s" % self.CONFIG_PATH.path

		if self.CONFIG_PATH.exists():
			self.run()
		else:
			print "Compass config file not found."


	def run(self):
		compass_path = self.which("compass")

		if len(compass_path) > 0:
			print "Running compass clean..."
			Execute().run([compass_path[0], "clean", self.CONFIG_PATH.dirname()])

			print "Running compass compile..."
			Execute().run([compass_path[0], "compile", self.CONFIG_PATH.dirname()])
		else:
			print "Compass was not found"

	def which(self, filename):
		locations = os.environ.get("PATH").split(os.pathsep)
		candidates = []
		for location in locations:
			candidate = os.path.join(location, filename)
			if os.path.isfile(candidate):
				candidates.append(candidate)

		return candidates
