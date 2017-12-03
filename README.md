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

Change the directory names in settings.py under pyCalculation folder:

where 
* importQuery - Solr UI url.
* fullPathToSolrBinFolder 
    
* fullPathToSolrServerFolder
    
* pathToResearchPapersFolder - Enter the full path to the folder where you want to store the research papers.
    
#### Course

**CS5395-004** Independent study with [Dr.Anne Hee Hiong Ngu](http://cs.txstate.edu/~hn12/) at Texas State University.
