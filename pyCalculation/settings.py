import os
import sys
import urllib2
import json
import subprocess

# to avoid other language's
reload(sys)
sys.setdefaultencoding('utf-8')

importQuery = 'http://localhost:8983/solr/'
fullPathToSolrBinFolder = "C:/solr-7.1.0/bin/solr/"
fullPathToSolrServerFolder = "C:/solr-7.1.0/server/solr/"
pathToResearchPapersFolder = "./ResearchPapers/"


def run_curl(curl_cmd):
    # print curl_cmd
    result, err = subprocess.Popen(curl_cmd, shell=True, stdout=subprocess.PIPE).communicate()
    if result is not None:
        print result
    if err is not None:
        print err
