from Exec import Exec

class Copy:

	def __init__(self, src, dest):
		Exec( "copy " + src + " " + dest )
		pass