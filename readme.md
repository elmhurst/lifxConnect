lifxConnect
==============

It's a little command line lifx controller using the http API and php.

Nothing too glamourous here just a useful way to learn the API.

running `php lifxconnect.php --help` provides the following info

```
Usage: php lifxconnect.php [-s] <selector> [-d] <data>

 -h	--help		view this list
 -ll	--list-lamps	List all lamps on network
 -ls	--list-scenes	List all scenes
 -cs	--choose-scene	a numbered prompt for scenes
 -sn	--scene-number	display a scene without prompting -sn 1
 -s	--selector	specify lights or groups, eg. -s label:desk lamp
  			(see http://api.developer.lifx.com/docs/selectors)
 -d	--data		pass additional parameters eg. color=red state=on
  			(see http://api.developer.lifx.com/docs/set-power)
 -a	--action	specify a specific action eg. -a toggle -s all
 -e	--effect	breathe or pulse effect. -e breathe -d color=red
```

**Requires a file in the project root called token.txt with your token in it to work**