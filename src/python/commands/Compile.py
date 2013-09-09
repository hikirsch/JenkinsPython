import os
from app import Settings
from commands.Copy import Copy


class Compile():
	def __init__(self, arg_helper):
		self.arg_helper = arg_helper

		command = self.arg_helper.getNextArgument()

		methodToCall = None

		try:
			methodToCall = getattr(self, command)
		except AttributeError:
			raise Exception("Command Not Found")

		if methodToCall is not None:
			methodToCall()

	def fake(self):
		projectType = self.arg_helper.getNextArgument()
		src = Settings.SOURCE_PATH + os.path.sep + projectType

		if os.path.exists(src):
			Copy(src, Settings.TARGET_PATH)
		else:
			raise Exception("source path '%s' does not exist" % src)

	def closure(self):
		from compilers.Closure import Closure

		Closure()

		pass
