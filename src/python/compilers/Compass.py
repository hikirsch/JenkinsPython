from commands.BaseCommand import BaseCommand
from helpers.Execute import Execute
from model.Path import Path


class Compass(BaseCommand):
	CONFIG_PATH = Path("config.rb")

	def __init__(self):

		print "Config Path: %s" % self.CONFIG_PATH.path

		if self.CONFIG_PATH.exists():
			self.run()
		else:
			print "Compass config file not found."


	def run(self):
		compass_executable = self.find_executable("compass")

		if compass_executable is not None:
			print "Running compass clean..."
			Execute().run([compass_executable, "clean", self.CONFIG_PATH.dirname()])

			print "Running compass compile..."
			Execute().run([compass_executable, "compile", self.CONFIG_PATH.dirname()])
		else:
			print "Compass was not found"

