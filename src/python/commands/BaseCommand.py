import os


class BaseCommand():
	def find_executable(self, executable):
		locations = os.environ.get("PATH").split(os.pathsep)

		for location in locations:
			candidate = os.path.join(location, executable)
			if os.path.isfile(candidate):
				return candidate

		return None