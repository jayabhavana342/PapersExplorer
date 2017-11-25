import re

from settings import *

newAnnotation = sys.argv[1]
id = sys.argv[2]
core = sys.argv[3]


def addAnnotation(annotation, id, core):
    pp = subprocess.Popen(
        "curl localhost:8983/solr/" + core + "/update?commit=true -H 'Content-type:application/json' --data-binary " + "\"[{'id':'" + id
        + "','annotation':{'set':'" + annotation + "'}}]\"",
        shell=True, stdout=subprocess.PIPE)
    text, err = pp.communicate()

    if text is not None:
        print text
    if err is not None:
        print err


addAnnotation(newAnnotation, id, core)
