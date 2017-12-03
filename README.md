# Personal Papers-Explorer

Personal Papers-Explorer is analogous to a private pdf papers search engine for a Phd researcher student at Texas State University.

## Features
* **Automatic metadata extraction** - Personal Papers-Explorer automatically extracts author, title and other related metadata for analysis and document search.
* **Full-text indexing** - Personal Papers-Explorer indexes the full-text of the entire pdf papers. Full boolean, phrase search is supported.
* **Individual core capability search** - Personal Papers-Explorer is flexible with multiple core search where cores can be created by user according to the categories of the papers. 

### Prerequisites

Below softwares should be installed to run the Project:
* [Apache Solr 7.1.0](http://www.apache.org/dyn/closer.lua/lucene/solr/7.1.0)
* [Python 2.7](https://www.python.org/downloads/)
* [PHP 7.0](http://php.net/downloads.php)
* [GROBID](http://cloud.science-miner.com/grobid/)
* [Curl](https://curl.haxx.se/download.html)
* [XAMPP](https://www.apachefriends.org/index.html)

### Project Installation

Setup the project by changing:
In settings.php under includes folder:
![image](https://user-images.githubusercontent.com/26471348/33521270-2a608758-d793-11e7-8dd3-abb9c4c2d317.png)

$target_dir - Enter the full path to the folder where you want to store the research papers.

In settings.py under pyCalculation folder:
![image](https://user-images.githubusercontent.com/26471348/33521239-37167d00-d792-11e7-8227-261c3b2a899b.png)

* importQuery - Solr UI url.
* fullPathToSolrBinFolder - C:\solr-7.1.0\bin
* fullPathToSolrServerFolder - C:\solr-7.1.0\server\solr
* pathToResearchPapersFolder - Enter the full path to the folder where you want to store the research papers.
    
#### Course

**CS5395-004** Independent study with [Dr.Anne Hee Hiong Ngu](http://cs.txstate.edu/~hn12/) at Texas State University.
