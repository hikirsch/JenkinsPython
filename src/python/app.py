# !/usr/bin/python
import os
import shutil
import traceback
from sys import argv

from model.Path import Path
from helpers.ArgumentsHelper import ArgumentsHelper


EQUALS_LINE = "==================================================================================================="
DASH_LINE = "---------------------------------------------------------------------------------------------------"


class Settings:
    BUILD_ASSETS_PATH = "build-assets"
    TARGET_PATH = "target"
    SOURCE_PATH = "src"
    MINIFY_PATH = "minify"


class Jenkins:
    version = "0.0.1"

    arg_helper = None

    def __init__(self):
        self.arg_helper = ArgumentsHelper(argv)

    def run(self):
        try:
            command = self.arg_helper.getCommand()

            lst = [word[0].upper() + word[1:] for word in command.split("-")]
            command = "".join(lst)
            command = command[:1].lower() + command[1:]

            print "Command Detected: ", command

            methodToCall = None

            try:
                methodToCall = getattr(self, command)
            except AttributeError:
                raise Exception("Command Not Found")

            if methodToCall is not None:
                methodToCall()

        except Exception as e:
            print ""
            print EQUALS_LINE
            print "Exception"
            print EQUALS_LINE
            print "An error occurred! "
            print e
            print DASH_LINE
            print traceback.format_exc()

            raise e

    def clean(self):
        path = Path(Settings.TARGET_PATH)

        if path.exists():
            shutil.rmtree(path.path)
            print "Removed '%s'" % Settings.TARGET_PATH
        else:
            print "Nothing to clean"

    def compile(self):
        from commands.Compile import Compile

        Compile(self.arg_helper)

    def copy(self):
        from commands.Copy import Copy

        src = self.arg_helper.getNextArgument()
        dest = self.arg_helper.getNextArgument()

        Copy(src, dest)

    def merge(self):
        from commands.Merge import Merge

        src = self.arg_helper.getNextArgument()
        dest = self.arg_helper.getNextArgument()

        Merge(src, dest)

    def deploy(self):
        from commands.Copy import Copy

        dest = self.arg_helper.getNextArgument()

        Copy(Settings.TARGET_PATH, dest)

    def shell(self):
        from commands.Shell import Shell

        path = self.arg_helper.getNextArgument()

        Shell(path)


    def buildAssets(self):
        from commands.Merge import Merge

        when = self.arg_helper.getNextArgument()
        env = self.arg_helper.getNextArgument()

        if env is None and "ENV" in os.environ:
            env = os.environ["ENV"]

        if env is None:
            raise Exception("No environment found")

        else:
            when_path = self.caseInsensativeFolder(Settings.BUILD_ASSETS_PATH, when + "-build")
            path = self.caseInsensativeFolder(when_path, env)

            if os.path.exists(path):
                if when == "pre":
                    Merge(path, Settings.SOURCE_PATH)
                else:
                    Merge(path, Settings.TARGET_PATH)
            else:
                raise Exception("Path '%s' was not found!" % path)

    def caseInsensativeFolder(self, path, when):
        files = os.listdir(path)

        while files:
            nextFile = files.pop()

            if nextFile.lower() == when.lower():
                return path + os.path.sep + nextFile

        return None


    def gitRevision(self ):
        from commands.GitRevision import GitRevision

        GitRevision().write_revision()


if __name__ == "__main__":
    Jenkins().run()
