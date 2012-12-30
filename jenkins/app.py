EQUALS_LINE = "==================================================================================================="
DASH_LINE = "---------------------------------------------------------------------------------------------------"

import traceback
from helpers.ArgumentsHelper import ArgumentsHelper

from sys import argv
from common.switch import switch
from commands.Copy import Copy

class Jenkins:
	version = "0.0.1"

	argHelper = None

	def __init__(self):
		try:
			argHelper = ArgumentsHelper(argv)

			command = argHelper.getCommand()

			print "Command:", command

			for case in switch( command ):
				if case("copy"):
					src = argHelper.getNextArgument()
					dest = argHelper.getNextArgument()

					Copy(src, dest)

					break

				if case( "compile" ):
					print "TEST"

				if case():
					print "Invalid command: " + command

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

Jenkins()