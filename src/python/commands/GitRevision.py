import os
from helpers.Execute import Execute


class GitRevision:

    def __init__(self):
        pass

    def write_revision(self, filename=".git_revision"):

        output = Execute().get_output(['git', 'describe', '--tags', '--always', 'HEAD'])

        if os.path.exists(filename):
            os.remove(filename)

        file = open(filename, "w")
        file.write("".join(output).strip())
        file.close()