#!/usr/bin/env python3
import requests
import sys
import json

# Example query
# curl --header "Authorization: ApiKey YU44dnIzNEJ0UFZoZkJIa19OYWs6emJRSk01LWhTMC1hNm0xMFBPUGZuUQ==" --header "Content-Type: application/json" -XGET "http://ssh.tylerharrison.dev:9200/_search" -d'
# {
#   "query": {
#     "match_all": {}
#   }
# }'

# Incoming data looks like this: '\{\"size\":100,\"aggs\":\{\"hostnames\":\{\"terms\":\{\"field\":\"host.name\",\"size\":100\}\}\}\}'

# Make a GET request to the API
def get(queryJSON, index=""):
    queryJSON = queryJSON.replace("\\", "")
    # Load in configuration file
    with open('../config.json') as config_file:
        config = json.load(config_file)
        sql_server = config['sql_server']
        sql_user = config['sql_user']
        sql_pass = config['sql_pass']
        sql_db = config['sql_db']

        elastic_url = config['elasticsearch_url']
        elastic_token = config['elasticsearch_api_token']

    # Swap port to 9200 for Kibana
    # TODO: Make this go through Nginx and have it divide traffic
    elastic_url = elastic_url.replace(":8220", ":9200")
    elastic_url = elastic_url + index + "/_search"
    headers = {"Content-Type" : "application/json", "Authorization": str("ApiKey " + elastic_token)}
    response = requests.get(elastic_url, headers=headers, data=queryJSON)
    return json.dumps(response.json())

if __name__ == "__main__":
    # Default: query all
    if len(sys.argv) < 2:
        query = ""
        print(get(query))
    
    # User specified query
    elif len(sys.argv) == 2:
        query = sys.argv[1]
        print(get(query))

    # User specified query and index
    elif len(sys.argv) == 3:
        query = sys.argv[1]
        index = sys.argv[2]
        print(get(query, index))
