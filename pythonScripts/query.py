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

apiKey = "YU44dnIzNEJ0UFZoZkJIa19OYWs6emJRSk01LWhTMC1hNm0xMFBPUGZuUQ=="
headers = {"Content-Type" : "application/json", "Authorization": str("ApiKey " + apiKey)}

# Make a GET request to the API
def get(queryJSON):
    url = "http://ssh.tylerharrison.dev:9200/_search"
    response = requests.get(url, headers=headers, data=queryJSON)
    return json.dumps(response.json())

if __name__ == "__main__":
    if len(sys.argv) < 2:
        query = ""
    else:
        query = sys.argv[1]

    print(get(query))