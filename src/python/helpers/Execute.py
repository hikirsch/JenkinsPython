import os
import subprocess


class Execute:
	def get_output(self, command):
		output_contents = []
		cleanProcess = subprocess.Popen(command, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
		output = cleanProcess.communicate()[0].split("\n")

		for line in output:
			output_contents.append(line)

		return output_contents

	def run(self, command):
		os.system(" ".join(command))