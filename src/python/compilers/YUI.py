import os
from app import Settings
from helpers.Execute import Execute
from model.Path import Path


class YUI:
	JAR_FILE_NAME = "yuicompressor-2.4.2.jar"

	CONFIG_PATH = Path(Settings.BUILD_ASSETS_PATH + os.path.sep + "minify" + os.path.sep + "css.txt")

	YUI_EXECUTE_PARAMS = "--type css".split(" ")

	compiler_path = None

	def __init__(self):
		self.compiler_path = Path(os.path.dirname(os.path.abspath(__file__)) + os.path.sep + self.JAR_FILE_NAME)

		self.YUI_EXECUTE_PARAMS = ["java", "-jar", self.compiler_path.path] + self.YUI_EXECUTE_PARAMS

		print "Compiler Path: %s" % self.compiler_path.path
		print "Config Path: %s" % self.CONFIG_PATH.path

		if self.CONFIG_PATH.exists():
			self.run()

	def run(self):
		config_file = self.read_file(self.CONFIG_PATH.path)

		dest_filename = Path(Settings.SOURCE_PATH + os.path.sep + config_file[0])
		print "Destination is %s" % dest_filename.path

		other_files = config_file[1:]

		dest_file_contents = []

		for line in other_files:
			firstCharacter = line[:1]

			if firstCharacter == "+":
				path = Path(Settings.SOURCE_PATH + os.path.sep + line[1:])

				if path.exists():
					print "Adding '%s'" % path.path
					dest_file_contents += self.read_file(path.path) + ["\n"]
				else:
					print "can not append file '%s'" % path.path

			else:
				path = Path(Settings.SOURCE_PATH + os.path.sep + line)

				if path.exists():
					print "Minifying '%s'" % path.path
					dest_file_contents += self.run_compiler(path) + ["\n"]

				else:
					print "File '%s' does not exist!" % path.path

		self.save_file(dest_filename, "".join(dest_file_contents))

	def run_compiler(self, path):
		command = self.YUI_EXECUTE_PARAMS + [path.path]
		return Execute().get_output(command)

	def read_file(self, file_path):
		contents = []

		open_file = open(file_path)

		while 1:

			line = open_file.readline()

			if not line:
				break

			contents.append(line)

		return contents

	def save_file(self, file_path, contents):
		f = open(file_path.path, "w")
		f.write(contents)
		f.close()

		print "File '%s' has been saved successfully." % file_path.path

