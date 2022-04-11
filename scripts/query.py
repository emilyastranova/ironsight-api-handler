#!/usr/bin/env python3
import requests
import sys
import json
sys.path.insert(0, './ironsight_harvester_api')
import ironsight_harvester_api as ironsight
import time

# Example query
# curl --header "Authorization: ApiKey YU44dnIzNEJ0UFZoZkJIa19OYWs6emJRSk01LWhTMC1hNm0xMFBPUGZuUQ==" --header "Content-Type: application/json" -XGET "http://ssh.tylerharrison.dev:9200/_search" -d'
# {
#   "query": {
#     "match_all": {}
#   }
# }'

# Incoming data looks like this: '\{\"size\":100,\"aggs\":\{\"hostnames\":\{\"terms\":\{\"field\":\"host.name\",\"size\":100\}\}\}\}'

# Make a GET request to the Elastic API
def query_elastic(raw_query, index=""):
    # Undo PHP security escaping
    raw_query = raw_query.replace("\\", "")

    # Load in configuration file
    with open('config.json') as config_file:
        config = json.load(config_file)
        elastic_url = config['elasticsearch_url']
        elastic_token = config['elasticsearch_api_token']

    # elastic_url = elastic_url.replace(":8220", ":9200")
    if elastic_url[-1] != "/":
        elastic_url += "/"
    elastic_url = elastic_url + index + "/_search"
    
    headers = {"Content-Type" : "application/json", "Authorization": str("ApiKey " + elastic_token)}
    response = requests.get(elastic_url, headers=headers, data=raw_query)

    return json.dumps(response.json())

#!/usr/bin/env python3

import json
import feedparser
import requests
from pprint import pprint

# Get RSS feed from BleepingComputer and convert from XML to JSON
# Example: [{'title': 'Title', 'link': 'Link'}, {'title': 'Title', 'link': 'Link'}]
def get_news():

    url = 'https://www.bleepingcomputer.com/feed/'
    # Do request with Firefox User-Agent
    headers = {'User-Agent': 'Mozilla/5.0 (X11; Linux x86_64; rv:68.0) Gecko/20100101 Firefox/68.0'}
    response = requests.get(url, headers=headers)
    # Convert XML to JSON
    data = feedparser.parse(response.text)
    # Convert to JSON
    json_data = json.dumps(data)
    # Convert to Python dict
    data = json.loads(json_data)
    # Get news titles
    titles = []
    for item in data['entries']:
        title = item['title']
        # Remove doublequotes, singlequotes and newlines
        title = title.replace('"', '')
        title = title.replace("'", '')
        title = title.replace('\n', '')
        titles.append(title)
    # Get news links
    links = []
    for item in data['entries']:
        links.append(item['link'])
    # Return the news articles with a title and link per article as JSON
    articles = []
    for i in range(len(titles)):
        article = {'title': titles[i], 'link': links[i]}
        articles.append(article)
    articles = {'articles': articles}
    # Make article into JSON, handle single quotes and newlines
    articles = json.dumps(articles)
    articles = articles.replace("'", '"')
    articles = articles.replace('\n', '')
    return articles

