from commands.BaseCommand import BaseCommand
from helpers.Execute import Execute
from model.Path import Path


class Shell(BaseCommand):
	path = None

	def __init__(self, path):
		self.path = Path(path)

		self.run()

	def run(self):
		if self.path.scheme == "ssh":
			executable = self.find_executable("ssh")

			path = self.path.path

			if not self.path.is_absolute:
				path = "./" + path

			command = [executable, self.path.get_ssh_path(), path ]

			Execute().run(command)
