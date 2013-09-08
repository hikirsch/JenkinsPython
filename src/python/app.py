#!/usr/bin/python
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

			try:
				methodToCall = getattr(self, command)

				if methodToCall is not None:
					methodToCall()
			except AttributeError as e:
				raise Exception("Command Not Found")

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

			raise e


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

		if os.path.exists(src):
			Copy(src, self.TARGET_PATH)
		else:
			raise Exception("source path '%s' does not exist" % src)


	def deploy(self):
		dest = self.argHelper.getNextArgument()

		Copy(self.TARGET_PATH, dest)

	def buildAssets(self):
		when = self.argHelper.getNextArgument() + "-build"
		env = self.argHelper.getNextArgument()

		if env is None and "ENV" in os.environ:
			env = os.environ["ENV"]

		if env is None:
			raise Exception("No environment found")
		else:
			when = self.caseInsensativeFolder(self.BUILD_ASSETS_PATH, when)
			path = self.caseInsensativeFolder(when, env)

			if os.path.exists(path):
				Merge(path, self.TARGET_PATH)
			else:
				raise Exception("Path '%s' was not found!" % path)

	def compile(self):
		print "TEST"

	def caseInsensativeFolder(self, path, when):
		files = os.listdir(path)

		while files:
			nextFile = files.pop()

			if nextFile.lower() == when.lower():
				return path + os.path.sep + nextFile

		return None


if __name__ == "__main__":
	Jenkins().run()