import os
import shutil
from commands.Merge import Merge
from model.Path import Path

EQUALS_LINE = "==================================================================================================="
DASH_LINE = "---------------------------------------------------------------------------------------------------"

import traceback
from sys import argv

from helpers.ArgumentsHelper import ArgumentsHelper
from commands.Copy import Copy


class Jenkins:
	BUILD_ASSETS_PATH = "build-assets"
	TARGET_PATH = "target"
	SOURCE_PATH = "src"

	version = "0.0.1"

	argHelper = None


	def __init__(self):
		self.argHelper = ArgumentsHelper(argv)

	def run(self):
		try:
			command = self.argHelper.getCommand()

			lst = [word[0].upper() + word[1:] for word in command.split("-")]
			command = "".join(lst)
			command = command[:1].lower() + command[1:]

			print "Command Detected: ", command

			methodToCall = None

			try:
				methodToCall = getattr(self, command)
			except AttributeError as e:
				print "Command Not Found"

			if methodToCall is not None:
				methodToCall()

			print "DONE"

		except Exception as e:
			print ""
			print EQUALS_LINE
			print "Exception"
			print EQUALS_LINE
			print "An error occured! "
			print e
			print DASH_LINE
			print traceback.format_exc()


	def clean(self):
		path = Path(self.TARGET_PATH)

		if path.exists():
			shutil.rmtree(path.path)
			print "Removed '%s'" % self.TARGET_PATH
		else:
			print "Nothing to clean"

	def copy(self):
		src = self.argHelper.getNextArgument()
		dest = self.argHelper.getNextArgument()

		Copy(src, dest)

	def merge(self):
		src = self.argHelper.getNextArgument()
		dest = self.argHelper.getNextArgument()

		Merge(src, dest)

	def fakeCompile(self):
		projectType = self.argHelper.getNextArgument()
		src = self.SOURCE_PATH + os.path.sep + projectType

		Copy(src, self.TARGET_PATH)


	def deploy(self):
		dest = self.argHelper.getNextArgument()

		Copy(self.TARGET_PATH, dest)

	def buildAssets(self):
		when = self.argHelper.getNextArgument() + "-build"
		env = self.argHelper.getNextArgument()

		path = self.BUILD_ASSETS_PATH + os.path.sep + when + os.path.sep + env

		print "build-assets '" + when + "' for '" + env + "' (" + path + ")"

		Merge(path, self.TARGET_PATH)

	def compile(self):
		print "TEST"


if __name__ == "__main__":
	Jenkins().run()