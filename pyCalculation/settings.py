import os
import sys
import subprocess

importQuery = 'http://localhost:8983/solr/'
fullPathToSolrBinFolder = "C:/solr-6.6.0/bin/solr"
pathToResearchPapersFolder = "./ResearchPapers/"

def run_curl(curl_cmd):
    result, err = subprocess.Popen(curl_cmd, shell=True, stdout=subprocess.PIPE).communicate()
    print result,err