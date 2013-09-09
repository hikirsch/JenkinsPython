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


class Settings:
	BUILD_ASSETS_PATH = "build-assets"
	TARGET_PATH = "target"
	SOURCE_PATH = "src"


class Compile():
	def __init(self, arg_helper):
		self.arg_helper = arg_helper

		command = self.arg_helper.getNextArgument()

		try:
			methodToCall = getattr(self, command)

			if methodToCall is not None:
				methodToCall()

		except AttributeError:
			raise Exception("Command Not Found")

	def fake(self):
		projectType = self.argHelper.getNextArgument()
		src = Settings.SOURCE_PATH + os.path.sep + projectType

		if os.path.exists(src):
			Copy(src, Settings.TARGET_PATH)
		else:
			raise Exception("source path '%s' does not exist" % src)

	def closure(self):
		from compilers.Closure import ClosureCompiler

		ClosureCompiler()

		pass


class Jenkins:
	version = "0.0.1"

	arg_helper = None

	compile = Compile()

	def __init__(self):
		self.arg_helper = ArgumentsHelper(argv)

	def run(self):
		try:
			command = self.arg_helper.getCommand()

			lst = [word[0].upper() + word[1:] for word in command.split("-")]
			command = "".join(lst)
			command = command[:1].lower() + command[1:]

			print "Command Detected: ", command

			try:
				methodToCall = getattr(self, command)

				if methodToCall is not None:
					methodToCall()
			except AttributeError:
				raise Exception("Command Not Found")

			print "DONE"

		except Exception as e:
			print ""
			print EQUALS_LINE
			print "Exception"
			print EQUALS_LINE
			print "An error occurred! "
			print e
			print DASH_LINE
			print traceback.format_exc()

			raise e


	def clean(self):
		path = Path(Settings.TARGET_PATH)

		if path.exists():
			shutil.rmtree(path.path)
			print "Removed '%s'" % Settings.TARGET_PATH
		else:
			print "Nothing to clean"

	def compile(self):
		Compile(self.arg_helper)

	def copy(self):
		src = self.arg_helper.getNextArgument()
		dest = self.arg_helper.getNextArgument()

		Copy(src, dest)

	def merge(self):
		src = self.arg_helper.getNextArgument()
		dest = self.arg_helper.getNextArgument()

		Merge(src, dest)

	def deploy(self):
		dest = self.arg_helper.getNextArgument()

		Copy(Settings.TARGET_PATH, dest)

	def buildAssets(self):
		when = self.arg_helper.getNextArgument() + "-build"
		env = self.arg_helper.getNextArgument()

		if env is None and "ENV" in os.environ:
			env = os.environ["ENV"]

		if env is None:
			raise Exception("No environment found")
		else:
			when = self.caseInsensativeFolder(Settings.BUILD_ASSETS_PATH, when)
			path = self.caseInsensativeFolder(when, env)

			if os.path.exists(path):
				if
				Merge(path, Settings.TARGET_PATH)
			else:
				raise Exception("Path '%s' was not found!" % path)

	def caseInsensativeFolder(self, path, when):
		files = os.listdir(path)

		while files:
			nextFile = files.pop()

			if nextFile.lower() == when.lower():
				return path + os.path.sep + nextFile

		return None


if __name__ == "__main__":
	Jenkins().run()