def query_ironsight(raw_query, params=None):
    with open('config.json') as config_file:
        config = json.load(config_file)
        sql_server = config['sql_server']
        sql_user = config['sql_user']
        sql_pass = config['sql_pass']
        sql_db = config['sql_db']

    if raw_query == "get_users":
        return(ironsight.get_users())

    elif raw_query == "get_vms":
        return(ironsight.get_vms())

    elif raw_query == "get_labs":
        return(ironsight.get_labs())

    elif raw_query == "get_templates":
        return(ironsight.get_templates())
    
    elif raw_query == "get_tags":
        return(ironsight.get_tags())

    elif raw_query == "get_news":
        return(get_news())

    elif raw_query == "get_metrics":
        return(ironsight.get_metrics())

    elif raw_query == "get_cpu_usage":
        # If no params, return last 15 minutes CPU usage
        if len(params) == 0:
            # start_time is 15 minutes ago in epochs
            start_time = str(int(time.time()) - 900)
            # end_time is now in epochs
            end_time = str(time.time())
            step = str(60)
        elif len(params) == 1:
            # start_time is 15 minutes ago in epochs
            start_time = str(int(time.time()) - 900)
            # end_time is now in epochs
            end_time = str(time.time())
            step = str(params[0])
        elif len(params) == 2:
            start_time = params[0]
            end_time = params[1]
            step = params[2]
        return(ironsight.get_cpu_usage(start_time, end_time, step))

    elif raw_query == "get_network_usage":
        # If no params, return last 15 minutes CPU usage
        if len(params) == 0:
            # start_time is 15 minutes ago in epochs
            start_time = str(int(time.time()) - 900)
            # end_time is now in epochs
            end_time = str(time.time())
            step = str(60)
        elif len(params) == 1:
            # start_time is 15 minutes ago in epochs
            start_time = str(int(time.time()) - 900)
            # end_time is now in epochs
            end_time = str(time.time())
            step = str(params[0])
        elif len(params) == 2:
            start_time = params[0]
            end_time = params[1]
            step = params[2]
        return(ironsight.get_network_usage(start_time, end_time, step))

    elif raw_query == "get_memory_usage":
        # If no params, return last 15 minutes CPU usage
        if len(params) == 0:
            # start_time is 15 minutes ago in epochs
            start_time = str(int(time.time()) - 900)
            # end_time is now in epochs
            end_time = str(time.time())
            step = str(60)
        elif len(params) == 1:
            # start_time is 15 minutes ago in epochs
            start_time = str(int(time.time()) - 900)
            # end_time is now in epochs
            end_time = str(time.time())
            step = str(params[0])
        elif len(params) == 2:
            start_time = params[0]
            end_time = params[1]
            step = params[2]
        return(ironsight.get_memory_usage(start_time, end_time, step))

    elif raw_query == "get_disk_usage":
        # If no params, return last 15 minutes CPU usage
        if len(params) == 0:
            # start_time is 15 minutes ago in epochs
            start_time = str(int(time.time()) - 900)
            # end_time is now in epochs
            end_time = str(time.time())
            step = str(60)
        elif len(params) == 1:
            # start_time is 15 minutes ago in epochs
            start_time = str(int(time.time()) - 900)
            # end_time is now in epochs
            end_time = str(time.time())
            step = str(params[0])
        elif len(params) == 2:
            start_time = params[0]
            end_time = params[1]
            step = params[2]
        return(ironsight.get_disk_usage(start_time, end_time, step))

    elif raw_query == "get_node_names":
        return(ironsight.get_node_names())

    elif raw_query == "get_num_vms":
        return(ironsight.get_num_vms())

    elif raw_query == "get_num_vms_on":
        return(ironsight.get_num_vms_on())

    elif raw_query == "get_vms_on":
        return(ironsight.get_vms_on())

    elif raw_query == "get_classes":
        return(ironsight.get_classes())

    else:
        return "Invalid query"

if __name__ == "__main__":
    # Queries Elastic directly
    if "--elastic" in sys.argv:
        sys.argv.remove("--elastic")
        # Default: query all
        if len(sys.argv) < 2:
            query = ""
            print(query_elastic(query))
    
        # User specified query
        elif len(sys.argv) == 2:
            query = sys.argv[1]
            print(query_elastic(query))

        # User specified query and index
        elif len(sys.argv) == 3:
            query = sys.argv[1]
            index = sys.argv[2]
            print(query_elastic(query, index))
    
    # Queries SQL or Harvester
    elif "--data" in sys.argv:
        sys.argv.remove("--data")
        # Default: Return blank json
        if len(sys.argv) < 2:
            print("{}")
        # User specified query
        elif len(sys.argv) >= 2:
            query = sys.argv[1]
            print(query_ironsight(query, sys.argv[2:]))
