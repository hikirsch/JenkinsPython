import sys

class ArgumentsHelper:
	arguments = None

	currentIndex = -1

	command = None

	def __init__(self, args):
		if len( args ) == 1:
			raise Exception("You must pass a command")

		self.arguments = args

		self.currentIndex = 0

		self.command = self.getNextArgument()

	def getNextArgument(self):
		self.currentIndex += 1

		if self.currentIndex < len( self.arguments ):
			return self.arguments[ self.currentIndex ]

		return None


	def getCount(self):
		return len( self.arguments ) - 2

	def getCommand(self):
		return self.command
