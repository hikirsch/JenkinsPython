import os

from helpers.Execute import Execute


class GitRevision:
    def __init__(self):
        pass

    def write_revision(self, folder="target", filename=".git_revision"):
        output = Execute().get_output(['git', 'describe', '--tags', '--always', 'HEAD'])

        if os.path.exists(filename):
            os.remove(filename)

        full_path = "%s/%s" % ( folder, filename )

        file = open(full_path, "w")
        revision = "".join(output).strip()
        file.write(revision)
        file.close()

        print "Git revision %s written to %s" % ( revision, full_path )