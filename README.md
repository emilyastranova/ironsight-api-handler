# Ironsight API Handler

Backend code for responding to API calls from the React site

## Usage

Usage: `https://your_server_IP_address/get.php?q=` followed by your query

You can also specify an index pattern: `https://your_server_IP_address/get.php?q=`  followed by your query, `&i=` followed by the index pattern

Example:

```bash
https://your_server_IP_address/get.php?q='{"query": {"match_all": {}}}'
```

```bash
https://your_server_IP_address/get.php?q='{"query": {"match_all": {}}}'&i=metrics-*
```

## Installing Apache

Install the apache2 package

```bash
sudo apt-get update
sudo apt-get install apache2
```

Configure Apache:

```bash
sudo nano /etc/apache2/apache2.conf
```

There will be a line that looks like the following:

```bash
ServerName server_domain_or_IP
```

Change this line to reflect your actual server domain name or IP address. Alternatively, the server can also listen for all calls using:

```bash
ServerName 0.0.0.0
```

Run the configuration test to make sure the files have no syntax errors:

```bash
sudo apache2ctl configtest
sudo systemctl restart apache2
```

Open Firewall for UFW

```bash
sudo ufw allow in "Apache Full"
```

Test your server, you should see the test Apache page:

```bash
http://your_server_IP_address
```

## Installing PHP

```bash
sudo apt-get install php libapache2-mod-php
```

Configure Apache to use PHP extensions as well as HTML

```bash
sudo nano /etc/apache2/mods-enabled/dir.conf
```

Modify the file to look like this:

```html
<IfModule mod_dir.c>
    DirectoryIndex index.html index.cgi index.pl index.php index.xhtml index.htm
</IfModule>
```

Restart Apache2 and make sure everything is running (output should be green and/or and display "running"):

```bash
sudo systemctl restart apache2
sudo systemctl status apache2
```

## Adding the Ironsight API Handler to the PHP server

Clone the GitHub repository (or any method of copying the files) to /var/www/html/:

```bash
gh repo clone tamuc-ironsight/ironsight-api-handler
```

The directory tree should look like this:

```bash
.
├── get.php
├── index.html
├── logo.svg
├── pythonScripts
│   └── query.py
└── test.php

1 directory, 5 files
```

Test the setup by navigating to:

```bash
http://your_server_IP_address/get.php?q='{"query": {"match_all": {}}}'
```

If the browser returns a JSON format with the general information from Elastic (the same thing you would get from running the `curl` command with this data), then everything is setup properly

## Troubleshooting

- The most likely error is that the directory was not cloned the correct way. Refer to the above file directory tree to make sure everything is in the right spot. Another way to test if this is in the correct place is by testing Apache instead of PHP. Navigate to `http://your_server_IP_address/index.html` and the page should now be the modified default with the Ironsight logo and title.

- The PHP query may be formatted incorrectly or your browser may not be URL encoding the characters properly. To test this, visit `ttp://your_server_IP_address/test.php`. If this shows the data, then that is the case. Look at encoding the URL parameters with something [like this tool](https://meyerweb.com/eric/tools/dencoder/).
